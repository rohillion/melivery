<?php

Class ImageManager {

    static function upload($file, $path, $fileName = NULL, $maxWidth = 0, $maxheight = 0) {
        
        $Filesystem = new Filesystem();

        if (!$Filesystem->exists($path)) {

            $Filesystem->makeDirectory($path);
        }
        
        if (is_null($fileName)) {

            $fileName = $file->getClientOriginalName();
        }

        $fileName = str_random(10) . '.' . $file->getClientOriginalExtension();

        $file->move($productPhotoPath, $productPhotoName);

        $fitImg = \Image::make($productPhotoPath . '/' . $productPhotoName)->fit(360, 240);
        $fitImg->save($productPhotoPath . '/' . $productPhotoName);

        $this->update($product->id, ['image' => $productPhotoName]);

        return true;
    }

    static function validateAspect($file, $aspect_ratio) {

        // Check first that the file is an image.
        if ($info = image_get_info($file->uri)) {
            if ($aspect_ratio) {
                // Check that it is smaller than the given dimensions.
                list($width, $height) = explode(':', $aspect_ratio);
                if ($width * $info['height'] != $height * $info['width']) {
                    $errors[] = t('The image is the wrong aspect ratio; the aspect ratio needed is %ratio.', array('%ratio' => $aspect_ratio));
                }
            }
        }
    }

}
