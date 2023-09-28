<?php 
namespace App\Manager;

use Intervention\Image\Facades\Image;

class ImageManager{
	public const DEFAULT_IMAGE = "images/default.jpg";

	final public static function uploadImage(string $name,int $width,int $height,string $path,string $file)
	{
		$image_file_name = $name.'.webp';
		Image::make($file)->fit($width, $height)->save(public_path($path).$image_file_name,50,'webp');

		return $image_file_name;
	}

	final public static function deletePhoto($path, $img){
		$path = public_path($path).$img;
		if($img != '' && file_exists($path)){
			unlink($path);
		}
	}

	final public static function prepareImageUrl($path, $img)
	{
		$url = url($path.$img);
		if(empty($img)){
			$url = url(self::DEFAULT_IMAGE);
		}
		return $url;
	}

	public static function processImageUpload(
		$file,
		$name,
		$path,
		$width,
		$height,
		$thumb_path=null,
		$width_thumbnail=0,
		$height_thumbnail=0,
		$existing_photo=''){
        if(!empty($existing_photo)){
            self::deletePhoto($path, $existing_photo);
            if(!empty($thumb_path)){
            	self::deletePhoto($thumb_path, $existing_photo);
        	}
        }

        $photo_name = self::uploadImage($name,$width,$height,$path,$file);
        if(!empty($thumb_path)){
        	self::uploadImage($name,$width_thumbnail, $height_thumbnail, $thumb_path, $file);
        }
        return $photo_name;
    }

}


?>