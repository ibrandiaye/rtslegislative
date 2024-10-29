<?php
namespace App\Repositories;

use App\Models\Collecteur;
use App\Repositories\RessourceRepository;
use Illuminate\Support\Facades\DB;

class CollecteurRepository extends RessourceRepository{
    public function __construct(Collecteur $collecteur){
        $this->model = $collecteur;
    }
    public function getCollecteurByTel($tel){
        return DB::table("collecteurs")
        ->where("tel",$tel)
        ->first();

    }
}
