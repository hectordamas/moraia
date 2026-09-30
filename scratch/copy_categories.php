<?php

$files = [
    'cat_pijamas.jpg' => 'C:/Users/hecto/.gemini/antigravity-ide/brain/c2ebe89d-41dd-41d0-a788-063ec3d0727b/moraia_cat_pijamas_1789940380529.jpg',
    'cat_lenceria.jpg' => 'C:/Users/hecto/.gemini/antigravity-ide/brain/c2ebe89d-41dd-41d0-a788-063ec3d0727b/moraia_cat_lenceria_1789940394779.jpg',
    'cat_belleza.jpg' => 'C:/Users/hecto/.gemini/antigravity-ide/brain/c2ebe89d-41dd-41d0-a788-063ec3d0727b/moraia_cat_belleza_1789940410399.jpg',
    'cat_cuidado.jpg' => 'C:/Users/hecto/.gemini/antigravity-ide/brain/c2ebe89d-41dd-41d0-a788-063ec3d0727b/moraia_cat_cuidado_1789940425518.jpg',
    'cat_bienestar.jpg' => 'C:/Users/hecto/.gemini/antigravity-ide/brain/c2ebe89d-41dd-41d0-a788-063ec3d0727b/moraia_cat_bienestar_1789940443976.jpg',
    'cat_intimo.jpg' => 'C:/Users/hecto/.gemini/antigravity-ide/brain/c2ebe89d-41dd-41d0-a788-063ec3d0727b/moraia_cat_bienestar_1789940443976.jpg',
    'cat_regalos.jpg' => 'C:/Users/hecto/.gemini/antigravity-ide/brain/c2ebe89d-41dd-41d0-a788-063ec3d0727b/moraia_cat_regalos_1789940462488.jpg',
];

$destDir = __DIR__.'/../public/images/categories';
if (! is_dir($destDir)) {
    mkdir($destDir, 0755, true);
}

foreach ($files as $destName => $srcPath) {
    copy($srcPath, $destDir.'/'.$destName);
    echo "Copied {$destName}: ".filesize($destDir.'/'.$destName)." bytes\n";
}
