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

        // If the WebP image doesn't exist, create it
        if (!Storage::exists($webpPath)) {
            Image::load($originalPath)
                ->format('webp')
                ->save($webpPath);

            // Update the media record to reflect the WebP file
            $this->media->update([
                'file_name' => preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $this->media->file_name),
                'mime_type' => 'image/webp',
            ]);
        }
    }
}
