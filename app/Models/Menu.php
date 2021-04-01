<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $fillable = ['title','price','ingredients','image_path','category_id'];
    protected $hidden = ['created_at','updated_at'];
}
