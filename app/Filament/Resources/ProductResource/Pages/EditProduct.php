<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Validasi: Pastikan produk punya varian ATAU stok produk diisi
        $hasVariants = isset($data['variants']) && count($data['variants']) > 0;
        
        if (!$hasVariants) {
            // Produk satuan: pastikan harga dan stok diisi
            if (empty($data['price']) || $data['price'] <= 0) {
                throw ValidationException::withMessages([
                    'price' => 'Harga wajib diisi untuk produk satuan.',
                ]);
            }
            if (empty($data['stock']) && $data['stock'] !== '0') {
                throw ValidationException::withMessages([
                    'stock' => 'Stok wajib diisi untuk produk satuan.',
                ]);
            }
        } else {
            // Produk varian: pastikan semua varian valid
            foreach ($data['variants'] as $index => $variant) {
                if (empty($variant['name']) || empty($variant['price']) || empty($variant['stock'])) {
                    throw ValidationException::withMessages([
                        'variants' => "Varian #" . ($index + 1) . " harus lengkap (nama, harga, stok).",
                    ]);
                }
            }
        }

        return $data;
    }
}
