<?php
//This is a model of our application
//It represents the Table on our database
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = ["menu_id", "count", "amount"];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
