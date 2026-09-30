<?php

$src1 = 'C:/Users/hecto/.gemini/antigravity-ide/brain/c2ebe89d-41dd-41d0-a788-063ec3d0727b/moraia_hero_slide1_1789940182030.jpg';
$src2 = 'C:/Users/hecto/.gemini/antigravity-ide/brain/c2ebe89d-41dd-41d0-a788-063ec3d0727b/moraia_hero_slide2_1789940197726.jpg';
$src3 = 'C:/Users/hecto/.gemini/antigravity-ide/brain/c2ebe89d-41dd-41d0-a788-063ec3d0727b/moraia_hero_slide3_1789940211868.jpg';

$destDir = __DIR__.'/../public/images/hero';
if (! is_dir($destDir)) {
    mkdir($destDir, 0755, true);
}

copy($src1, $destDir.'/hero-slide-1.jpg');
copy($src2, $destDir.'/hero-slide-2.jpg');
copy($src3, $destDir.'/hero-slide-3.jpg');

echo "COPIED SUCCESSFULLY!\n";
echo 'Slide 1: '.filesize($destDir.'/hero-slide-1.jpg')." bytes\n";
echo 'Slide 2: '.filesize($destDir.'/hero-slide-2.jpg')." bytes\n";
echo 'Slide 3: '.filesize($destDir.'/hero-slide-3.jpg')." bytes\n";
