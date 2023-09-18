<?php

namespace App\Models;

use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Apartment extends Model
{
    use HasFactory;
    protected $fillable = ['title','slug','n_rooms','n_beds','n_bathrooms','mq','address','latitude','longitude','img','description','price','user_id', 'visible'];

    public static function generateSlug($title)
    {
        return Str::slug($title, '-');
    }
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function services() {
        return $this->belongsToMany(Service::class);
    }
}
