<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rtscommune extends Model
{
    use HasFactory;
    protected $fillable = ["nbvote","commune_id","candidat_id"/*, "nbvv" */] ;
    public function commune(){
        return $this->belongsTo(Commune::class);
    }
    public function candidat(){
        return $this->belongsTo(Candidat::class);
    }
}
