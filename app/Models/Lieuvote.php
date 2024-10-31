<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lieuvote extends Model
{
    use HasFactory;
    protected $fillable = ["nom","centrevote_id","nb","etat",'votant','bulnull','hs'] ;
    public function centrevote(){
        return $this->belongsTo(Centrevote::class);
    }
    public function rtslieus(){
        return $this->hasMany(Rtslieu::class);
    }
    public function bureaus(){
        return $this->hasMany(Bureau::class);
    }

}
