<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;
use Imagick;

class ThumbnailService
{
    const MAX_SIDE_LENGTH = 150;

    /**
     * Generate Thumbnail using Imagick class
     *  
     * @param string $image
     * @param string $storageType
     * @param int $quality
     *
     * @return boolean on true
     * @throws Exception
     * @throws ImagickException
     */
    public function generateThumbnail(string $image, string $storageType = 'gcs', int $quality = 90)
    {
        if (is_file($image)) {
            $imagick = new Imagick(realpath($image));
            $imageWidth = $imagick->getImageWidth();
            $imageHeight = $imagick->getImageHeight();
            $currentTimestamp = Carbon::now()->format('Y_m_d_His');

            if ($imageHeight > $imageWidth) {   
                $ratio = self::MAX_SIDE_LENGTH / $imageHeight;  
                $newHeight = self::MAX_SIDE_LENGTH;
                $newWidth = $imageWidth * $ratio; 
            } else {
                $ratio = self::MAX_SIDE_LENGTH / $imageWidth;   
                $newWidth = self::MAX_SIDE_LENGTH;
                $newHeight = $imageHeight * $ratio;   
            }

            $imagick->setImageFormat('jpeg');
            $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
            $imagick->setImageCompressionQuality($quality);
            $imagick->thumbnailImage($newHeight, $newWidth, false, false);

            $fileName = Str::before(Str::afterLast($image, '/'), '.') . '_thumb_' . $currentTimestamp . '.jpg';

            try {
                Storage::disk('images')->put($fileName, $imagick);
                Storage::disk($storageType)->put($fileName, $imagick);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }

            return true;
        }
        else {
            throw new Exception("No valid image provided with {$image}.");
        }
    }
}