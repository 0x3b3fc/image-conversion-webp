<?php

return [
    'image_folder' => env('IMAGE_FOLDER', 'images'),  // Image folder in the public disk
    'webp_quality' => env('WEBP_QUALITY', 80),        // Quality of WebP images (0 to 100)
    'use_queue' => env('IMAGE_CONVERSION_USE_QUEUE', false),  // Enable queueing for async processing
];
