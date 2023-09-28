<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    use HasFactory;
    protected $fillable=['product_id','name','value'];

    final public function storeSpecificatioData($input,$product)
    {
         $specifications_data = $this->prepareSpecificatioData($input,$product);
         foreach ($specifications_data as $specification) {
            self::create($specification);
        }
    }

    private function prepareSpecificatioData($input,$product)
    {
        $specification_data =[];
        foreach ($input as $value) {
             $data['product_id'] = $product->id;
             $data['name'] = $value['name'];
             $data['value'] = $value['value'];
             $specification_data[]= $data;
         }
         return $specification_data;
    }
}
