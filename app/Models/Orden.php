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

    public function scopeByStatus($query, $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function scopeByGroupId($query, $group_id)
    {
        return $group_id ? $query->where('group_id', $group_id) : $query;
    }

    public function scopeByAmount($query, $amount)
    {
        return $amount ? $query->where('amount', $amount) : $query;
    }

}
