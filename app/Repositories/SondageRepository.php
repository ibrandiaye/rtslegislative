<?php
namespace App\Repositories;

use App\Models\Sondage;
use App\Repositories\RessourceRepository;
use Illuminate\Support\Facades\DB;

class SondageRepository extends RessourceRepository{
    public function __construct(Sondage $sondage){
        $this->model = $sondage;
    }
    public function  rtsSondageByCandidat(){

        return   DB::table('sondages')
      ->join('candidats','sondages.candidat_id','=','candidats.id')
      ->select('candidats.nom','candidats.photo' ,DB::raw('count(sondages.id) as nb'))
      ->groupBy('candidats.nom','candidats.photo')
  ->get();
    }
    public function nbSondage(){
        return   DB::table('sondages')
        ->count();
        //->get();


    }
}
