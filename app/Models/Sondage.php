<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sondage extends Model
{
    use HasFactory;
    protected $fillable = ["email","candidat_id","ipaddress"] ;

    public function candidat(){
        return $this->belongsTo(Candidat::class);
    }

}
