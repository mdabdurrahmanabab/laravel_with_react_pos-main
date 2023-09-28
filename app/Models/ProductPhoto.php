<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPhoto extends Model
{
    use HasFactory;
    protected $fillable =['product_id','is_primary','photo',];

    public const STATUS_ACTIVE =1;
    public const STATUS_ACTIVE_TEXT ='Active';
    public const STATUS_INACTIVE =0;
    public const STATUS_INACTIVE_TEXT ='Inactive';

    public const WIDTH=800;
    public const HEIGHT=800;
    public const WIDTH_THUMBNAIL =200;
    public const HEIGHT_THUMBNAIL =200;

    public const IMAGE_UPLOAD_PATH = "images/uploads/product/";
    public const THUMB_IMAGE_UPLOAD_PATH = "images/uploads/product_thumb/";

    final public function storeProductPhoto($input)
    {
        return self::create($input);
    }
}
