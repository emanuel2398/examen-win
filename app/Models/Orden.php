<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;
    protected $table = "ordenes";
    protected $fillable = ['id','order_id', 'status', 'amount', 'group_id'];
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    // Scope para filtrar por estado
    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    // Scope para filtrar por group_id
    public function scopeByGroupId($query, $group_id)
    {
        if ($group_id) {
            return $query->where('group_id', $group_id);
        }
        return $query;
    }

    // Scope para filtrar por amount
    public function scopeByAmount($query, $amount)
    {
        if ($amount) {
            return $query->where('amount', $amount);
        }
        return $query;
    }

}
