<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [
        'id',
    ];

    // Tambahkan relasi ke saleDetails
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
}


