<?php

namespace phpsamurai\ImageConversion\Commands;

use Illuminate\Console\Command;
use phpsamurai\ImageConversion\Jobs\ConvertImageToWebPJob;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ConvertImagesToWebP extends Command
{
    protected $signature = 'media:convert-to-webp';
    protected $description = 'Convert all media images to WebP format';

    public function handle()
    {
        $mediaItems = Media::where('mime_type', 'like', 'image/%')
            ->where('file_name', 'not like', '%.webp')
            ->get();

        foreach ($mediaItems as $media) {
            ConvertImageToWebPJob::dispatch($media);
        }

        $this->info('Conversion jobs have been dispatched.');
    }
}
