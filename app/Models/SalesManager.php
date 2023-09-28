<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Address;

class SalesManager extends Model
{
    use HasFactory;
    protected $fillable =['name','phone','email','password','nid','bio','status','user_id','shop_id','photo','nid_photo'];

    public const STATUS_ACTIVE = 1;
    public const STATUS_ACTIVE_TEXT ='Active';
    public const STATUS_INACTIVE = 0;
    public const STATUS_INACTIVE_TEXT ='Inactive';

    public const WIDTH=800;
    public const HEIGHT=800;
    public const WIDTH_THUMBNAIL =200;
    public const HEIGHT_THUMBNAIL =200;

    public const IMAGE_UPLOAD_PATH = "images/uploads/sales_manager/";
    public const THUMB_IMAGE_UPLOAD_PATH = "images/uploads/sales_manager_thumb/";

    public function prepareData(array $input,$auth)
    {
        $supplier['name'] = $input['name'] ?? null;
        $supplier['phone'] = $input['phone'] ?? null;
        $supplier['email'] = $input['email'] ?? null;
        $supplier['bio'] = $input['bio'] ?? null;
        $supplier['nid'] = $input['nid'] ?? null;
        $supplier['status'] = $input['status'] ?? 0;
        $supplier['user_id'] = $auth->id() ?? 0;
        $supplier['shop_id'] = $input['shop_id'] ?? 0;
        $supplier['password'] = Hash::make($input['password']);

        return $supplier;
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
