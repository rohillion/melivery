<?php

use Illuminate\Filesystem\Filesystem;

Class MeliveryImageManager {

    static function upload($file, $path, $fileName = NULL, $maxWidth = 0, $maxheight = 0) {

        try {

            $Filesystem = new Filesystem();

            if (!$Filesystem->exists($path)) {

                $Filesystem->makeDirectory($path);
            }

            if (is_null($fileName)) {

                $fileName = $file->getClientOriginalName();
                //$fileName = str_random(10) . '.' . $file->getClientOriginalExtension();
            }

            //$file->move($productPhotoPath, $productPhotoName);

            if ($maxheight) {

                $fitImg = \Image::make($file->getRealPath())->fit($maxWidth, $maxheight);
            } else {

                $fitImg = \Image::make($file->getRealPath())->fit($maxWidth);
            }

            $fitImg->save($path . '/' . $fileName);

        } catch (\Exception $e) {
            throw new \Exception($e);
        }
        
        return true;
    }

}
