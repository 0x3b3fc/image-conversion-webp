# Laravel Image Converter To WebP

- This package converts images in your Laravel application to WebP format, helping to reduce file sizes and optimize loading speeds.

## Author: Sief Hesham

### Contact: <dev.sief.hesham@gmail.com>

## 1. Requirements

- PHP: 8.0 or above
- Laravel: 10 or above

## Packages

- Spatie Image: For image manipulation.
- Spatie Laravel Media Library: For managing media files.

## 2. Installation

- Step 1: Require the Package
To install the package, run the following command in your Laravel project's root directory:

```bash
composer require phpsamurai/image-conversion:dev-main -W
```

This will add the package to your project's dependencies and make it available in your application.

## 3. Configuration

- Step 1: Set Up Queue Configuration (Optional)
The package uses Laravel’s job dispatching system to convert images asynchronously. If you haven't already configured a queue driver, consider using database, redis, or another supported driver to handle background jobs.

- Open the .env file and set your QUEUE_CONNECTION to a non-sync driver (e.g., database or redis).

```bash
QUEUE_CONNECTION=database
```

Run Migrations to create the necessary database table for queued jobs if you choose the database driver:

```bash
php artisan queue:table
php artisan migrate
```

Start the Queue Worker to process jobs:

```bash
php artisan queue:work
```

This step is optional but recommended for large numbers of images, as the package will otherwise process conversions synchronously, which could impact performance.

## 4. Usage

### Step 1: Run the Conversion Command

Once the package is installed, you can use the following Artisan command to convert images in your media library to WebP format.

```bash
php artisan media:convert-to-webp
```

### Command Description

#### Purpose

- Converts all images in the media library to WebP format.

#### Process

- Fetches all media files with image MIME types (image/jpeg, image/png, etc.) that aren’t already in WebP format.

- Dispatches a job (ConvertImageToWebPJob) for each image, converting it to WebP format and updating the database record.

- Output: Displays a confirmation message once all jobs have been dispatched.

- Note: Each image is converted and stored as a WebP file, replacing the original format in the database and on disk.

### Step 2: Monitor Job Progress (If Using Queues)

- If you're running the command asynchronously with a queue driver (e.g., database or redis), the conversion jobs will be added to your queue. You can monitor the progress of these jobs via your preferred queue monitoring tool or by viewing the queue database table.

- Example Queue Worker Command To run the queue worker in the background:

```bash
php artisan queue:work
```

## 5. Additional Information

Image Conversion Details

- The package uses the Spatie Image package to convert images to WebP format. During conversion, the following steps occur:

- The original image file path is identified and processed.
A new WebP file is generated.

- The original image file is replaced by the WebP file in the storage location.
The media record in the database is updated with the new file name and MIME type (image/webp).

## Important Notes

- Image Formats Supported: The package will convert ``` JPEG, JPG, and PNG ``` formats to WebP. Any other formats will be ignored.

- Data Integrity: The database records for each media file are updated to reflect the new file name and MIME type after conversion.

- Overwriting Files: Each converted WebP file will replace the original image file, so make sure you have backups if you need to keep original formats.

## 6. Troubleshooting

### Common Issues

- Queue Not Processing Jobs: Make sure your queue worker is running (php artisan queue:work). Also, check that your QUEUE_CONNECTION in .env is correctly set to database, redis, or another valid driver.

- File Permission Errors: Ensure that your storage directory has the correct permissions, especially if you're using local storage ``` (storage/app) ```.

- Image Conversion Failures: If an image cannot be converted, ensure that Spatie Image is properly installed and that the image file is accessible.

## 7. License

This package is open-sourced software licensed under the MIT license.
