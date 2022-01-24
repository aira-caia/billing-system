<?php
//This is a model of our application
//It represents the Table on our database
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ["order_code", "type", "split_count", "amount", "table_name", "payment_id", "receipt_number", "method"];
    use HasFactory;


    protected $casts = ['is_served' => 'boolean'];

    public function references()
    {
        return $this->hasMany(Reference::class);
    }
}
