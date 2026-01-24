<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\Toggle; // Import Toggle Form
use Filament\Tables\Columns\ToggleColumn; // Import Toggle Tabel

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    // Icon Box/Keranjang
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // --- BAGIAN 1: INFO UTAMA ---
                Forms\Components\Section::make('Informasi Produk')
                    ->description('Detail utama produk.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => 
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->unique(Product::class, 'slug', ignoreRecord: true),

                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('image')
                            ->label('Foto Produk')
                            ->image()
                            ->directory('products')
                            ->visibility('public')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Singkat')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                // --- BAGIAN 2: HARGA & KETERSEDIAAN (YANG KITA UBAH) ---
                Forms\Components\Section::make('Harga & Status')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->label('Harga Utama')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),

                        // 🔥 REVISI YANG BENAR 🔥
                        Toggle::make('is_available')
                            ->label('Status Stok')
                            // ->onLabel(...) <--- INI HAPUS AJA, GAK BISA DI FORM
                            // ->offLabel(...) <--- INI JUGA HAPUS
                            ->onIcon('heroicon-m-check') // Pakai Icon Centang
                            ->offIcon('heroicon-m-x-mark') // Pakai Icon Silang
                            ->onColor('success') // Hijau
                            ->offColor('danger') // Merah
                            ->inline(false) 
                            ->helperText('Aktifkan jika barang tersedia. Matikan jika habis.') // Ganti pakai helper text biar jelas
                            ->default(true)
                            ->required(),

                        Forms\Components\TextInput::make('unit')
                            ->label('Satuan')
                            ->placeholder('Pcs, Kg, Pack')
                            ->required()
                            ->default('Pcs'), 
                    ])->columns(3),

                // --- BAGIAN 3: VARIAN ---
                Forms\Components\Section::make('Varian Produk (Opsional)')
                    ->description('Isi jika produk punya pilihan (misal: Sirloin/Tenderloin).')
                    ->collapsed()
                    ->schema([
                        Forms\Components\Repeater::make('variants')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Varian')
                                    ->placeholder('Contoh: Sirloin / 1 Kg')
                                    ->required(),

                                Forms\Components\TextInput::make('price')
                                    ->label('Harga Varian')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),
                                
                                // Stok Varian kita sembunyikan & set 999 biar gak ribet
                                Forms\Components\Hidden::make('stock')
                                    ->default(999),
                            ])
                            ->columns(2) // Jadi 2 kolom saja (Nama & Harga)
                            ->addActionLabel('Tambah Varian Baru')
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Foto'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori'),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR'),

                // 🔥 SAKLAR LANGSUNG DI TABEL 🔥
                // Bisa klik langsung ON/OFF tanpa masuk menu edit
                ToggleColumn::make('is_available')
                    ->label('Ketersediaan')
                    ->onColor('success')
                    ->offColor('danger'),

                Tables\Columns\TextColumn::make('unit')
                    ->label('Satuan')
                    ->badge()
                    ->color('gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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