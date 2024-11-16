<?php
namespace App\Repositories;

use App\Models\Rtslieue;
use App\Repositories\RessourceRepository;
use Illuminate\Support\Facades\DB;

class RtslieueRepository extends RessourceRepository{
    public function __construct(Rtslieue $rtslieue){
        $this->model = $rtslieue;
    }
    public function nbRtslieue(){
        return   DB::table('rtslieues')
        ->count();
        //->get();


    }

    public function nbVotants(){
        return   Rtslieue::sum('nbvote');


    }
    public function  rtsByCandidat(){

        return   DB::table('rtslieues')
      ->join('candidats','rtslieues.candidat_id','=','candidats.id')
      ->select('candidats.id','candidats.nom','candidats.photo','candidats.coalition' ,DB::raw('sum(rtslieues.nbvote) as nb'))
      ->groupBy('candidats.id','candidats.nom','candidats.photo','candidats.coalition')
  ->get();

}
public function  rtsByOneCandidat($id){
    return   DB::table('rtslieues')
  ->where("rtslieues.candidat_id",$id)
  ->sum('nbvote');

}
public function  rtsGroupByLieuvVoteByCandidat($id){

    return   DB::table('rtslieues')
    ->join('lieuvotees','rtslieues.lieuvotee_id','=','lieuvotees.id')
  ->join('centrevotees','lieuvotees.centrevotee_id','=','centrevotees.id')
  ->join('localites','centrevotees.localite_id','=','localites.id')
  ->join('pays','localites.pays_id','=','pays.id')
  ->join('juridictions','pays.juridiction_id','=','juridictions.id')
  ->select('centrevotees.nom','localites.nom as localite','pays.nom as pays','juridictions.nom as juridiction'   ,DB::raw('sum(rtslieues.nbvote) as nb'))
  ->groupBy('centrevotees.nom','localites.nom','pays.nom','juridictions.nom')
  ->where("rtslieues.candidat_id",$id)
->get();

}
public function  rtsGroupByPaysByCandidat($id){

    return   DB::table('rtslieues')
    ->join('lieuvotees','rtslieues.lieuvotee_id','=','lieuvotees.id')
  ->join('centrevotees','lieuvotees.centrevotee_id','=','centrevotees.id')
  ->join('localites','centrevotees.localite_id','=','localites.id')
  ->join('pays','localites.pays_id','=','pays.id')
  ->join('juridictions','pays.juridiction_id','=','juridictions.id')
  ->select('pays.nom as pays'   ,DB::raw('sum(rtslieues.nbvote) as nb'))
  ->groupBy('pays.nom')
  ->where("rtslieues.candidat_id",$id)
->get();

}
public function  rtsGroupByJuridictionByCandidat($id){

    return   DB::table('rtslieues')
    ->join('lieuvotees','rtslieues.lieuvotee_id','=','lieuvotees.id')
  ->join('centrevotees','lieuvotees.centrevotee_id','=','centrevotees.id')
  ->join('localites','centrevotees.localite_id','=','localites.id')
  ->join('pays','localites.pays_id','=','pays.id')
  ->join('juridictions','pays.juridiction_id','=','juridictions.id')
  ->select('juridictions.nom as juridiction'   ,DB::raw('sum(rtslieues.nbvote) as nb'))
  ->groupBy('juridictions.nom')
  ->where("rtslieues.candidat_id",$id)
->get();

}
public function deleteByBureau($id)
{
  return DB::table("rtslieues")->where("lieuvote_id",$id)->delete();
}

}
