<?php

namespace phpsamurai\ImageConversion;

use phpsamurai\ImageConversion\Jobs\ConvertImageToWebPJob;
use Spatie\Image\Image;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class ImageConversionService
{
    protected $imageFolder;
    protected $quality;
    protected $useQueue;

    public function __construct()
    {
        // Fetch configurations
        $this->imageFolder = Config::get('image-conversion.image_folder', 'images');
        $this->quality = Config::get('image-conversion.webp_quality', 80);
        $this->useQueue = Config::get('image-conversion.use_queue', false);
    }

    /**
     * Convert all images in the folder to WebP format.
     *
     * @return void
     */
    public function convertToWebP()
    {
        // Get all image files from the image folder
        $files = Storage::disk('public')->files($this->imageFolder);

        foreach ($files as $file) {
            // Skip non-image files
            if (!in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                continue;
            }

            // Convert the image to WebP format
            if ($this->useQueue) {
                // If queue is enabled, dispatch a job for conversion
                ConvertImageToWebPJob::dispatch($file);
            } else {
                // Synchronously convert the image
                $this->convertImage($file);
            }
        }
    }

    /**
     * Convert a single image to WebP format.
     *
     * @param string $file
     * @return void
     */
    public function convertImage(string $file)
    {
        $imagePath = Storage::disk('public')->path($file);

        // Load the image using Spatie Image package
        $image = Image::load($imagePath);

        // Define the WebP file path
        $webpFilePath = pathinfo($imagePath, PATHINFO_DIRNAME) . '/' . pathinfo($imagePath, PATHINFO_FILENAME) . '.webp';

        // Save the image as WebP
        $image->save($webpFilePath, $this->quality);

        // Optionally, delete the original image if desired
        // Storage::disk('public')->delete($file);
    }
}
