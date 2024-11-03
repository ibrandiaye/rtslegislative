<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participation extends Model
{
    use HasFactory;
    protected $fillable = [
        'resultat','heure_id','departement_id',"etat","region_id","lieuvote_id"
    ];

    public function departement(){
    return $this->belongsTo(Departement::class);
    }
    public function heure(){
        return $this->belongsTo(Heure::class);
    }
    public function region(){
        return $this->belongsTo(Region::class);
    }
    public function lieuvote(){
        return $this->belongsTo(Lieuvote::class);
    }
}

