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
            'tmp' => '/assets/tmp_bg.png',
            'path' => 'upload/commerce_image',
            'name' => 'cover.jpg',
            'size' => array(
                'width' => 1440,
                'height' => 310
            )
        ),
        'commerceLogo' => array(
            'tmp' => '/assets/tmp_logo.png',
            'path' => 'upload/commerce_image',
            'name' => 'logo.jpg',
            'size' => array(
                'width' => 255,
                'height' => 206
            )
        ),
    ),
    /*
      |--------------------------------------------------------------------------
      | Order Status
      |--------------------------------------------------------------------------
      |
      | Contains order status description.
      |
      |
     */
    'order_status' => array(
        'pending' => 1,
        'progress' => 2,
        'ready' => 3,
        'done' => 4,
        'canceled' => 5,
        'not_delivered' => 6,
    ),
    /*
      |--------------------------------------------------------------------------
      | User Type
      |--------------------------------------------------------------------------
      |
      | Contains User Rol description.
      |
      |
     */
    'user_type' => array(
        'admin' => 1,
        'commerce' => 2,
        'customer' => 3,
        'branch_manager' => 4,
        'branch' => 5,
        'kitchen' => 6,
    ),
    /*
      |--------------------------------------------------------------------------
      | Steps
      |--------------------------------------------------------------------------
      |
      | Contains User Rol description.
      |
      |
     */
    'steps' => array(
        'commerce_name' => array(
            'id' => 1
        ),
        'branch_create' => array(
            'id' => 2
        ),
    ),
    /*
      |--------------------------------------------------------------------------
      | Mobile Format
      |--------------------------------------------------------------------------
      |
      | Contains available mobile formats for placeholders.
      |
      |
     */
    'mobile' => array(
        'ar' => '011 2345 6789',
        'uy' => '098 123 456',
        'py' => '0981 234 567',
    )
);
