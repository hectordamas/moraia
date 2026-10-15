<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ProductImageService
{
    /**
     * Target resolution for the square 1:1 cropped e-commerce display image.
     */
    public const CROPPED_SIZE = 800;

    /**
     * Process and store a newly uploaded image:
     * 1. Preserves the full original image untouched in uploads/products/originals
     * 2. Generates an optimal 1:1 square cropped image for the frontend in uploads/products
     *
     * @param  array{x?: float|int, y?: float|int, width?: float|int, height?: float|int}|null  $cropData
     */
    public function storeImage(Product $product, UploadedFile $file, ?array $cropData = null, int $sortOrder = 0, bool $isCover = false): ProductImage
    {
        $productsDir = public_path('uploads/products');
        $originalsDir = public_path('uploads/products/originals');

        if (! file_exists($productsDir)) {
            mkdir($productsDir, 0755, true);
        }
        if (! file_exists($originalsDir)) {
            mkdir($originalsDir, 0755, true);
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            $extension = 'jpg';
        }

        $uniqueHash = Str::random(12);
        $originalFileName = 'orig_prod_'.$product->id.'_'.$uniqueHash.'.'.$extension;
        $croppedFileName = 'prod_'.$product->id.'_'.$uniqueHash.'_1x1.'.$extension;

        // 1. Move and preserve the untouched original file
        $file->move($originalsDir, $originalFileName);
        $originalFullPath = $originalsDir.DIRECTORY_SEPARATOR.$originalFileName;
        $originalRelPath = 'uploads/products/originals/'.$originalFileName;

        // 2. Generate the 1:1 cropped version
        $croppedRelPath = 'uploads/products/'.$croppedFileName;
        $croppedFullPath = $productsDir.DIRECTORY_SEPARATOR.$croppedFileName;

        $appliedCropData = $this->createSquareCrop($originalFullPath, $croppedFullPath, $cropData, $extension);

        return ProductImage::create([
            'product_id' => $product->id,
            'image_path' => $croppedRelPath,
            'original_path' => $originalRelPath,
            'crop_data' => $appliedCropData,
            'alt_text' => $product->name,
            'sort_order' => $sortOrder,
            'is_cover' => $isCover,
        ]);
    }

    /**
     * Re-crop an existing product image using new crop coordinates without re-uploading.
     *
     * @param  array{x?: float|int, y?: float|int, width?: float|int, height?: float|int}  $cropData
     */
    public function updateCrop(ProductImage $productImage, array $cropData): bool
    {
        // Use original if available, otherwise fallback to current image_path
        $sourceRelPath = $productImage->original_path ?: $productImage->image_path;
        $sourceFullPath = public_path($sourceRelPath);

        if (! file_exists($sourceFullPath)) {
            return false;
        }

        $extension = strtolower(pathinfo($sourceFullPath, PATHINFO_EXTENSION) ?: 'jpg');
        $uniqueHash = Str::random(12);
        $croppedFileName = 'prod_'.$productImage->product_id.'_'.$uniqueHash.'_recrop.'.$extension;
        $newCroppedRelPath = 'uploads/products/'.$croppedFileName;
        $newCroppedFullPath = public_path($newCroppedRelPath);

        $appliedCropData = $this->createSquareCrop($sourceFullPath, $newCroppedFullPath, $cropData, $extension);

        // Delete old cropped file if different from original
        if ($productImage->image_path && $productImage->image_path !== $productImage->original_path) {
            $oldPath = public_path($productImage->image_path);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $productImage->update([
            'image_path' => $newCroppedRelPath,
            'crop_data' => $appliedCropData,
        ]);

        return true;
    }

    /**
     * Delete image files from disk.
     */
    public function deleteImageFiles(ProductImage $image): void
    {
        if ($image->image_path && str_starts_with($image->image_path, 'uploads/')) {
            $path = public_path($image->image_path);
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        if ($image->original_path && str_starts_with($image->original_path, 'uploads/')) {
            $path = public_path($image->original_path);
            if (file_exists($path)) {
                @unlink($path);
            }
        }
    }

    /**
     * Creates a high quality 1:1 cropped image using GD.
     *
     * @param  array{x?: float|int, y?: float|int, width?: float|int, height?: float|int}|null  $cropData
     * @return array{x: int, y: int, width: int, height: int}
     */
    protected function createSquareCrop(string $sourcePath, string $destPath, ?array $cropData, string $extension): array
    {
        $rawContent = file_get_contents($sourcePath);
        if ($rawContent === false) {
            throw new \RuntimeException("No se pudo leer la imagen de origen: {$sourcePath}");
        }

        $srcImage = @imagecreatefromstring($rawContent);
        if (! $srcImage) {
            // Fallback if GD fails to decode
            copy($sourcePath, $destPath);

            return ['x' => 0, 'y' => 0, 'width' => self::CROPPED_SIZE, 'height' => self::CROPPED_SIZE];
        }

        // Correct EXIF orientation for JPEG photos if needed
        if (function_exists('exif_read_data') && in_array($extension, ['jpg', 'jpeg'])) {
            try {
                $exif = @exif_read_data($sourcePath);
                if (! empty($exif['Orientation'])) {
                    $srcImage = match ((int) $exif['Orientation']) {
                        3 => imagerotate($srcImage, 180, 0),
                        6 => imagerotate($srcImage, -90, 0),
                        8 => imagerotate($srcImage, 90, 0),
                        default => $srcImage,
                    };
                }
            } catch (\Throwable $e) {
                // Ignore EXIF read errors
            }
        }

        $srcWidth = imagesx($srcImage);
        $srcHeight = imagesy($srcImage);

        // Calculate crop rectangle coordinates
        if ($cropData && isset($cropData['width'], $cropData['height']) && $cropData['width'] > 0 && $cropData['height'] > 0) {
            $cropX = (int) round($cropData['x'] ?? 0);
            $cropY = (int) round($cropData['y'] ?? 0);
            $cropW = (int) round($cropData['width']);
            $cropH = (int) round($cropData['height']);

            // Clamp crop box within source image boundaries
            $cropX = max(0, min($cropX, $srcWidth - 1));
            $cropY = max(0, min($cropY, $srcHeight - 1));
            $cropW = max(1, min($cropW, $srcWidth - $cropX));
            $cropH = max(1, min($cropH, $srcHeight - $cropY));

            // Force 1:1 aspect ratio on crop area if slightly off
            $squareDim = min($cropW, $cropH);
            $cropW = $squareDim;
            $cropH = $squareDim;
        } else {
            // Default center 1:1 square crop
            $squareDim = min($srcWidth, $srcHeight);
            $cropX = (int) floor(($srcWidth - $squareDim) / 2);
            $cropY = (int) floor(($srcHeight - $squareDim) / 2);
            $cropW = $squareDim;
            $cropH = $squareDim;
        }

        $targetSize = self::CROPPED_SIZE;
        $dstImage = imagecreatetruecolor($targetSize, $targetSize);

        if (in_array($extension, ['png', 'webp'])) {
            imagealphablending($dstImage, false);
            imagesavealpha($dstImage, true);
            $transparent = imagecolorallocatealpha($dstImage, 255, 255, 255, 127);
            imagefilledrectangle($dstImage, 0, 0, $targetSize, $targetSize, $transparent);
        } else {
            // White background default for JPEG
            $white = imagecolorallocate($dstImage, 255, 255, 255);
            imagefilledrectangle($dstImage, 0, 0, $targetSize, $targetSize, $white);
        }

        imagecopyresampled(
            $dstImage,
            $srcImage,
            0,
            0,
            $cropX,
            $cropY,
            $targetSize,
            $targetSize,
            $cropW,
            $cropH
        );

        // Output destination image
        match ($extension) {
            'png' => imagepng($dstImage, $destPath, 8),
            'webp' => function_exists('imagewebp') ? imagewebp($dstImage, $destPath, 90) : imagejpeg($dstImage, $destPath, 92),
            default => imagejpeg($dstImage, $destPath, 92),
        };

        imagedestroy($srcImage);
        imagedestroy($dstImage);

        return [
            'x' => $cropX,
            'y' => $cropY,
            'width' => $cropW,
            'height' => $cropH,
        ];
    }
}
