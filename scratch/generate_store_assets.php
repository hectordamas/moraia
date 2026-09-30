<?php

function saveBmp24($filename, $width, $height, $rgbData)
{
    $rowSize = ($width * 3 + 3) & ~3;
    $padding = $rowSize - ($width * 3);
    $dataSize = $rowSize * $height;
    $fileSize = 54 + $dataSize;

    $header = 'BM'.
        pack('V', $fileSize).
        pack('v', 0).pack('v', 0).
        pack('V', 54).
        pack('V', 40).
        pack('V', $width).
        pack('V', $height).
        pack('v', 1).
        pack('v', 24).
        pack('V', 0).
        pack('V', $dataSize).
        pack('V', 2835).pack('V', 2835).
        pack('V', 0).pack('V', 0);

    $body = '';
    for ($y = $height - 1; $y >= 0; $y--) {
        $row = '';
        for ($x = 0; $x < $width; $x++) {
            $pos = ($y * $width + $x) * 3;
            $r = $rgbData[$pos] ?? "\x00";
            $g = $rgbData[$pos + 1] ?? "\x00";
            $b = $rgbData[$pos + 2] ?? "\x00";
            $row .= $b.$g.$r;
        }
        $row .= str_repeat("\x00", $padding);
        $body .= $row;
    }

    file_put_contents($filename, $header.$body);
}

function createEditorialImage($filename, $width, $height, $bgColorHex, $accentColorHex, $title, $subtitle, $categoryTag)
{
    // Convert hex to RGB
    [$r1, $g1, $b1] = sscanf($bgColorHex, '#%02x%02x%02x');
    [$r2, $g2, $b2] = sscanf($accentColorHex, '#%02x%02x%02x');

    $rgbData = '';
    for ($y = 0; $y < $height; $y++) {
        $factor = ($y / $height) * 0.15; // subtle vertical gradient
        $r = (int) max(0, min(255, $r1 * (1 - $factor) + 255 * $factor * 0.2));
        $g = (int) max(0, min(255, $g1 * (1 - $factor) + 255 * $factor * 0.2));
        $b = (int) max(0, min(255, $b1 * (1 - $factor) + 255 * $factor * 0.2));

        // Add delicate border
        $isBorder = ($y < 8 || $y >= $height - 8);

        for ($x = 0; $x < $width; $x++) {
            $isInnerBorder = $isBorder || ($x < 8 || $x >= $width - 8);
            if ($isInnerBorder) {
                $rgbData .= chr($r2).chr($g2).chr($b2);
            } else {
                $rgbData .= chr($r).chr($g).chr($b);
            }
        }
    }

    $bmpPath = sys_get_temp_dir().'/'.uniqid('img_').'.bmp';
    saveBmp24($bmpPath, $width, $height, $rgbData);

    // Ensure dir exists
    $dir = dirname($filename);
    if (! is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    // Convert via PowerShell System.Drawing to JPEG with typography / branding overlay if desired, or save as JPG
    $psScript = 'Add-Type -AssemblyName System.Drawing; '.
        "\$bmp = [System.Drawing.Bitmap]::FromFile('$bmpPath'); ".
        '$g = [System.Drawing.Graphics]::FromImage($bmp); '.
        '$g.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::AntiAlias; '.
        '$g.TextRenderingHint = [System.Drawing.Text.TextRenderingHint]::ClearTypeGridFit; '.
        "\$fontTitle = New-Object System.Drawing.Font('Georgia', ".($width > 1000 ? 44 : 26).', [System.Drawing.FontStyle]::Regular); '.
        "\$fontSub = New-Object System.Drawing.Font('Arial', ".($width > 1000 ? 18 : 12).', [System.Drawing.FontStyle]::Regular); '.
        "\$fontTag = New-Object System.Drawing.Font('Arial', ".($width > 1000 ? 14 : 10).', [System.Drawing.FontStyle]::Bold); '.
        '$brushText = New-Object System.Drawing.SolidBrush([System.Drawing.Color]::FromArgb(50, 42, 42)); '.
        '$brushRose = New-Object System.Drawing.SolidBrush([System.Drawing.Color]::FromArgb(216, 127, 134)); '.
        '$brushWhite = New-Object System.Drawing.SolidBrush([System.Drawing.Color]::White); '.
        "\$rect = New-Object System.Drawing.RectangleF(40, ($height * 0.45), ($width - 80), ($height * 0.45)); ".
        '$format = New-Object System.Drawing.StringFormat; '.
        '$format.Alignment = [System.Drawing.StringAlignment]::Center; '.
        '$format.LineAlignment = [System.Drawing.StringAlignment]::Center; '.
        // Draw decorative bow / gift box icon center top
        "\$g.FillEllipse(\$brushRose, ($width / 2 - 25), ($height * 0.25), 50, 50); ".
        "\$g.DrawString('M', \$fontTitle, \$brushWhite, [float]($width / 2 - 18), [float]($height * 0.25 + 4)); ".
        // Draw Tag
        "\$g.DrawString('$categoryTag'.ToUpper(), \$fontTag, \$brushRose, [float]($width / 2), [float]($height * 0.38), \$format); ".
        // Draw Title
        "\$g.DrawString('$title', \$fontTitle, \$brushText, \$rect, \$format); ".
        // Draw Subtitle
        "\$g.DrawString('$subtitle', \$fontSub, \$brushText, [float]($width / 2), [float]($height * 0.85), \$format); ".
        "\$bmp.Save('$filename', [System.Drawing.Imaging.ImageFormat]::Jpeg); ".
        "\$g.Dispose(); \$bmp.Dispose(); Remove-Item '$bmpPath';";

    file_put_contents('scratch/run_render.ps1', $psScript);
    shell_exec('powershell -ExecutionPolicy Bypass -File scratch/run_render.ps1');
    echo "Generated: $filename\n";
}

$base = 'd:/Proyectos en Curso/moraia/public/';

// Products only
$products = [
    ['images/products/pijama-aura-1.jpg', 'Set Pijama Aura Rose', 'Satén ultra suave en rosa empolvado', 'PIJAMAS'],
    ['images/products/pijama-aura-2.jpg', 'Set Pijama Aura Rose (Detalle)', 'Ribetes y confección de alta gama', 'PIJAMAS'],
    ['images/products/bata-kimono-1.jpg', 'Bata Kimono Velvet Whisper', 'Caída fluida y tacto seda', 'PIJAMAS'],
    ['images/products/bralette-chantilly-1.jpg', 'Bralette Seduction Chantilly', 'Encaje botánico sin aros', 'LENCERÍA'],
    ['images/products/body-moonlight-1.jpg', 'Body Moonlight Lace', 'Escote refinado y espalda descubierta', 'LENCERÍA'],
    ['images/products/serum-glow-1.jpg', 'Serum Golden Glow Drops', 'Luminosidad e hidratación botánica', 'BELLEZA'],
    ['images/products/lip-oil-1.jpg', 'Lip Oil Silk Kiss', 'Aceite labial frambuesa no pegajoso', 'BELLEZA'],
    ['images/products/mist-calm-1.jpg', 'Bruma Calm Petals', 'Lavanda francesa y peonías', 'CUIDADO'],
    ['images/products/intimo-velvet-1.jpg', 'Vibrador Velvet Blossom', 'Silicona médica y diseño ergonómico', 'BIENESTAR'],
    ['images/products/lubricante-pure-1.jpg', 'Lubricante Pure Touch', 'Base de agua y ácido hialurónico', 'BIENESTAR'],
    ['images/products/box-signature-1.jpg', 'Box Moraia Signature Experience', 'Set pijama, bruma, lip oil y tarjeta', 'REGALOS'],
    ['images/products/box-dulce-1.jpg', 'Mini Box Dulce Consentirte', 'Bralette y lip oil en caja de regalo', 'REGALOS'],
    ['images/placeholder-product.jpg', 'Moraia Luxury Item', 'El arte de consentirte', 'MORAIA'],
];

foreach ($products as $p) {
    createEditorialImage($base.$p[0], 600, 600, '#FAF6F4', '#D87F86', $p[1], $p[2], $p[3]);
}

echo "All store images created successfully!\n";
