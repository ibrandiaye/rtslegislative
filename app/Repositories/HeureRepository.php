<?php
namespace App\Repositories;

use App\Models\Heure;
use App\Repositories\RessourceRepository;
use Illuminate\Support\Facades\DB;

class HeureRepository extends RessourceRepository{
    public function __construct(Heure $heure){
        $this->model = $heure;
    }

    public function getAlls()
    {
        return DB::table("heures")->get();
    }
   
}
