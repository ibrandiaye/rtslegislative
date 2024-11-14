<?php
namespace App\Repositories;

use App\Models\Rtscentre;
use App\Models\Region;
use App\Repositories\RessourceRepository;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\Regex;
use MongoDB\BSON\ObjectId;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Session;
class RtscentreRepository extends RessourceRepository{
   protected $id;
    public function __construct(Rtscentre $rtscentre){
        $this->model = $rtscentre;
    }
    public function nbRtsCentre(){
        return   DB::table('rtscentres')
        ->count();
        //->get();


    }
    public function nbVotants(){
        return   Rtscentre::sum('nbvote');


    }
    public function  rtsByCandidat(){

          return   DB::table('rtscentres')
        ->join('candidats','rtscentres.candidat_id','=','candidats.id')
        ->select('candidats.id','candidats.nom','candidats.photo','candidats.coalition' ,DB::raw('sum(rtscentres.nbvote) as nb'))
        ->groupBy('candidats.id','candidats.nom','candidats.photo','candidats.coalition')
    ->get();

}

public function rstByRegieon(){
    $region = array();
    $data = Region::with('departements.communes.centrevotes.lieuvotes')->get();
    foreach($data as $d){
         $somme = 0;
         $etat = 0;

         foreach($d['departements'] as $c){

             foreach($c['communes'] as $ce){

                 foreach($ce['centrevotes'] as $li){

                     foreach($li['lieuvotes'] as $rs){
                         $somme = $somme + $rs['nb'];


                     }

                     $etat = $etat + DB::table('rtscentres')->where('centrevote_id','=', $li['id'])->sum('nbvote');

                 }

             }
         }

         array_push($region,['id'=>$d['id'],'nom'=>$d['nom'],'totalAvoter'=>$somme,'totalVoter'=>$etat,'pourcentages'=>round(($etat/$somme)*100,2)]);

    }

  return json_encode($region);

}

public function rstByRegieonId($id){
$data =  DB::table('rtscentres')
      ->join('candidats','rtscentres.candidat_id','=','candidats.id')
     ->join('centrevotes','rtscentres.centrevote_id','=','centrevotes.id')
     ->join('communes','centrevotes.commune_id','=','communes.id')
     ->join('departements','communes.departement_id','=','departements.id')
     ->join('regions','departements.region_id','=','regions.id')

     ->select( DB::raw('sum(rtscentres.nbvote) as nb'),'candidats.nom as nomcandidat','rtscentres.candidat_id','candidats.photo as photo','regions.nom as region','regions.id as regionId','candidats.id as candidatId')
     ->groupBy('rtscentres.candidat_id','candidats.nom','candidats.photo','regions.nom','regions.id','candidats.id')
     ->where("regions.id",$id)
     ->get();

     return json_encode($data);

}

public function getByCentre($centrevote_id){
    return DB::table("rtscentres")->where("centrevote_id",$centrevote_id)->get();
}
public function getByCentreAndCandidat($centrevote_id,$candidat_id){
    return DB::table("rtscentres")->where([["centrevote_id",$centrevote_id],["candidat_id",$candidat_id]])->first();

}
public function updateResulat($id,$nbvote){
    return DB::table("rtscentres")->where("id",$id)->update(["nbvote"=>$nbvote]);
}
public function deleteByCentre($id){
    return DB::table("rtscentres")->where("centrevote_id",$id)->delete();
}
public function  rtsByRegionDepartementByCentreByCandidat($id,$idregion){

    return   DB::table('rtscentres')
            ->join('centrevotes','rtscentres.centrevote_id','=','centrevotes.id')
            ->join('communes','centrevotes.commune_id','=','communes.id')
            ->join('departements','communes.departement_id','=','departements.id')
            ->join('regions','departements.region_id','=','regions.id')
            ->select('departements.nom as departement',DB::raw('sum(rtscentres.nbvote) as nb'))
            ->groupBy('departements.nom')
            ->where("rtscentres.candidat_id",$id)
            ->where("regions.id",$idregion)
            ->get();

}

public function rtsByRegionComuneByCentreByCandidat($id,$idregion){
    return   DB::table('rtscentres')
        ->join('centrevotes','rtscentres.centrevote_id','=','centrevotes.id')
        ->join('communes','centrevotes.commune_id','=','communes.id')
        ->join('departements','communes.departement_id','=','departements.id')
        ->join('regions','departements.region_id','=','regions.id')
        ->select('departements.nom as departement','communes.nom as commune'   ,DB::raw('sum(rtscentres.nbvote) as nb'))
        ->groupBy('departements.nom','communes.nom')
        ->where("rtscentres.candidat_id",$id)
        ->where("regions.id",$idregion)->get();
}
public function  rtsGroupByDepartementByCandidat($id){

    return   DB::table('rtscentres')
    ->join('centrevotes','rtslieus.centrevote_id','=','centrevotes.id')
  ->join('communes','centrevotes.commune_id','=','communes.id')
  ->join('departements','communes.departement_id','=','departements.id')
  ->join('pays','departement.juridiction_id','=','pays.id')
  ->select('departements.nom as departement'   ,DB::raw('sum(centrevotes.nbvote) as nb'))
  ->groupBy('departements.nom')
  ->where("rtscentres.candidat_id",$id)
->get();

}
public function  rtsGroupByCentreByCandidat($id){

    return   DB::table('rtscentres')
    ->join('centrevotes','rtscentres.centrevote_id','=','centrevotes.id')
  ->join('communes','centrevotes.commune_id','=','communes.id')
  ->join('departements','communes.departement_id','=','departements.id')
  ->join('regions','departements.region_id','=','regions.id')
  ->select('centrevotes.nom','communes.nom as commune','departements.nom as departement','regions.nom as region'   ,DB::raw('sum(rtscentres.nbvote) as nb'))
  ->groupBy('centrevotes.nom','communes.nom','departements.nom','regions.nom')
  ->where("rtscentres.candidat_id",$id)
->get();

}
public function  rtsGroupByRegion(){

    return   DB::table('rtsdepartements')
  ->join('departements','rtsdepartements.departement_id','=','departements.id')
  ->join('regions','departements.region_id','=','regions.id')
  ->select('regions.nom as region'   ,DB::raw('sum(rtsdepartements.nbvote) as nb'))
  ->groupBy('regions.nom')

->get();

}

public function  rtsByOneCandidat($id){
    return   DB::table('rtscentres')
  ->where("rtscentres.candidat_id",$id)
  ->sum('nbvote');

}

public function  rtsGroupByRegionByCandidat($id){

    return   DB::table('rtscentres')
  ->join('centrevotes','rtscentres.centrevote_id','=','centrevotes.id')
  ->join('communes','centrevotes.commune_id','=','communes.id')
  ->join('departements','communes.departement_id','=','departements.id')
  ->join('regions','departements.region_id','=','regions.id')
  ->select('regions.nom as region'   ,DB::raw('sum(rtscentres.nbvote) as nb'))
  ->groupBy('regions.nom')
  ->where("rtscentres.candidat_id",$id)
->get();

}

public function  rtsGroupByRegionAndCandidat(){

    return  DB::table('rtsdepartements')
    ->join('candidats','rtsdepartements.candidat_id','=','candidats.id')
  ->join('departements','rtsdepartements.departement_id','=','departements.id')
  ->join('regions','departements.region_id','=','regions.id')
  ->select('regions.nom as region', 'candidats.nom as candidat' ,DB::raw('sum(rtsdepartements.nbvote) as nb'))
  ->groupBy('candidats.nom','regions.nom')
->get();

}

public function  rtsGroupByDepartementandCandidat(){

    return   DB::table('rtscentres')
    ->join('centrevotes','rtscentres.centrevote_id','=','centrevotes.id')
    ->join('communes','centrevotes.commune_id','=','communes.id')
    ->join('departements','communes.departement_id','=','departements.id')
  ->join('candidats','rtscentres.candidat_id','=','candidats.id')
  ->select('departements.nom as departement',"candidats.coalition as coalition","candidats.photo"   ,DB::raw('sum(rtscentres.nbvote) as nb'))
  ->groupBy('departements.nom','candidats.coalition',"candidats.photo")
  ->orderBy("nb","desc")
  ->get();

  }

}
