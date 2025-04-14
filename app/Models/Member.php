<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'no_telephone',
        'point',
        'date'
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
