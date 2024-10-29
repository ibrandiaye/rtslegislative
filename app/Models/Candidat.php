<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidat extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom','coalition','photo'
    ];
    public function rtscentres(){
        return $this->hasMany(Rtscentre::class);
    }
    public function rtslieus(){
        return $this->hasMany(Rtslieu::class);
    }
    public function sondages(){
        return $this->hasMany(Sondage::class);
    }
}
