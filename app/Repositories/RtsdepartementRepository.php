<?php
namespace App\Repositories;

use App\Models\Rtsdepartement;
use App\Models\Region;
use App\Repositories\RessourceRepository;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\Regex;
use MongoDB\BSON\ObjectId;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Session;
class RtsdepartementRepository extends RessourceRepository{
   protected $id;
    public function __construct(Rtsdepartement $rtsdepartement){
        $this->model = $rtsdepartement;
    }
    public function nbRtsDepartement(){
        return   DB::table('rtsdepartements')
        ->count();
        //->get();


    }
    public function nbVotants(){
        return   Rtsdepartement::sum('nbvote');


    }
    public function  rtsByCandidat(){

          return   DB::table('rtsdepartements')
        ->join('candidats','rtsdepartements.candidat_id','=','candidats.id')
        ->select('candidats.id','candidats.nom','candidats.photo','candidats.coalition' ,DB::raw('sum(rtsdepartements.nbvote) as nb'))
        ->groupBy('candidats.id','candidats.nom','candidats.photo','candidats.coalition')
        ->orderBy("nb","desc")  
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
$data =  DB::table('rtsdepartements')
      ->join('candidats','rtsdepartements.candidat_id','=','candidats.id')
     ->join('departements','rtsdepartements.departement_id','=','departements.id')
     ->join('regions','departements.region_id','=','regions.id')
   
     ->select( DB::raw('sum(rtsdepartements.nbvote) as nb'),'candidats.nom as nomcandidat','rtsdepartements.candidat_id','candidats.photo as photo','regions.nom as region','regions.id as regionId','candidats.id as candidatId')
     ->groupBy('rtsdepartements.candidat_id','candidats.nom','candidats.photo','regions.nom','regions.id','candidats.id')
     ->where("regions.id",$id)
     ->get();

     return json_encode($data);

}

public function getByDepartement($departement_id){
    return DB::table("rtsdepartements")->where("departement_id",$departement_id)->get();
}
public function getByDepartementAndCandidat($departement_id,$candidat_id){
    return DB::table("rtsdepartements")->where([["departement_id",$departement_id],["candidat_id",$candidat_id]])->first();

}
public function updateResulat($id,$nbvote){
    return DB::table("rtsdepartements")->where("id",$id)->update(["nbvote"=>$nbvote]);
}
public function deleteByDepartement($id){
    return DB::table("rtsdepartements")->where("departement_id",$id)->delete();
}
public function  rtsByRegionDepartementByDepartementByCandidat($id){

    return   DB::table('rtsdepartements')
            ->join('departements','rtsdepartements.departement_id','=','departements.id')
            ->join('regions','departements.region_id','=','regions.id')
            ->select('regions.nom as region','departements.nom as departement',DB::raw('sum(rtsdepartements.nbvote) as nb'))
            ->groupBy('departements.nom',"regions.nom")
            ->where("rtsdepartements.candidat_id",$id)
            
            ->get();

}

public function rtsByRegionComuneByDepartementByCandidat($id,$idregion){
    return   DB::table('rtsdepartements')
        ->join('departements','rtsdepartements.departement_id','=','departements.id')
        ->join('regions','departements.region_id','=','regions.id')
        ->select('departements.nom as departement','departements.nom as departement'   ,DB::raw('sum(rtsdepartements.nbvote) as nb'))
        ->groupBy('departements.nom','departements.nom')
        ->where("rtsdepartements.candidat_id",$id)
        ->where("regions.id",$idregion)->get();
}
public function  rtsGroupByDepartementByCandidat($id){

    return   DB::table('rtsdepartements')
  ->join('departements','rtsdepartements.departement_id','=','departements.id')
  ->join('regions','departements.region_id','=','regions.id')
  ->select('departements.nom','departements.nom as departement','departements.nom as departement','regions.nom as region'   ,DB::raw('sum(rtsdepartements.nbvote) as nb'))
  ->groupBy('departements.nom','departements.nom','departements.nom','regions.nom')
  ->where("rtsdepartements.candidat_id",$id)
->get();

}
public function  rtsByOneCandidat($id){
    return   DB::table('rtsdepartements')
  ->where("rtsdepartements.candidat_id",$id)
  ->sum('nbvote');

}

public function  rtsGroupByRegionByCandidat($id){

    return   DB::table('rtsdepartements')
  ->join('departements','rtsdepartements.departement_id','=','departements.id')
  ->join('regions','departements.region_id','=','regions.id')
  ->select('regions.nom as region'   ,DB::raw('sum(rtsdepartements.nbvote) as nb'))
  ->groupBy('regions.nom')
  ->where("rtsdepartements.candidat_id",$id)
->get();

}
public function  rtsVotantGroupByRegion(){

    return   DB::table('rtsdepartements')
  ->join('departements','rtsdepartements.departement_id','=','departements.id')
  ->join('regions','departements.region_id','=','regions.id')
  ->select('regions.nom as region'   ,DB::raw('sum(rtsdepartements.nbvote) as nb'))
  ->groupBy('regions.nom')
->get();

}

public function  rtsGroupByRegionAndCandidat(){

    return  DB::table('rtsdepartements')
    ->join('candidats','rtsdepartements.candidat_id','=','candidats.id')
  ->join('departements','rtsdepartements.departement_id','=','departements.id')
  ->join('regions','departements.region_id','=','regions.id')
  ->select('regions.nom as region', 'candidats.nom as candidat' ,DB::raw('sum(rtsdepartements.nbvote) as nb'))
  ->groupBy('regions.nom','candidats.nom')
->get(); 

}
public function updateEtat($id){
    return DB::table("departements")->where("id",$id)->update(["etat"=>true]);
}
public function  rtsGroupByDepartementandCandidat(){

    return   DB::table('rtsdepartements')
  ->join('departements','rtsdepartements.departement_id','=','departements.id')
  ->join('candidats','rtsdepartements.candidat_id','=','candidats.id')
  ->select('departements.nom as departement',"candidats.coalition as coalition","candidats.photo"   ,DB::raw('sum(rtsdepartements.nbvote) as nb'))
  ->groupBy('departements.nom','candidats.coalition',"candidats.photo")
  ->orderBy("nb","desc")
  ->get();
  
  }

  public function  rtsGroupByCandidatByDepartement($departement){

    return   DB::table('rtsdepartements')
  ->join('candidats','rtsdepartements.candidat_id','=','candidats.id')
  ->select("candidats.nom as candidat" ,"candidats.photo"  ,DB::raw('sum(rtsdepartements.nbvote) as nb'))
  ->groupBy('candidats.nom',"candidats.photo")
  ->orderBy("nb","desc")
  ->where("rtsdepartements.departement_id",$departement)
  ->get();

  }

 



}