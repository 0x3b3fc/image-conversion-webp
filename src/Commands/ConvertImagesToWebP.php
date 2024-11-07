<?php

namespace phpsamurai\ImageConversion\Commands;

use Illuminate\Console\Command;
use PhpSamurai\ImageConversion\Jobs\ConvertImageToWebPJob;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ConvertImagesToWebP extends Command
{
    protected $signature = 'media:convert-to-webp';
    protected $description = 'Convert all media images to WebP format';

    public function handle()
    {
        // Get all media items that are images and not already in WebP format
        $mediaItems = Media::where('mime_type', 'like', 'image/%')
            ->where('file_name', 'not like', '%.webp')
            ->get();

        // Dispatch jobs for each media item to convert to WebP
        foreach ($mediaItems as $media) {
            ConvertImageToWebPJob::dispatch($media);
        }

        $this->info('Conversion jobs have been dispatched.');
    }
}
