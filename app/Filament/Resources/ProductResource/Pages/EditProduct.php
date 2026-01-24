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
        $hasVariants = isset($data['variants']) && is_array($data['variants']) && count($data['variants']) > 0;
        
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
            // Produk varian: pastikan semua varian valid dan lengkap
            $validVariants = [];
            foreach ($data['variants'] as $index => $variant) {
                // Skip varian yang masih kosong (saat form sedang diisi)
                if (!isset($variant) || !is_array($variant)) {
                    continue;
                }
                
                // Validasi varian yang sudah diisi
                if (isset($variant['name']) && isset($variant['price']) && isset($variant['stock'])) {
                    if (empty($variant['name']) || empty($variant['price']) || $variant['price'] <= 0) {
                        throw ValidationException::withMessages([
                            'variants.' . $index . '.name' => "Varian #" . ($index + 1) . ": Nama dan harga wajib diisi.",
                        ]);
                    }
                    // Set default stock jika kosong
                    if (!isset($variant['stock']) || $variant['stock'] === '' || $variant['stock'] === null) {
                        $variant['stock'] = 0;
                    }
                    $validVariants[] = $variant;
                } elseif (!empty($variant['name']) || !empty($variant['price'])) {
                    // Jika ada sebagian data tapi tidak lengkap
                    throw ValidationException::withMessages([
                        'variants.' . $index . '.name' => "Varian #" . ($index + 1) . " harus lengkap (nama, harga, stok).",
                    ]);
                }
            }
            
            // Pastikan minimal ada 1 varian yang valid
            if (count($validVariants) === 0) {
                throw ValidationException::withMessages([
                    'variants' => 'Minimal harus ada 1 varian yang lengkap (nama, harga, stok).',
                ]);
            }
            
            // Update data dengan varian yang valid
            $data['variants'] = $validVariants;
        }

        return $data;
    }
}
