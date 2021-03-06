<?php

return [
    // If true, the uploaded file will be renamed to uniqid() + file extension.
    'rename_file'           => false,

    // If rename_file set to false and this set to true, then filter filename characters which are not alphanumeric.
    'alphanumeric_filename' => true,

    'use_package_routes'    => true,

    // For laravel 5.2, please set to ['web', 'auth']
    'middlewares'           => ['web'],

    // Allow multi_user mode or not.
    // If true, laravel-filemanager create private folders for each signed-in user.
    'allow_multi_user'      => false,

    // The database field to identify a user.
    // When set to 'id', the private folder will be named as the user id.
    // NOTE: make sure to use an unique field.
    'user_field'            => 'id',

    'shared_folder_name'    => 'yipin',
    'org_folder_name'       => 'original',
    'cell_folder_name'      => 'cell',
    'thumb_folder_name'     => 'thumbs',

    'images_dir'            => 'public/photos/',
    'images_url'            => '/photos/',

    'files_dir'             => 'public/files/',
    'files_url'             => '/files/',

    // available since v1.3.0
    'valid_image_mimetypes' => [
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/gif',
        'images/jpeg',
        'images/pjpeg',
        'images/png',
        'images/gif'
    ],

    // available since v1.3.0
    // only when '/laravel-filemanager?type=Files'
    'valid_file_mimetypes' => [
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/gif',
        'images/jpeg',
        'images/pjpeg',
        'images/png',
        'images/gif',
        'application/pdf',
        'text/plain',
    ],

    // file extensions array, only for showing file information, it won't affect the upload process.
    'file_type_array'         => [
        'pdf'  => 'Adobe Acrobat',
        'docx' => 'Microsoft Word',
        'docx' => 'Microsoft Word',
        'xls'  => 'Microsoft Excel',
        'xls'  => 'Microsoft Excel',
        'zip'  => 'Archive',
        'gif'  => 'GIF Image',
        'jpg'  => 'JPEG Image',
        'jpeg' => 'JPEG Image',
        'png'  => 'PNG Image',
        'ppt'  => 'Microsoft PowerPoint',
        'pptx' => 'Microsoft PowerPoint',
    ],

    // file extensions array, only for showing icons, it won't affect the upload process.
    'file_icon_array'         => [
        'pdf'  => 'fa-file-pdf-o',
        'docx' => 'fa-file-word-o',
        'docx' => 'fa-file-word-o',
        'xls'  => 'fa-file-excel-o',
        'xls'  => 'fa-file-excel-o',
        'zip'  => 'fa-file-archive-o',
        'gif'  => 'fa-file-images-o',
        'jpg'  => 'fa-file-images-o',
        'jpeg' => 'fa-file-images-o',
        'png'  => 'fa-file-images-o',
        'ppt'  => 'fa-file-powerpoint-o',
        'pptx' => 'fa-file-powerpoint-o',
    ],
];
