<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'status', 'amount', 'group_id'];
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }
}
