<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Pilih Kategori (Relasi ke tabel categories)
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->label('Kategori Produk'),

                // Nama Produk
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(
                        fn(string $operation, $state, \Filament\Forms\Set $set) =>
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null
                    ),

                TextInput::make('slug')
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->unique(ignoreRecord: true),

                // Harga (Tambahkan prefix 'Rp' biar cantik)
                TextInput::make('price')
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Harga Satuan')
                    ->nullable(), // Boleh kosong sesuai request klien

                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Harga Satuan'),

                // --- TAMBAHKAN INI ---
                Forms\Components\TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(10) // Contoh default 10
                    ->label('Stok Barang'),
                // ---------------------    

                Forms\Components\Repeater::make('variants')
                    ->relationship()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Satuan (Misal: 500gr, 1Kg)')
                            ->required(),
                        Forms\Components\TextInput::make('price')
                            ->label('Harga Varian')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                    ])
                    ->columnSpanFull()
                    ->label('Varian Ukuran/Berat (Opsional)'),

                // Upload Gambar
                FileUpload::make('image')
                    ->image() // Validasi harus file gambar
                    ->directory('products') // Disimpan di folder products
                    ->label('Foto Produk'),

                // Deskripsi
                Textarea::make('description')
                    ->columnSpanFull(), // Agar lebarnya full

                // Stok Tersedia?
                Toggle::make('is_available')
                    ->label('Stok Tersedia')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
