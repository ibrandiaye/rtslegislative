<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rtscentre extends Model
{
    use HasFactory;
    protected $fillable = ["nbvote","centrevote_id","candidat_id"/*, "nbvv" */] ;
    public function centrevote(){
        return $this->belongsTo(Centrevote::class);
    }
    public function candidat(){
        return $this->belongsTo(Candidat::class);
    }

}
