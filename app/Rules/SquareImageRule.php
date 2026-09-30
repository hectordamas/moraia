<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;
use Illuminate\Translation\PotentiallyTranslatedString;

class SquareImageRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile || ! $value->isValid()) {
            return;
        }

        $imageSize = @getimagesize($value->getRealPath());
        if (! $imageSize) {
            $fail('El archivo subido no es una imagen válida.');

            return;
        }

        $width = $imageSize[0];
        $height = $imageSize[1];

        if ($height === 0 || $width === 0) {
            $fail('La imagen tiene dimensiones no válidas.');

            return;
        }

        $ratio = $width / $height;

        // Validar que la proporción sea 1:1 o muy cercana (tolerancia de 10% entre 0.90 y 1.10)
        if ($ratio < 0.90 || $ratio > 1.10) {
            $fail('La imagen debe tener una relación de aspecto cuadrada 1:1 o muy cercana (recomendado 500x500 px). Las dimensiones actuales son '.$width.'x'.$height.' px.');
        }
    }
}
