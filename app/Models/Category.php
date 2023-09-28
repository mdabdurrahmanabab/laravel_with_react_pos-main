<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public const IMAGE_UPLOAD_PATH = "images/uploads/category/";
    public const THUMB_IMAGE_UPLOAD_PATH = "images/uploads/category_thumb/";
    
    protected $fillable =['name','slug','serial','status','description','photo','user_id'];

    public const STATUS_ACTIVE = 1;

    final public function getCategory(array $input)
    {
        $per_page = $input['per_page'] ?? 10;
        $query = self::query();

        if(!empty($input['search'])){
            $query->where('name','like','%'.$input['search'].'%');
        }
        if(!empty($input['order_by'])){
            $query->orderBy($input['order_by'], $input['direction'] ?? 'asc');
        }

        return $query->with('user:id,name')->orderBy('serial','asc')->paginate($per_page);
    }

    final public function getCategoryIdAndName(){
        return self::query()->select('id','name')->where('status',self::STATUS_ACTIVE)->get();
    }

    final public function storeCategory(array $input)
    {
        return self::query()->create($input);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
