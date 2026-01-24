<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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
            // Cek apakah stock ada di array, jika tidak set default 0
            if (!isset($data['stock'])) {
                $data['stock'] = 0;
            }
            if (empty($data['stock']) && $data['stock'] !== '0' && $data['stock'] !== 0) {
                throw ValidationException::withMessages([
                    'stock' => 'Stok wajib diisi untuk produk satuan.',
                ]);
            }
        } else {
            // Produk varian: pastikan semua varian valid
            foreach ($data['variants'] as $index => $variant) {
                if (empty($variant['name']) || empty($variant['price']) || !isset($variant['stock'])) {
                    throw ValidationException::withMessages([
                        'variants' => "Varian #" . ($index + 1) . " harus lengkap (nama, harga, stok).",
                    ]);
                }
            }
        }

        return $data;
    }
}
