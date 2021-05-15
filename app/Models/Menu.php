<?php
//This is a model of our application
//It represents the Table on our database
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Menu extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'price', 'ingredients', 'image_path', 'category_id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function getImagePathAttribute($value)
    {
        return env("APP_URL") . Storage::url("images/menu/" . $value);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
