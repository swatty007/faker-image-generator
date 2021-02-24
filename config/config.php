<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    /*
        |--------------------------------------------------------------------------
        | Directory
        |--------------------------------------------------------------------------
        |
        | This option defines, where our generated images should be saved.
        | If nothing is specified we will fallback to the systems tmp folder!
        |
        */
    'directory' => sys_get_temp_dir(),


    /*
        |--------------------------------------------------------------------------
        | FullPath
        |--------------------------------------------------------------------------
        |
        | This option defines, if our generator will return the full filepath of the generated image or only the image name
        | The default options is true, so you will receive the image name and its filepath as return value.
        |
        */
    'full_path' => true,


    /*
        |--------------------------------------------------------------------------
        | Format
        |--------------------------------------------------------------------------
        |
        | This option defines, the default image format of our generated images, if no overwrite is specified within the generator instance.
        | This option can take `jpg, jpeg or png` as valid values, default to jpeg.
        |
        */
    'format' => 'jpeg',

    /*
        |--------------------------------------------------------------------------
        | Width
        |--------------------------------------------------------------------------
        |
        | This option defines, the default width of our generated images, if no overwrite is specified within the generator instance.
        |
        */
    'width' => 640,

    /*
        |--------------------------------------------------------------------------
        | Height
        |--------------------------------------------------------------------------
        |
        | This option defines, the default height of our generated images, if no overwrite is specified within the generator instance.
        |
        */
    'height' => 480,

    /*
        |--------------------------------------------------------------------------
        | Background Color
        |--------------------------------------------------------------------------
        |
        | This option defines, the default background color of our generated images, if no overwrite is specified within the generator instance.
        |
        */
    'background_color' => null,

    /*
        |--------------------------------------------------------------------------
        | Text
        |--------------------------------------------------------------------------
        |
        | This option defines, the default text of our generated images, if no overwrite is specified within the generator instance.
        | The generator will try to make the font-size fit the picture size.
        | *A special value can be used*, if set to `true`, will return the width and height as text of the picture (example: `640x480`).
        |
        */
    'text' => true,

    /*
        |--------------------------------------------------------------------------
        | Text Color
        |--------------------------------------------------------------------------
        |
        | This option defines, the default text color of our generated images, if no overwrite is specified within the generator instance.
        |
        */
    'text_color' => null,

    /*
        |--------------------------------------------------------------------------
        | Font
        |--------------------------------------------------------------------------
        |
        | This option defines, the default font of our generated images, if no overwrite is specified within the generator instance.
        |
        */
    'font_face' => public_path('vendor/faker-image-generator/fonts/Roboto-Regular.ttf'),
];
