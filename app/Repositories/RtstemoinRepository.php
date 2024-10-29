<?php
namespace App\Repositories;

use App\Models\Rtstemoin;
use App\Repositories\RessourceRepository;
use Illuminate\Support\Facades\DB;

class RtstemoinRepository extends RessourceRepository{
    public function __construct(Rtstemoin $rtstemoin){
        $this->model = $rtstemoin;
    }
    public function nbRtstemoin(){
        return   DB::table('rtstemoins')
        ->count();
        //->get();


    }

    public function nbVotants(){
        return   Rtstemoin::sum('nbvote');


    }
    public function  rtsByCandidat(){

        return   DB::table('rtstemoins')
      ->join('candidats','rtstemoins.candidat_id','=','candidats.id')
      ->select('candidats.id','candidats.nom','candidats.photo','candidats.coalition' ,DB::raw('sum(rtstemoins.nbvote) as nb'))
      ->groupBy('candidats.id','candidats.nom','candidats.photo','candidats.coalition')
  ->get();

}
public function  rtsByOneCandidat($id){
    return   DB::table('rtstemoins')
  ->where("rtstemoins.candidat_id",$id)
  ->sum('nbvote');

}
public function  rtsGroupByLieuvVoteByCandidat($id){

    return   DB::table('rtstemoins')
    ->join('lieuvotes','rtstemoins.lieuvote_id','=','lieuvotes.id')
  ->join('centrevotes','lieuvotes.centrevote_id','=','centrevotes.id')
  ->join('communes','centrevotes.commune_id','=','communes.id')
  ->join('departements','communes.departement_id','=','departements.id')
  ->join('regions','departements.region_id','=','regions.id')
  ->select('centrevotes.nom','communes.nom as commune','departements.nom as departement','regions.nom as region' ,DB::raw('sum(rtstemoins.nbvote) as nb'))
  ->groupBy('centrevotes.nom','communes.nom','departements.nom','regions.nom')
  ->where("rtstemoins.candidat_id",$id)
->get();

}
public function  rtsGroupByDepartementByCandidat($id){

    return   DB::table('rtstemoins')
    ->join('lieuvotes','rtstemoins.lieuvote_id','=','lieuvotes.id')
  ->join('centrevotes','lieuvotes.centrevote_id','=','centrevotes.id')
  ->join('communes','centrevotes.commune_id','=','communes.id')
  ->join('departements','communes.departement_id','=','departements.id')
  ->join('regions','departements.region_id','=','regions.id')
  ->select('departements.nom as departement'   ,DB::raw('sum(rtstemoins.nbvote) as nb'))
  ->groupBy('departements.nom')
  ->where("rtstemoins.candidat_id",$id)
->get();

}
public function  rtsGroupByRegionByCandidat($id){

    return   DB::table('rtstemoins')
  ->join('lieuvotes','rtstemoins.lieuvote_id','=','lieuvotes.id')
  ->join('centrevotes','lieuvotes.centrevote_id','=','centrevotes.id')
  ->join('communes','centrevotes.commune_id','=','communes.id')
  ->join('departements','communes.departement_id','=','departements.id')
  ->join('regions','departements.region_id','=','regions.id')
  ->select('regions.nom as region',DB::raw('sum(rtstemoins.nbvote) as nb'))
  ->groupBy('regions.nom')
  ->where("rtstemoins.candidat_id",$id)
->get();

}

public function  rtsByLieu($id){

  return Rtstemoin::with("candidat")
  ->where("lieuvote_id",$id)
  ->get();
  
/*   DB::table('rtstemoins')
->join('lieuvotes','rtstemoins.lieuvote_id','=','lieuvotes.id')
->join('centrevotes','lieuvotes.centrevote_id','=','centrevotes.id')
->join('communes','centrevotes.commune_id','=','communes.id')
->join('departements','communes.departement_id','=','departements.id')
->join('regions','departements.region_id','=','regions.id')
->select('regions.nom as region',DB::raw('sum(rtstemoins.nbvote) as nb'))
->groupBy('regions.nom')
->where("rtstemoins.candidat_id",$id)
->get(); */

}

}
