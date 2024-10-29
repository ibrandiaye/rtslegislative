<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rtslieue extends Model
{
    use HasFactory;
    protected $fillable = ["nbvote","lieuvotee_id","candidat_id"/* ,"nbvv" */] ;
    public function lieuvotee(){
        return $this->belongsTo(Lieuvotee::class);
    }
    public function candidat(){
        return $this->belongsTo(Candidat::class);
    }
}
