<?php

return array(
    /*
      |--------------------------------------------------------------------------
      | Image
      |--------------------------------------------------------------------------
      |
      | Contains image info like path and size.
      |
      |
     */

    'image' => array(
        'product' => array(
            'path' => 'upload/product_photo',
            'name' => NULL,
            'size' => array(
                'width' => 200,
                'height' => 200
            )
        ),
        'branchMap' => array(
            'path' => 'upload/branch_image',
            'name' => NULL,
            'size' => array(
                'width' => 200,
                'height' => 200
            )
        ),
        'commerceCover' => array(
            'path' => 'upload/commerce_image',
            'name' => 'cover.jpg',
            'size' => array(
                'width' => 1440,
                'height' => 310
            )
        ),
        'commerceLogo' => array(
            'path' => 'upload/commerce_image',
            'name' => 'logo.jpg',
            'size' => array(
                'width' => 255,
                'height' => 206
            )
        ),
    )
);
