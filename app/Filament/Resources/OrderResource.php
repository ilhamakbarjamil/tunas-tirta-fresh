<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
                Tables\Columns\TextColumn::make('id')
                    ->label('Order ID')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable(),

                // MENAMPILKAN ALAMAT DI TABEL DEPAN (Disingkat)
                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat Tujuan')
                    ->limit(20) // Biar tabel gak kepanjangan
                    ->tooltip(fn ($state) => $state), // Hover buat lihat full

                Tables\Columns\TextColumn::make('total_price')
                    ->money('IDR')
                    ->label('Total'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processed' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Tanggal')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
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