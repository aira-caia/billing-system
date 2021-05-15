<?php
//This is a model of our application
//It represents the Table on our database
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'image_path'];


    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
