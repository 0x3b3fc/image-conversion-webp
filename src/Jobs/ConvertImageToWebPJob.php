<?php

namespace phpsamurai\ImageConversion\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Image\Image;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Storage;

class ConvertImageToWebPJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $media;

    public function __construct(Media $media)
    {
        $this->media = $media;
    }

    public function handle()
    {
        $originalPath = $this->media->getPath();
        $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $originalPath);

        // Ensure WebP file doesn't already exist
        if (!Storage::exists($webpPath)) {
            // Get the WebP quality from the config
            $webpQuality = config('image-conversion.webp_quality', 80); // Default to 80 if not set

            // Convert the image to WebP format with the specified quality
            Image::load($originalPath)
                ->format('webp')
                ->quality($webpQuality)  // This applies the quality setting
                ->save($webpPath);

            // Update the media record to point to the new WebP file
            $this->media->update([
                'file_name' => preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $this->media->file_name),
                'mime_type' => 'image/webp',
            ]);

            // Replace the original image with the new WebP image
            Storage::move($webpPath, $originalPath);
        }
    }
}
