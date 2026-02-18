<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudinary URL
    |--------------------------------------------------------------------------
    |
    | Format:
    | cloudinary://API_KEY:API_SECRET@CLOUD_NAME
    |
    */
    'cloud_url' => env('CLOUDINARY_URL'),

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Credentials (Option B - Explicit)
    |--------------------------------------------------------------------------
    */

    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
    'api_key'    => env('CLOUDINARY_API_KEY'),
    'api_secret' => env('CLOUDINARY_API_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Default Base Folder
    |--------------------------------------------------------------------------
    */

    'base_folder' => env('CLOUDINARY_BASE_FOLDER', 'payments'),

];
