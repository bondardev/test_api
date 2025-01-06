<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Tinify\Tinify;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageProcessingService
{
    private function ensureDirectoryExists($directory)
    {
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
    }
    
    public function process($image, $outputDir = 'uploads')
    {
        $this->ensureDirectoryExists($outputDir);

        $croppedImagePath = $this->cropAndConvertToJpg($image, $outputDir);

        return $this->optimizeWithTinify($croppedImagePath, $outputDir);
    }

    private function cropAndConvertToJpg($image, $outputDir)
    {
        $tempFileName = uniqid('cropped_') . '.jpg';
        $tempPath = "{$outputDir}/{$tempFileName}";

        Storage::disk('public')->put($tempPath, (string) Image::make($image)
            ->fit(70, 70, function ($constraint) {
                $constraint->upsize();
            })
            ->encode('jpg', 90));

        return $tempPath;
    }

    private function optimizeWithTinify($imagePath, $outputDir)
    {
        try {
            Tinify::setKey(env('TINIFY_API_KEY'));

            $optimizedFileName = uniqid('optimized_') . '.jpg';
            $optimizedImagePath = "{$outputDir}/{$optimizedFileName}";

            $source = \Tinify\fromFile(Storage::disk('public')->path($imagePath));
            $source->toFile(Storage::disk('public')->path($optimizedImagePath));

            return Storage::disk('public')->url($optimizedImagePath);
        } catch (\Tinify\Exception $e) {
            Log::error('Tinify optimization failed: ' . $e->getMessage());
            return null;
        }
    }
}
