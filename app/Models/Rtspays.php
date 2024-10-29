<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rtspays extends Model
{
    use HasFactory;
    protected $fillable = ["nbvote","pays_id","candidat_id","etat"] ;
    public function pays(){
        return $this->belongsTo(Pays::class);
    }
    public function candidat(){
        return $this->belongsTo(Candidat::class);
    }
}
