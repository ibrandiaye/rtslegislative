<?php
namespace App\Repositories;

use App\Models\Rtslieu;
use App\Repositories\RessourceRepository;
use Illuminate\Support\Facades\DB;

class RtslieuRepository extends RessourceRepository{
    public function __construct(Rtslieu $rtslieu){
        $this->model = $rtslieu;
    }
    public function nbRtslieu(){
        return   DB::table('rtslieus')
        ->count();
        //->get();


    }

    public function nbVotants(){
        return   Rtslieu::sum('nbvote');


    }
    public function  rtsByCandidat(){

        return   DB::table('rtslieus')
      ->join('candidats','rtslieus.candidat_id','=','candidats.id')
      ->select('candidats.id','candidats.nom','candidats.photo','candidats.coalition' ,DB::raw('sum(rtslieus.nbvote) as nb'))
      ->groupBy('candidats.id','candidats.nom','candidats.photo','candidats.coalition')
  ->get();

}
public function  rtsByOneCandidat($id){
    return   DB::table('rtslieus')
  ->where("rtslieus.candidat_id",$id)
  ->sum('nbvote');

}
public function  rtsGroupByLieuvVoteByCandidat($id){

    return   DB::table('rtslieus')
    ->join('lieuvotes','rtslieus.lieuvote_id','=','lieuvotes.id')
  ->join('centrevotes','lieuvotes.centrevote_id','=','centrevotes.id')
  ->join('communes','centrevotes.commune_id','=','communes.id')
  ->join('departements','communes.departement_id','=','departements.id')
  ->join('regions','departements.region_id','=','regions.id')
  ->select('centrevotes.nom','communes.nom as commune','departements.nom as departement','regions.nom as region' ,DB::raw('sum(rtslieus.nbvote) as nb'))
  ->groupBy('centrevotes.nom','communes.nom','departements.nom','regions.nom')
  ->where("rtslieus.candidat_id",$id)
->get();

}
public function  rtsGroupByDepartementByCandidat($id){

    return   DB::table('rtslieus')
  ->join('departements','rtslieus.departement_id','=','departements.id')
  ->join('candidats','rtslieus.candidat_id','=','candidats.id')

  ->select('departements.nom as departement'   ,DB::raw('sum(rtslieus.nbvote) as nb'))
  ->groupBy('departements.nom')
  ->where("rtslieus.candidat_id",operator: $id)
->get();

}

public function  rtsGroupByDepartementandCandidat(){

  return   DB::table('rtslieus')
->join('departements','rtslieus.departement_id','=','departements.id')
->join('candidats','rtslieus.candidat_id','=','candidats.id')
->select('departements.nom as departement',"candidats.nom as candidat"   ,DB::raw('sum(rtslieus.nbvote) as nb'))
->groupBy('departements.nom','candidats.nom')
->orderBy("nb","desc")
->get();

}
public function  rtsGroupByRegionByCandidat($id){

    return   DB::table('rtslieus')
  ->join('lieuvotes','rtslieus.lieuvote_id','=','lieuvotes.id')
  ->join('centrevotes','lieuvotes.centrevote_id','=','centrevotes.id')
  ->join('communes','centrevotes.commune_id','=','communes.id')
  ->join('departements','communes.departement_id','=','departements.id')
  ->join('regions','departements.region_id','=','regions.id')
  ->select('regions.nom as region',DB::raw('sum(rtslieus.nbvote) as nb'))
  ->groupBy('regions.nom')
  ->where("rtslieus.candidat_id",$id)
->get();

}

/* public function  rtsGroupByLieuvVoteByCandidat($id){

    return   DB::table('rtslieus')
    ->join('lieuvotes','rtslieus.lieuvote_id','=','lieuvotes.id')
  ->join('centrevotes','lieuvotes.centrevote_id','=','centrevotes.id')
  ->join('communes','centrevotes.commune_id','=','communes.id')
  ->join('departement','communes.departement_id','=','departement.id')
  ->join('pays','departement.region_id','=','pays.id')
  ->select('centrevotes.nom','communes.nom as commune','departement.nom as departement','pays.nom as region'   ,DB::raw('sum(rtslieus.nbvote) as nb'))
  ->groupBy('centrevotes.nom','communes.nom','departement.nom','pays.nom')
  ->where("rtslieus.candidat_id",$id)
->get();

}public function  rtsGroupByLieuvVoteByCandidat($id){

    return   DB::table('rtslieus')
    ->join('lieuvotes','rtslieus.lieuvote_id','=','lieuvotes.id')
  ->join('centrevotes','lieuvotes.centrevote_id','=','centrevotes.id')
  ->join('communes','centrevotes.commune_id','=','communes.id')
  ->join('departement','communes.departement_id','=','departement.id')
  ->join('pays','departement.region_id','=','pays.id')
  ->select('centrevotes.nom','communes.nom as commune','departement.nom as departement','pays.nom as region'   ,DB::raw('sum(rtslieus.nbvote) as nb'))
  ->groupBy('centrevotes.nom','communes.nom','departement.nom','pays.nom')
  ->where("rtslieus.candidat_id",$id)
->get();

} */
}
