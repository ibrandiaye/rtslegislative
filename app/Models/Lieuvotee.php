<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lieuvotee extends Model
{
    use HasFactory;
    protected $fillable = ["nom","centrevotee_id","nb","etat"] ;
    public function centrevotee(){
        return $this->belongsTo(Centrevotee::class);
    } public function rtslieues(){
        return $this->hasMany(Rtslieue::class);
    }

}
