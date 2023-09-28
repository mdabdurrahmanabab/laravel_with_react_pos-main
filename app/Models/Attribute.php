<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\AttributeValue;

class Attribute extends Model
{
    use HasFactory;
    protected $fillable=['name','status','user_id'];

    public const STATUS_ACTIVE =1;

    public function getAttributeList()
    {
        return self::query()->with(['user:id,name','value','value.user:id,name'])->orderBy('id','DESC')->paginate(10);
    }

    public function getAttributeIdAndNameWithValue()
    {
        return self::query()->select('id','name')->with('value:id,name,attribute_id')->where('status',self::STATUS_ACTIVE)->get();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function value()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
