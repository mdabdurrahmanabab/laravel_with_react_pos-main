<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Address;

class Shop extends Model
{
    use HasFactory;
    protected $fillable =['name','phone','email','details','status','user_id','logo'];

    public const STATUS_ACTIVE =1;
    public const STATUS_ACTIVE_TEXT ='Active';
    public const STATUS_INACTIVE =0;
    public const STATUS_INACTIVE_TEXT ='Inactive';

    public const WIDTH=800;
    public const HEIGHT=800;
    public const WIDTH_THUMBNAIL =200;
    public const HEIGHT_THUMBNAIL =200;

    public const IMAGE_UPLOAD_PATH = "images/uploads/shop/";
    public const THUMB_IMAGE_UPLOAD_PATH = "images/uploads/shop_thumb/";

    public function prepareData(array $input,$auth)
    {
        $shop['name'] = $input['name'] ?? '';
        $shop['phone'] = $input['phone'] ?? '';
        $shop['email'] = $input['email'] ?? '';
        $shop['details'] = $input['details'] ?? '';
        $shop['status'] = $input['status'] ?? '';
        $shop['user_id'] = $auth->id() ?? '';

        return $shop;
    }

    public function getShopList($input)
    {
        $per_page = $input['per_page'] ?? 10;
        $query = self::query()->with('address','address.division:id,name','address.district:id,name','address.area:id,name','user:id,name');

        if(!empty($input['search'])){
            $query->where('name','like','%'.$input['search'].'%')
            ->orWhere('phone','like','%'.$input['search'].'%')
            ->orWhere('email','like','%'.$input['search'].'%');
        }
        if(!empty($input['order_by'])){
            $query->orderBy($input['order_by'], $input['direction'] ?? 'asc');
        }

        return $query->orderBy('created_at','desc')->paginate($per_page);
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getShopIdAndName()
    {
        return self::query()->select('id','name')->where('status',self::STATUS_ACTIVE)->get();
    }
}
