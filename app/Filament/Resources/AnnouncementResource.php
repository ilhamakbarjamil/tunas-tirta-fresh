<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Filament\Resources\AnnouncementResource\RelationManagers;
use App\Models\Announcement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Konten Promo')
                    ->schema([
                        // 1. Upload Gambar
                        FileUpload::make('image')
                            ->label('Banner Promo')
                            ->image()
                            ->directory('announcements')
                            ->columnSpanFull(),

                        // 2. Judul & Status
                        TextInput::make('title')
                            ->label('Judul Promo')
                            ->placeholder('Contoh: Grand Opening Sale!')
                            ->required(),
                        
                        Toggle::make('is_active')
                            ->label('Aktifkan Promo Ini?')
                            ->default(true)
                            ->inline(false),

                        // 3. Deskripsi
                        Textarea::make('description')
                            ->label('Isi Pesan')
                            ->rows(3)
                            ->columnSpanFull(),

                        // 4. Tombol Aksi
                        TextInput::make('button_text')
                            ->label('Teks Tombol')
                            ->default('Belanja Sekarang'),
                        
                        TextInput::make('button_link')
                            ->label('Link Tujuan (Opsional)')
                            ->placeholder('Contoh: /category/fresh-fruits')
                            ->helperText('Kosongkan jika ingin menutup popup saja.'),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->label('Banner'),
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')->label('Aktif'), // Bisa on/off langsung dari tabel
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Dibuat'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
