<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Apartment extends Model
{
    use HasFactory;
    protected $fillable = ['title','slug','n_rooms','n_beds','n_bathrooms','mq','address','latitude','longitude','img','price','user_id'];

    public static function generateSlug($title)
    {
        return Str::slug($title, '-');
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
