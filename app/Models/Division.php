<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;
    protected $guarded=[];

    final public function getDevision()
    {
        return self::query()->select('id','name')->get();
    }
}
