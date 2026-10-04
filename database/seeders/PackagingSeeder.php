<?php

namespace Database\Seeders;

use App\Models\Packaging;
use Illuminate\Database\Seeder;

class PackagingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packagingsData = [
            [
                'name' => 'Bolsa de Satén & Lazo MORAIA (1-2 prendas)',
                'description' => 'Bolsa satinada suave con cordón sedoso, tarjeta distintiva y aroma sutil de la marca.',
                'capacity' => '1 a 2 prendas o accesorios',
                'price' => 0.00,
                'image_path' => 'images/packagings/bolsa_saten.jpg',
                'sort_order' => 1,
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'name' => 'Caja Rígida de Lujo Moraia (2-4 prendas)',
                'description' => 'Caja rígida rosa empolvado con lazo de satén hecho a mano, papel de seda y tarjeta de dedicatoria.',
                'capacity' => '2 a 4 prendas o pijamas',
                'price' => 0.00,
                'image_path' => 'images/packagings/caja_lujo.jpg',
                'sort_order' => 2,
                'is_active' => true,
                'is_default' => false,
            ],
            [
                'name' => 'Caja Grande Especial Regalo (5+ prendas)',
                'description' => 'Caja espaciosa de lujo con cinta de satén, papel de seda perfumado, ideal para sets combinados y momentos inolvidables.',
                'capacity' => '5+ prendas o sets completos',
                'price' => 0.00,
                'image_path' => 'images/packagings/caja_especial.jpg',
                'sort_order' => 3,
                'is_active' => true,
                'is_default' => false,
            ],
            [
                'name' => 'Cofre Edición Especial San Valentín / Aniversario',
                'description' => 'Presentación romántica exclusiva con lazo de satén carmín, pétalos aromáticos decorativos y tarjeta caligrafiada.',
                'capacity' => 'Hasta 4 prendas + accesorios de regalo',
                'price' => 3.50,
                'image_path' => 'images/packagings/caja_especial.jpg',
                'sort_order' => 4,
                'is_active' => true,
                'is_default' => false,
            ],
        ];

        foreach ($packagingsData as $pData) {
            Packaging::updateOrCreate(
                ['name' => $pData['name']],
                $pData
            );
        }
    }
}
