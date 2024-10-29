<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centrevotee extends Model
{
    use HasFactory;
    protected $fillable = ["nom","localite_id","niveau"] ;

    public function localite(){
        return $this->belongsTo(Localite::class);
    }
    public function lieuvotees(){
        return $this->hasMany(Lieuvotee::class);
    }
    public function rtscentrees(){
        return $this->hasMany(Rtscentree::class);
    }
}
