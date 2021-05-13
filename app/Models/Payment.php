<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ["order_code", "type", "split_count", "amount", "table_name", "payment_id", "receipt_number"];
    use HasFactory;

    public function references()
    {
        return $this->hasMany(Reference::class);
    }
}
