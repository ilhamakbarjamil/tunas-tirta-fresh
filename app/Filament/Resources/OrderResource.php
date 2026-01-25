<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Pesanan Masuk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // KOTAK KIRI: Info Utama & Pengiriman
                Forms\Components\Group::make()
                    ->schema([
                        // 1. DATA PESANAN
                        Forms\Components\Section::make('Informasi Utama')
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->disabled()
                                    ->label('Pelanggan'),

                                Forms\Components\Select::make('status')
                                    ->options([
                                        'pending' => 'Pending (Belum Dibayar)',
                                        'processed' => 'Diproses (Sedang Disiapkan)',
                                        'completed' => 'Selesai (Dikirim)',
                                        'cancelled' => 'Dibatalkan',
                                    ])
                                    ->required()
                                    ->label('Status Pesanan'),

                                Forms\Components\TextInput::make('total_price')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->label('Total Belanja'),
                            ])->columns(2),

                        // 2. INFO PENGIRIMAN (INI YANG BARU KITA TAMBAHKAN)
                        Forms\Components\Section::make('Info Pengiriman')
                            ->schema([
                                Forms\Components\Textarea::make('address')
                                    ->label('Alamat Lengkap')
                                    ->rows(3)
                                    ->columnSpanFull()
                                    ->disabled(), // Admin cuma baca, biar gak salah ubah

                                Forms\Components\Textarea::make('note')
                                    ->label('Catatan User')
                                    ->placeholder('Tidak ada catatan')
                                    ->columnSpanFull()
                                    ->disabled(),
                            ]),
                    ])->columnSpan(2), // Ambil 2/3 layar

                // KOTAK KANAN: Rincian Barang (Agar layout lebih rapi)
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Rincian Barang')
                            ->schema([
                                Forms\Components\Repeater::make('items')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\Select::make('product_id')
                                            ->relationship('product', 'name')
                                            ->label('Produk')
                                            ->disabled(),

                                        Forms\Components\TextInput::make('quantity')
                                            ->label('Qty')
                                            ->suffix('x')
                                            ->disabled(),

                                        Forms\Components\TextInput::make('price')
                                            ->label('Harga')
                                            ->prefix('Rp')
                                            ->disabled(),
                                    ])
                                    ->addable(false)
                                    ->deletable(false)
                                    ->disableItemMovement()
                            ]),
                    ])->columnSpan(1), // Ambil 1/3 layar
            ])->columns(3); // Layout grid 3 kolom
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Ganti ID biasa dengan External ID (biar sama kayak Invoice)
                Tables\Columns\TextColumn::make('external_id')
                    ->label('Order ID')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable(),

                // 2. Kolom Alamat (Kode Mas yang lama)
                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat Tujuan')
                    ->limit(20)
                    ->tooltip(fn($state) => $state),

                Tables\Columns\TextColumn::make('total_price')
                    ->money('IDR')
                    ->label('Total'),

                // 3. Kolom Resi (BARU: Biar admin bisa lihat resi yg sudah diinput)
                Tables\Columns\TextColumn::make('resi')
                    ->label('No. Resi')
                    ->copyable() // Biar bisa dicopy admin
                    ->placeholder('-'),

                // 4. Update Warna Status (Menyesuaikan Status Midtrans)
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',   // Kuning
                        'paid' => 'success',      // Hijau (LUNAS)
                        'shipped' => 'info',      // Biru (DIKIRIM)
                        'failed' => 'danger',     // Merah
                        'cancelled' => 'danger',  // Merah
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Tanggal')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                // Action Edit Bawaan
                Tables\Actions\EditAction::make(),

                // 🔥 ACTION BARU: INPUT RESI + KIRIM WA 🔥
                Action::make('updateResi')
                    ->label('Kirim Barang')
                    ->icon('heroicon-o-truck') // Ikon Truk
                    ->color('info') // Warna Biru
                    ->modalHeading('Update Resi Pengiriman')
                    ->modalDescription('Masukkan nomor resi. Notifikasi WA akan otomatis dikirim ke pembeli.')
                    ->form([
                        TextInput::make('resi')
                            ->label('Nomor Resi (JNE/J&T/SiCepat)')
                            ->required()
                            ->placeholder('Contoh: JP1234567890'),
                    ])
                    ->action(function (Order $record, array $data) {
                        // A. Update Database
                        $record->update([
                            'resi' => $data['resi'],
                            'status' => 'shipped' // Ubah status jadi DIKIRIM
                        ]);

                        // B. Kirim WA Otomatis (Fonnte)
                        // Catatan: Targetnya kita arahkan ke Admin dulu buat testing
                        $targetPhone = $record->phone;

                        if (empty($targetPhone)) {
                            $targetPhone = env('WHATSAPP_ADMIN');
                        }
                        $token = env('FONNTE_TOKEN');

                        $message = "*PAKET SEDANG DIKIRIM!* 🚚\n\n";
                        $message .= "Halo kak {$record->user->name},\n";
                        $message .= "Pesanan #{$record->external_id} sudah kami kirim.\n\n";
                        $message .= "📦 *No. Resi:* {$data['resi']}\n\n";
                        $linkInvoice = route('invoice.public', $record->external_id); // Pake route yang baru
                        $message .= "📄 *Lihat Invoice:* \n$linkInvoice\n\n";
                        $message .= "Alamat: {$record->address}\n\n";
                        $message .= "Terima kasih sudah belanja di Tunas Tirta Fresh! 🍎";

                        try {
                            Http::withHeaders(['Authorization' => $token])->post('https://api.fonnte.com/send', [
                                'target' => $targetPhone,
                                'message' => $message,
                            ]);
                        } catch (\Exception $e) {
                        }

                        // C. Notifikasi Sukses di Pojok Kanan Atas Admin
                        Notification::make()
                            ->title('Berhasil!')
                            ->body('Resi disimpan & WA terkirim ke pembeli.')
                            ->success()
                            ->send();
                    })
                    // Tombol ini HANYA MUNCUL kalau statusnya 'paid' atau 'shipped'
                    ->visible(fn(Order $record) => in_array($record->status, ['paid', 'shipped'])),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}