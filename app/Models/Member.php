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

    
    public function getPreviousPoints()
    {
        $pointHistory = json_decode($this->point_history, true) ?? [];
        
        // Cari entry terakhir dengan points_earned
        $lastEarned = collect($pointHistory)->where('type', 'earned')->last();
        
        return $lastEarned['points_earned'] ?? 0;
    }

    public function updatePoints($earnedPoints)
{
    $this->point_usable = $this->point; // Point sebelum transaksi
    $this->point += $earnedPoints;
    $this->point_earned = $earnedPoints;
    $this->save();
}
}



