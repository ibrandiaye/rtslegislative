<?php
namespace App\Repositories;

use App\Models\Juridiction;
use App\Repositories\RessourceRepository;
use Illuminate\Support\Facades\DB;

class JuridictionRepository extends RessourceRepository{
    public function __construct(Juridiction $juridiction){
        $this->model = $juridiction;
    }
    public function getJuridictionAsc(){
        return DB::table("juridictions")
        ->orderBy("nom","asc")
        ->get();

    }
    public function getAllOnLy(){
        return DB::table("juridictions")
        ->orderBy("nom","asc")
        ->get();
    }
}
