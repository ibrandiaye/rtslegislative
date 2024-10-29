<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Jenssegers\Mongodb\Eloquent\Model ;

class Commune extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom','departement_id','latitude','longitude','arrondissement_id'
    ];
    public function departement(){
        return $this->belongsTo(Departement::class);
    }
    public function centrevotes(){
        return $this->hasMany(Centrevote::class);
    }

    public function arrondissement(){
        return $this->belongsTo(Arrondissement::class);
    }
}
