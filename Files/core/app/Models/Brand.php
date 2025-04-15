<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Brand extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'meta_keywords' => 'array'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }


    public function scopeTop($query)
    {
        return $query->where('top', 1);
    }

    public function logo()
    {
        return getImage(getFilePath('brand') . '/' . $this->logo, getFileSize('brand'));
    }
}
