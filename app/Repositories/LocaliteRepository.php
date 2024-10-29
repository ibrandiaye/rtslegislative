<?php
namespace App\Repositories;

use App\Models\Localite;
use App\Repositories\RessourceRepository;
use Illuminate\Support\Facades\DB;

class LocaliteRepository extends RessourceRepository{
    public function __construct(Localite $localite){
        $this->model = $localite;
    }
    public function getAllWithPays(){
        return Localite::with('pays')
        ->get();
    }

    public function getByPays($pays){
        return DB::table("localites")
        ->where("pays_id",$pays)
        ->orderBy("nom","asc")

        ->get();
}
public function getAllOnLy(){
    return DB::table("localites")
    ->orderBy("nom","asc")
    ->get();
}
}
