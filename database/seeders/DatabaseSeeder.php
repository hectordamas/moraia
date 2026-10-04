<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Packaging;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin User
        User::updateOrCreate(
            ['email' => 'admin@moraia.com'],
            [
                'name' => 'Airan Zambrano',
                'password' => Hash::make('moraia2026'),
                'role' => 'admin',
            ]
        );

        // 2. Settings
        $settings = [
            'site_name' => 'MORAIA',
            'site_tagline' => 'El arte de consentirte en cada detalle',
            'contact_whatsapp' => '+584120206548',
            'contact_whatsapp_clean' => '584120206548',
            'contact_email' => 'By.moraia@gmail.com',
            'contact_instagram' => '@by.moraia',
            'contact_instagram_url' => 'https://instagram.com/by.moraia',
            'shipping_caracas_price' => '3.00',
            'shipping_caracas_time' => 'Entrega el mismo día o en 24h',
            'shipping_nacional_note' => 'Envíos asegurados a toda Venezuela por MRW, Zoom y Tealca.',
            'announcement_bar_text' => '🌸 Envíos a toda Venezuela | Delivery propio en Caracas | Atención personalizada por WhatsApp',
            'about_manifesto' => 'Moraia existe para reunir en una sola caja todo lo que una mujer necesita para consentirse: lo que se pone, lo que la embellece, lo que la hace sentir deseada y lo que la hace sonreír al abrirla. Regala experiencia.',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        // 3. Categories
        $categoriesData = [
            [
                'name' => 'Pijamas',
                'slug' => 'pijamas',
                'description' => 'Sets de seda satinada, lino suave y modal pensados para noches de descanso con máxima elegancia.',
                'image_path' => 'images/categories/cat_pijamas.jpg',
                'sort_order' => 1,
                'is_active' => true,
                'is_featured' => true,
                'seo_title' => 'Pijamas de Seda y Satén para Mujer | Moraia',
                'seo_description' => 'Descubre la colección de pijamas elegantes y suaves de Moraia. Estilo, confort y feminidad en cada noche.',
            ],
            [
                'name' => 'Lencería',
                'slug' => 'lenceria',
                'description' => 'Piezas delicadas de encaje y microfibra que celebran la silueta femenina con sutileza y sofisticación.',
                'image_path' => 'images/categories/cat_lenceria.jpg',
                'sort_order' => 2,
                'is_active' => true,
                'is_featured' => true,
                'seo_title' => 'Lencería Fina y Delicada | Moraia Íntimo',
                'seo_description' => 'Lencería femenina sofisticada y cómoda. Diseños de encaje premium pensados para consentirte.',
            ],
            [
                'name' => 'Belleza & Skincare',
                'slug' => 'belleza',
                'description' => 'Fórmulas limpias, hidratantes y esenciales para realzar tu belleza natural en tu rutina diaria.',
                'image_path' => 'images/categories/cat_belleza.jpg',
                'sort_order' => 3,
                'is_active' => true,
                'is_featured' => true,
                'seo_title' => 'Productos de Belleza y Cuidado Facial | Moraia',
                'seo_description' => 'Cosmética y cuidado facial curado para mujeres que aman consentir su piel.',
            ],
            [
                'name' => 'Cuidado Personal',
                'slug' => 'cuidado-personal',
                'description' => 'Brumas aromáticas, exfoliantes y aceites corporales para convertir tu baño en un spa.',
                'image_path' => 'images/categories/cat_cuidado.jpg',
                'sort_order' => 4,
                'is_active' => true,
                'is_featured' => true,
                'seo_title' => 'Cuidado Personal y Aromaterapia | Moraia',
                'seo_description' => 'Cremas, brumas y aceites aromáticos para una experiencia sensorial única.',
            ],
            [
                'name' => 'Bienestar Íntimo',
                'slug' => 'bienestar-intimo',
                'description' => 'Curaduría exclusiva y discreta para el autodescubrimiento, placer y relajación.',
                'image_path' => 'images/categories/cat_bienestar.jpg',
                'sort_order' => 5,
                'is_active' => true,
                'is_featured' => true,
                'seo_title' => 'Bienestar Íntimo Femenino | Moraia Íntimo',
                'seo_description' => 'Productos seleccionados con la máxima discreción y elegancia para tu bienestar.',
            ],
            [
                'name' => 'Cajas de Regalo & Sets',
                'slug' => 'regalos',
                'description' => 'Combinaciones prediseñadas en empaque de lujo Moraia con lazo y tarjeta para regalar momentos inolvidables.',
                'image_path' => 'images/categories/cat_regalos.jpg',
                'sort_order' => 6,
                'is_active' => true,
                'is_featured' => true,
                'seo_title' => 'Cajas de Regalo y Experiencias para Mujer | Moraia',
                'seo_description' => 'El regalo perfecto para ella. Cajas curadas con pijamas, cuidado y detalles inolvidables.',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cData) {
            $categories[$cData['slug']] = Category::updateOrCreate(
                ['slug' => $cData['slug']],
                $cData
            );
        }

        // 4. Products Data
        $productsData = [
            // Pijamas
            [
                'category_slug' => 'pijamas',
                'name' => 'Set Pijama Satén Francés "Aura Rose"',
                'sku' => 'PIJ-AUR-01',
                'short_description' => 'Set de dos piezas en satén ultra sedoso con ribetes en contraste y tacto piel de ángel.',
                'description' => "Eleva tu descanso nocturno con nuestro set emblemático Aura Rose. Confeccionado en un satén ligero de caída fluida que no se arruga fácilmente y acaricia suavemente tu piel.\n\n• Incluye camisa abotonada de manga corta y pantalón corto con pretina elástica confortable.\n• Detalles de vivos en tono rosa empolvado característico de Moraia.\n• Ideal para regalar o para tu propio ritual nocturno.",
                'price' => 38.00,
                'compare_at_price' => 45.00,
                'stock_quantity' => 15,
                'is_active' => true,
                'is_featured' => true,
                'badge' => 'Best Seller',
                'target_audience' => 'all',
                'images' => [
                    'images/products/pijama-aura-1.jpg',
                    'images/products/pijama-aura-2.jpg',
                ],
                'variants' => [
                    ['type' => 'talla', 'name' => 'Talla S', 'value' => 'S', 'price_modifier' => 0.00],
                    ['type' => 'talla', 'name' => 'Talla M', 'value' => 'M', 'price_modifier' => 0.00],
                    ['type' => 'talla', 'name' => 'Talla L', 'value' => 'L', 'price_modifier' => 0.00],
                    ['type' => 'color', 'name' => 'Rosa Moraia', 'value' => '#D87F86', 'price_modifier' => 0.00],
                    ['type' => 'color', 'name' => 'Marfil Lino', 'value' => '#FCF7F4', 'price_modifier' => 0.00],
                ],
            ],
            [
                'category_slug' => 'pijamas',
                'name' => 'Bata Kimono Seda "Velvet Whisper"',
                'sku' => 'BAT-VEL-02',
                'short_description' => 'Kimono envolvente con cinturón satinado y detalles de encaje en puños.',
                'description' => 'Una prenda sofisticada y versátil para tus mañanas o noches de relax. Caída fluida con acabados de alta costura que complementan cualquier pijama o conjunto de lencería.',
                'price' => 42.00,
                'compare_at_price' => 50.00,
                'stock_quantity' => 12,
                'is_active' => true,
                'is_featured' => true,
                'badge' => 'Exclusivo',
                'target_audience' => 'all',
                'images' => [
                    'images/products/bata-kimono-1.jpg',
                ],
                'variants' => [
                    ['type' => 'talla', 'name' => 'Talla Única (S-L)', 'value' => 'Única', 'price_modifier' => 0.00],
                    ['type' => 'color', 'name' => 'Negro Onyx', 'value' => '#2A2626', 'price_modifier' => 0.00],
                    ['type' => 'color', 'name' => 'Rosa Moraia', 'value' => '#D87F86', 'price_modifier' => 0.00],
                ],
            ],
            // Lencería
            [
                'category_slug' => 'lenceria',
                'name' => 'Bralette Encaje Floral "Seduction Chantilly"',
                'sku' => 'LEN-SED-01',
                'short_description' => 'Bralette sin aros en encaje chantilly con tirantes dobles regulables.',
                'description' => 'Diseñado para brindar soporte natural sin sacrificar la sensualidad. Encaje suave de motivos botánicos que no pica y se funde con el contorno femenino.',
                'price' => 24.00,
                'compare_at_price' => 30.00,
                'stock_quantity' => 20,
                'is_active' => true,
                'is_featured' => true,
                'badge' => 'Favorito',
                'target_audience' => 'moraia_intimo',
                'images' => [
                    'images/products/bralette-chantilly-1.jpg',
                ],
                'variants' => [
                    ['type' => 'talla', 'name' => 'Talla 32B', 'value' => '32B', 'price_modifier' => 0.00],
                    ['type' => 'talla', 'name' => 'Talla 34B', 'value' => '34B', 'price_modifier' => 0.00],
                    ['type' => 'talla', 'name' => 'Talla 36B', 'value' => '36B', 'price_modifier' => 0.00],
                    ['type' => 'color', 'name' => 'Rosa Mauve', 'value' => '#D87F86', 'price_modifier' => 0.00],
                    ['type' => 'color', 'name' => 'Negro Noche', 'value' => '#1F1B1B', 'price_modifier' => 0.00],
                ],
            ],
            [
                'category_slug' => 'lenceria',
                'name' => 'Body Escote Profundo "Moonlight Lace"',
                'sku' => 'LEN-MOO-02',
                'short_description' => 'Body de encaje elástico con espalda descubierta y cierre inferior de corchetes.',
                'description' => 'La pieza perfecta para llevar como prenda exterior con un blazer o para una velada íntima inolvidable. Realce armónico y elegancia pura.',
                'price' => 32.00,
                'compare_at_price' => 40.00,
                'stock_quantity' => 10,
                'is_active' => true,
                'is_featured' => false,
                'badge' => 'Tendencia',
                'target_audience' => 'moraia_intimo',
                'images' => [
                    'images/products/body-moonlight-1.jpg',
                ],
                'variants' => [
                    ['type' => 'talla', 'name' => 'Talla S', 'value' => 'S', 'price_modifier' => 0.00],
                    ['type' => 'talla', 'name' => 'Talla M', 'value' => 'M', 'price_modifier' => 0.00],
                    ['type' => 'talla', 'name' => 'Talla L', 'value' => 'L', 'price_modifier' => 0.00],
                ],
            ],
            // Belleza
            [
                'category_slug' => 'belleza',
                'name' => 'Serum Facial Iluminador "Golden Glow Drops"',
                'sku' => 'BEL-GLO-01',
                'short_description' => 'Serum botánico con Niacinamida, Vitamina C y microperlas reflectantes de luz.',
                'description' => 'Un velo de hidratación y luminosidad instantánea. Textura ligera de rápida absorción que prepara tu piel para el día y unifica el tono.',
                'price' => 22.00,
                'compare_at_price' => 28.00,
                'stock_quantity' => 25,
                'is_active' => true,
                'is_featured' => true,
                'badge' => 'Glow Effect',
                'target_audience' => 'all',
                'images' => [
                    'images/products/serum-glow-1.jpg',
                ],
                'variants' => [
                    ['type' => 'modelo', 'name' => 'Frasco Gotero 30ml', 'value' => '30ml', 'price_modifier' => 0.00],
                ],
            ],
            [
                'category_slug' => 'belleza',
                'name' => 'Aceite Labial Hidratante "Silk Kiss"',
                'sku' => 'BEL-LIP-02',
                'short_description' => 'Lip oil con infusión de aceite de jojoba y aroma a frambuesa silvestre.',
                'description' => 'Labios nutridos, jugosos y con un brillo tipo espejo no pegajoso. Aporta un suave tinte rosado que realza tu color natural.',
                'price' => 12.00,
                'compare_at_price' => 15.00,
                'stock_quantity' => 30,
                'is_active' => true,
                'is_featured' => true,
                'badge' => 'Must Have',
                'target_audience' => 'all',
                'images' => [
                    'images/products/lip-oil-1.jpg',
                ],
                'variants' => [
                    ['type' => 'aroma', 'name' => 'Rosa Silvestre', 'value' => 'Rosa', 'price_modifier' => 0.00],
                    ['type' => 'aroma', 'name' => 'Vainilla Dulce', 'value' => 'Vainilla', 'price_modifier' => 0.00],
                ],
            ],
            // Cuidado Personal
            [
                'category_slug' => 'cuidado-personal',
                'name' => 'Bruma Corporal & Almohada "Calm Petals"',
                'sku' => 'CUI-BRU-01',
                'short_description' => 'Mist aromático relajante con extractos de lavanda francesa, jazmín y peonía.',
                'description' => 'Rocía sobre tu piel después del baño o sobre tus sábanas antes de dormir para inducir un estado de calma profunda y bienestar.',
                'price' => 18.00,
                'compare_at_price' => 22.00,
                'stock_quantity' => 20,
                'is_active' => true,
                'is_featured' => true,
                'badge' => 'Aromaterapia',
                'target_audience' => 'all',
                'images' => [
                    'images/products/mist-calm-1.jpg',
                ],
                'variants' => [
                    ['type' => 'modelo', 'name' => 'Spray 120ml', 'value' => '120ml', 'price_modifier' => 0.00],
                ],
            ],
            // Bienestar Íntimo
            [
                'category_slug' => 'bienestar-intimo',
                'name' => 'Vibrador Ergonómico "Velvet Blossom"',
                'sku' => 'INT-VEL-01',
                'short_description' => 'Dispositivo de silicona médica ultra sedosa, resistente al agua y silencioso.',
                'description' => 'Diseño ergonómico y elegante pensado para el disfrute personal femenino con 10 patrones de vibración, recargable por USB magnético.',
                'price' => 45.00,
                'compare_at_price' => 55.00,
                'stock_quantity' => 12,
                'is_active' => true,
                'is_featured' => true,
                'badge' => 'Exclusivo',
                'target_audience' => 'moraia_intimo',
                'images' => [
                    'images/products/intimo-velvet-1.jpg',
                ],
                'variants' => [
                    ['type' => 'color', 'name' => 'Rosa Mauve', 'value' => '#D87F86', 'price_modifier' => 0.00],
                ],
            ],
            [
                'category_slug' => 'bienestar-intimo',
                'name' => 'Lubricante Sensual Base Agua "Pure Touch"',
                'sku' => 'INT-LUB-02',
                'short_description' => 'Fórmula con ácido hialurónico y aloe vera, pH balanceado y textura sedosa.',
                'description' => 'Suavidad duradera que respeta tu flora natural. Libre de parabenos, no mancha y se limpia fácilmente con agua.',
                'price' => 16.00,
                'compare_at_price' => 20.00,
                'stock_quantity' => 25,
                'is_active' => true,
                'is_featured' => false,
                'badge' => 'Natural',
                'target_audience' => 'moraia_intimo',
                'images' => [
                    'images/products/lubricante-pure-1.jpg',
                ],
                'variants' => [
                    ['type' => 'modelo', 'name' => 'Frasco Dosificador 100ml', 'value' => '100ml', 'price_modifier' => 0.00],
                ],
            ],
            // Regalos & Sets
            [
                'category_slug' => 'regalos',
                'name' => 'Box "Moraia Signature Experience"',
                'sku' => 'REG-SIG-01',
                'short_description' => 'Set de regalo definitivo con Pijama Aura Rose, Bruma Calm Petals, Lip Oil y Tarjeta personalizada.',
                'description' => 'Nuestra caja más codiciada. Incluye el Set Pijama Satén Aura Rose (talla a elección), la Bruma Corporal Calm Petals, el Aceite Labial Silk Kiss y presentación en caja rígida premium con moño de satén y tarjeta con mensaje personalizado.',
                'price' => 65.00,
                'compare_at_price' => 78.00,
                'stock_quantity' => 10,
                'is_active' => true,
                'is_featured' => true,
                'badge' => 'Regalo Estrella',
                'target_audience' => 'all',
                'images' => [
                    'images/products/box-signature-1.jpg',
                ],
                'variants' => [
                    ['type' => 'talla', 'name' => 'Pijama Talla S', 'value' => 'S', 'price_modifier' => 0.00],
                    ['type' => 'talla', 'name' => 'Pijama Talla M', 'value' => 'M', 'price_modifier' => 0.00],
                    ['type' => 'talla', 'name' => 'Pijama Talla L', 'value' => 'L', 'price_modifier' => 0.00],
                ],
            ],
            [
                'category_slug' => 'regalos',
                'name' => 'Mini Box "Dulce Consentirte"',
                'sku' => 'REG-MIN-02',
                'short_description' => 'Detalle ideal para sorprender: Lip Oil Silk Kiss + Bralette Chantilly en cajita de regalo.',
                'description' => 'Un detalle delicado y encantador para consentir a una amiga o a ti misma. Incluye empaque de regalo y tarjeta caligrafiada.',
                'price' => 34.00,
                'compare_at_price' => 40.00,
                'stock_quantity' => 15,
                'is_active' => true,
                'is_featured' => true,
                'badge' => 'Ideal Regalo',
                'target_audience' => 'all',
                'images' => [
                    'images/products/box-dulce-1.jpg',
                ],
                'variants' => [
                    ['type' => 'talla', 'name' => 'Bralette 32B', 'value' => '32B', 'price_modifier' => 0.00],
                    ['type' => 'talla', 'name' => 'Bralette 34B', 'value' => '34B', 'price_modifier' => 0.00],
                    ['type' => 'talla', 'name' => 'Bralette 36B', 'value' => '36B', 'price_modifier' => 0.00],
                ],
            ],
        ];

        foreach ($productsData as $pData) {
            $cat = $categories[$pData['category_slug']] ?? null;
            $slug = Str::slug($pData['name']);

            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $cat?->id,
                    'name' => $pData['name'],
                    'sku' => $pData['sku'],
                    'short_description' => $pData['short_description'],
                    'description' => $pData['description'],
                    'price' => $pData['price'],
                    'compare_at_price' => $pData['compare_at_price'],
                    'stock_quantity' => $pData['stock_quantity'],
                    'is_active' => $pData['is_active'],
                    'is_featured' => $pData['is_featured'],
                    'badge' => $pData['badge'],
                    'target_audience' => $pData['target_audience'],
                    'seo_title' => "{$pData['name']} | Moraia",
                    'seo_description' => $pData['short_description'],
                ]
            );

            // Images
            foreach ($pData['images'] as $iIdx => $img) {
                ProductImage::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'image_path' => $img,
                    ],
                    [
                        'alt_text' => $product->name,
                        'sort_order' => $iIdx,
                        'is_cover' => $iIdx === 0,
                    ]
                );
            }

            // Variants
            if (! empty($pData['variants'])) {
                foreach ($pData['variants'] as $v) {
                    ProductVariant::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'name' => $v['name'],
                            'variant_type' => $v['type'],
                        ],
                        [
                            'value' => $v['value'],
                            'price_modifier' => $v['price_modifier'],
                            'stock_quantity' => 15,
                            'is_active' => true,
                        ]
                    );
                }
            }
        }

        // 5. Packagings (Empaques & Presentaciones)
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
