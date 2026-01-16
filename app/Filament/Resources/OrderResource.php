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
                // KOTAK 1: Info Pesanan
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Utama')
                            ->schema([
                                // Nama Pelanggan (Tidak bisa diedit admin)
                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->disabled() 
                                    ->label('Pelanggan'),
                                
                                // Status (Bisa diedit admin)
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'pending' => 'Pending (Belum Dibayar)',
                                        'processed' => 'Diproses (Sedang Disiapkan)',
                                        'completed' => 'Selesai (Dikirim)',
                                        'cancelled' => 'Dibatalkan',
                                    ])
                                    ->required()
                                    ->label('Status Pesanan'),

                                // Total Harga (Otomatis)
                                Forms\Components\TextInput::make('total_price')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->label('Total Belanja'),
                            ])->columns(2),
                    ]),

                // KOTAK 2: Daftar Barang (Rincian)
                Forms\Components\Section::make('Rincian Barang Belanjaan')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->label('Produk')
                                    ->disabled(), // Admin cuma lihat, jangan ubah
                                
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->suffix('x') // Tampilan: "2 x"
                                    ->disabled(),

                                Forms\Components\TextInput::make('price')
                                    ->label('Harga saat itu')
                                    ->prefix('Rp')
                                    ->disabled(),
                            ])
                            ->addable(false)    // Matikan tombol Tambah
                            ->deletable(false)  // Matikan tombol Hapus
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('No. Order')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_price')
                    ->money('IDR')
                    ->label('Total'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',   // Kuning
                        'processed' => 'info',    // Biru
                        'completed' => 'success', // Hijau
                        'cancelled' => 'danger',  // Merah
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Tanggal Order')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc') // Pesanan terbaru di atas
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