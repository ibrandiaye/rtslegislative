<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centrevote extends Model
{
    use HasFactory;
    protected $fillable = ["nom","commune_id","niveau"] ;
    public function commune(){
        return $this->belongsTo(Commune::class);
    }
    public function lieuvotes(){
        return $this->hasMany(Lieuvote::class);
    }
    public function rtscentres(){
        return $this->hasMany(Rtscentre::class);
    }
}
