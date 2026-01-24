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
                // Kita bungkus dalam Section biar rapi
                Forms\Components\Section::make('Detail Produk')
                    ->description('Isi informasi utama buah segar di sini.')
                    ->schema([
                        
                        // 1. INPUT NAMA PRODUK (Yang tadi hilang)
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(255)
                            // FITUR KEREN: Saat nama diketik, Slug otomatis terisi!
                            ->live(onBlur: true) 
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => 
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            ),

                        // Input Slug (Terisi otomatis, jadi kita disable saja biar aman)
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug URL (Otomatis)')
                            ->disabled()
                            ->dehydrated() // Tetap dikirim ke database meski disabled
                            ->required()
                            ->unique(Product::class, 'slug', ignoreRecord: true),

                        // 2. UPLOAD FOTO (Perbaikan dari TextInput jadi FileUpload)
                        Forms\Components\FileUpload::make('image')
                            ->label('Foto Produk')
                            ->image()
                            ->directory('products')
                            ->imagePreviewHeight('250')
                            ->required()
                            ->columnSpanFull(),

                        // Kategori
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        // Harga
                        Forms\Components\TextInput::make('price')
                            ->label('Harga Produk')
                            ->helperText(fn ($get) => 
                                count($get('variants') ?? []) > 0 
                                    ? '⚠️ Harga ini TIDAK digunakan! Harga sebenarnya diisi di VARIAN di bawah.' 
                                    : '✅ Harga produk satuan (wajib diisi karena tidak ada varian)'
                            )
                            ->required(fn ($get) => count($get('variants') ?? []) === 0)
                            ->numeric()
                            ->prefix('Rp')
                            ->live(),
                        
                        // Stock produk (untuk produk tanpa varian/satuan)
                        Forms\Components\TextInput::make('stock')
                            ->label('Stok Produk Satuan')
                            ->helperText(fn ($get) => 
                                count($get('variants') ?? []) > 0 
                                    ? 'Tidak digunakan (stok dikelola per varian)' 
                                    : 'Stok produk satuan (wajib diisi jika tidak ada varian)'
                            )
                            ->required(fn ($get) => count($get('variants') ?? []) === 0)
                            ->numeric()
                            ->default(0)
                            ->visible(fn ($get) => true)
                            ->disabled(fn ($get) => count($get('variants') ?? []) > 0)
                            ->live(),

                        Forms\Components\Section::make('Variasi Produk (Opsional)')
                            ->description('Pilih: Produk SATUAN (kosongkan varian) atau Produk VARIAN (tambah varian seperti 1 Kg, 500gr, Pack, dll)')
                            ->schema([
                                Forms\Components\Repeater::make('variants')
                                    ->label('Daftar Varian')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama Varian')
                                            ->placeholder('Contoh: 1 Kg, 500gr, Pack, dll')
                                            ->required(),

                                        Forms\Components\TextInput::make('price')
                                            ->label('Harga Varian')
                                            ->helperText('✅ Harga yang digunakan saat user beli varian ini')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->required(),

                                        Forms\Components\TextInput::make('stock')
                                            ->label('Stok')
                                            ->numeric()
                                            ->default(0)
                                            ->required(),
                                    ])
                                    ->columns(3)
                                    ->defaultItems(0)
                                    ->addActionLabel('Tambah Varian Baru')
                                    ->reorderable()
                                    ->deletable()
                                    ->live()
                            ])
                            ->columnSpanFull(),

                        // Deskripsi
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Singkat')
                            ->rows(3)
                            ->columnSpanFull(),

                    ])->columns(2), // Menggunakan layout 2 kolom
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. FOTO KECIL (Biar cantik)
                Tables\Columns\ImageColumn::make('image')
                    ->label('Foto'),

                // 2. NAMA PRODUK (Bisa dicari)
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->sortable()
                    ->searchable(), // <--- PENTING: Biar bisa search di admin

                // 3. KATEGORI
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),

                // 4. HARGA
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable()
                    ->description(fn ($record) => 
                        $record->variants()->count() > 0 
                            ? 'Harga referensi (harga di varian)' 
                            : 'Harga produk satuan'
                    ),

                // 5. STOK
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->sortable()
                    ->description(fn ($record) => 
                        $record->variants()->count() > 0 
                            ? 'Stok di varian' 
                            : 'Stok produk satuan'
                    ),

                // 6. JUMLAH VARIAN
                Tables\Columns\TextColumn::make('variants_count')
                    ->label('Jumlah Varian')
                    ->counts('variants')
                    ->sortable()
                    ->description(fn ($record) => 
                        $record->variants()->count() === 0 ? 'Produk Satuan' : 'Produk Varian'
                    ),
            ])
            ->filters([
                // Filter Kategori (Opsional, biar gampang sortir)
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
