<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pays extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom','juridiction_id'
    ];

    public function juridiction(){
        return $this->belongsTo(Juridiction::class);
    }
}
