<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rtsdepartement extends Model
{
    use HasFactory;
    protected $fillable = ["nbvote","departement_id","candidat_id"/*, "nbvv" */] ;
    public function departement(){
        return $this->belongsTo(Departement::class);
    }
    public function candidat(){
        return $this->belongsTo(Candidat::class);
    }
}
