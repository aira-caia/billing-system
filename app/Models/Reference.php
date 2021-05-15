<?php
//This is a model of our application
//It represents the Table on our database
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    use HasFactory;
    protected $fillable = ['reference_number'];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
