<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Image;

trait ImageTrait
{

    private $public_path = 'app/public/uploads/';

    /**
     * @param String $image imagen binaria base 64
     * @param Model $idNumber id del registro
     * @param String $tagName nombre para el nuevo archivo
     * @param string $nameValueFoto  nombre del archivo existente
     * @return string
     */
    public function saveImage($image, $idNumber, $tagName, $nameValueFoto = null)
    {
        try {
            if ($nameValueFoto) {
                $imageExist = storage_path($this->public_path . $nameValueFoto);
                if ($nameValueFoto && file_exists($imageExist)) {
                    unlink($imageExist);
                    unlink(storage_path($this->public_path . 'thumbnail/' . $nameValueFoto));
                    unlink(storage_path($this->public_path . 'thumbnail-small/' . $nameValueFoto));
                }
            }
            $imageName = $tagName . '_' . $idNumber . date('ymdHis').'.jpg';
            $path = storage_path($this->public_path . $imageName);
            Image::make(file_get_contents($image))->save($path); 

            $img = Image::make(storage_path($this->public_path . $imageName))->resize(200, 275);
            $img->save(storage_path($this->public_path . 'thumbnail/' . $imageName));
            $imgSm = Image::make(storage_path($this->public_path . $imageName))->resize(50, 50);
            $imgSm->save(storage_path($this->public_path . 'thumbnail-small/' . $imageName));
            return $imageName;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
