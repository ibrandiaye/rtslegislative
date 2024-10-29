<?php
namespace App\Repositories;

use App\Models\Participation;
use App\Repositories\RessourceRepository;
use Illuminate\Support\Facades\DB;

class ParticipationRepository extends RessourceRepository{
    public function __construct(Participation $participation){
        $this->model = $participation;
    }

    public function getParticpationByBureaudeVote($id){
        return DB::table("participations")
        ->where("lieuvote_id", $id)
        ->first();
    }
    public function getParticipationByHeure($id){
        return DB::table("participations")
        ->join('heures','participations.heure_id','=','participations.id')
        ->join('lieuvotes','participations.lieuvote_id','=','lieuvotes.id')
        ->join('centrevotes','lieuvotes.centrevote_id','=','centrevotes.id')
        ->join('communes','centrevotes.commune_id','=','communes.id')
        ->join('departements','communes.departement_id','=','departements.id')
        ->join('regions','departements.region_id','=','regions.id')
        ->select('participations.resultat','lieuvotes.nom as bureau','centrevotes.nom as centre','communes.nom as commune','departements.nom as departement','regions.nom as region','heures.designation')
        ->where("heures.designation", $id)
        ->get();
    }
    public function getParticipationGroupByHeure(){
        return DB::table("participations")
        ->join('heures','participations.heure_id','=','heures.id')
      
        ->select('heures.designation',DB::raw('sum(participations.resultat) as nb'))
        ->groupBy("heures.designation")
        ->get();
    }
   
   
}
