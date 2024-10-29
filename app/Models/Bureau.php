<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bureau extends Model
{
    use HasFactory;
    protected $fillable = ["nom","prenom","tel","fonction","commune_id","lieuvote_id","profession"] ;
    
    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }
    public function lieuvote()
    {
        return $this->belongsTo(Lieuvote::class);
    }
}
