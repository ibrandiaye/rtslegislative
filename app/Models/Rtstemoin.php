<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rtstemoin extends Model
{
    use HasFactory;
    protected $fillable = [
        'nbvote','lieuvote_id','candidat_id'
    ];

    public function lieuvote(){
    return $this->belongsTo(Lieuvote::class);
    }

    public function candidat(){
        return $this->belongsTo(Candidat::class);
    }
}
