<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable=['category_id','sub_category_id','brand_id','country_id','supplier_id','created_by_id','updated_by_id','name','slug','cost','price','sku','stock','discount_percent','discount_fixed','discount_start','discount_end','description','status'];
    
    public const STATUS_ACTIVE =1;

    public function getProductList($input)
    {
        $per_page = $input['per_page'] ?? 10;
        $query = self::query()->with([
            'category:id,name',
            'sub_category:id,name',
            'brand:id,name',
            'country:id,name',
            'supplier:id,name,phone',
            'created_by:id,name',
            'updated_by:id,name',
            'primary_photo',
            'product_attributes',
            'product_attributes.attributes',
            'product_attributes.attribute_value',
        ]);

        if(!empty($input['search'])){
            $query->where('name','like','%'.$input['search'].'%')
            ->orWhere('phone','like','%'.$input['search'].'%')
            ->orWhere('email','like','%'.$input['search'].'%');
        }
        if(!empty($input['order_by'])){
            $query->orderBy($input['order_by'], $input['direction'] ?? 'asc');
        }

        return $query->paginate($per_page);
    }

    final public function storeProduct($input, $auth_id)
    {
        $product_data = $this->prepareData($input, $auth_id);
        return self::create($product_data);
    }

    private function prepareData($input, $auth_id)
    {
        return [
            'category_id'       =>$input['category_id'] ?? 0,
            'sub_category_id'   =>$input['sub_category_id'] ?? 0,
            'brand_id'          =>$input['brand_id'] ?? 0,
            'country_id'        =>$input['country_id'] ?? 0,
            'supplier_id'       =>$input['supplier_id'] ?? 0,
            'created_by_id'     =>$auth_id,
            'updated_by_id'     =>$auth_id,
            'name'              =>$input['name'] ?? '',
            'slug'              =>$input['slug'] ? Str::slug($input['slug']) : '',
            'cost'              =>$input['cost'] ?? 0,
            'price'             =>$input['price'] ?? 0,
            'sku'               =>$input['sku'] ?? '',
            'stock'             =>$input['stock'] ?? 0,
            'discount_percent'  =>$input['discount_percent'] ?? 0,
            'discount_fixed'    =>$input['discount_fixed'] ?? 0,
            'discount_start'    =>$input['discount_start'] ?? null,
            'discount_end'      =>$input['discount_end'] ?? null,
            'description'       =>$input['description'] ?? '',
            'status'            =>$input['status'] ?? 0,
        ];
    }

    
    public function getProductById($id)
    {
        return self::query()->findOrFail($id);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class,'sub_category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class,'created_by_id');
    }

    public function updated_by()
    {
        return $this->belongsTo(User::class,'updated_by_id');
    }

    public function primary_photo()
    {
        return $this->hasOne(ProductPhoto::class)->where('is_primary',1);
    }
    public function product_attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }


}
