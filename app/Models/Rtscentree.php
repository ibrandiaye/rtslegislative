<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rtscentree extends Model
{
    use HasFactory;
    protected $fillable = ["nbvote","centrevotee_id","candidat_id"/*, "nbvv" */] ;
    public function centrevotee(){
        return $this->belongsTo(Centrevotee::class);
    }
    public function candidat(){
        return $this->belongsTo(Candidat::class);
    }
}
