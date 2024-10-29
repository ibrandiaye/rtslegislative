<?php
namespace App\Repositories;

use App\Models\Rtscommune;
use App\Models\Region;
use App\Repositories\RessourceRepository;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\Regex;
use MongoDB\BSON\ObjectId;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Session;
class RtscommuneRepository extends RessourceRepository{
   protected $id;
    public function __construct(Rtscommune $rtscommune){
        $this->model = $rtscommune;
    }
    public function nbRtsCommune(){
        return   DB::table('rtscommunes')
        ->count();
        //->get();


    }
    public function nbVotants(){
        return   Rtscommune::sum('nbvote');


    }
    public function  rtsByCandidat(){

          return   DB::table('rtscommunes')
        ->join('candidats','rtscommunes.candidat_id','=','candidats.id')
        ->select('candidats.id','candidats.nom','candidats.photo','candidats.coalition' ,DB::raw('sum(rtscommunes.nbvote) as nb'))
        ->groupBy('candidats.id','candidats.nom','candidats.photo','candidats.coalition')
    ->get();
   
}

public function rstByRegieon(){
    $region = array();
    $data = Region::with('departements.communes.communes.lieuvotes')->get();
    foreach($data as $d){
         $somme = 0;
         $etat = 0;
        
         foreach($d['departements'] as $c){
          
             foreach($c['communes'] as $ce){
                
                 foreach($ce['communes'] as $li){
                   
                     foreach($li['lieuvotes'] as $rs){
                         $somme = $somme + $rs['nb'];
                         
                        
                     }
                    
                     $etat = $etat + DB::table('rtscommunes')->where('commune_id','=', $li['id'])->sum('nbvote');
                    
                 }
                
             }
         }
        
         array_push($region,['id'=>$d['id'],'nom'=>$d['nom'],'totalAvoter'=>$somme,'totalVoter'=>$etat,'pourcentages'=>round(($etat/$somme)*100,2)]);

    }

  return json_encode($region);

}

public function rstByRegieonId($id){
$data =  DB::table('rtscommunes')
      ->join('candidats','rtscommunes.candidat_id','=','candidats.id')
     ->join('communes','communes.commune_id','=','communes.id')
     ->join('departements','communes.departement_id','=','departements.id')
     ->join('regions','departements.region_id','=','regions.id')
   
     ->select( DB::raw('sum(rtscommunes.nbvote) as nb'),'candidats.nom as nomcandidat','rtscommunes.candidat_id','candidats.photo as photo','regions.nom as region','regions.id as regionId','candidats.id as candidatId')
     ->groupBy('rtscommunes.candidat_id','candidats.nom','candidats.photo','regions.nom','regions.id','candidats.id')
     ->where("regions.id",$id)
     ->get();

     return json_encode($data);

}

public function getByCommune($commune_id){
    return DB::table("rtscommunes")->where("commune_id",$commune_id)->get();
}
public function getByCommuneAndCandidat($commune_id,$candidat_id){
    return DB::table("rtscommunes")->where([["commune_id",$commune_id],["candidat_id",$candidat_id]])->first();

}
public function updateResulat($id,$nbvote){
    return DB::table("rtscommunes")->where("id",$id)->update(["nbvote"=>$nbvote]);
}
public function deleteByCommune($id){
    return DB::table("rtscommunes")->where("commune_id",$id)->delete();
}
public function  rtsByRegionDepartementByCommuneByCandidat($id,$idregion){

    return   DB::table('rtscommunes')
            ->join('communes','communes.commune_id','=','communes.id')
            ->join('departements','communes.departement_id','=','departements.id')
            ->join('regions','departements.region_id','=','regions.id')
            ->select('departements.nom as departement',DB::raw('sum(rtscommunes.nbvote) as nb'))
            ->groupBy('departements.nom')
            ->where("rtscommunes.candidat_id",$id)
            ->where("regions.id",$idregion)
            ->get();

}

public function rtsByRegionComuneByCommuneByCandidat($id,$idregion){
    return   DB::table('rtscommunes')
        ->join('communes','communes.commune_id','=','communes.id')
        ->join('departements','communes.departement_id','=','departements.id')
        ->join('regions','departements.region_id','=','regions.id')
        ->select('departements.nom as departement','communes.nom as commune'   ,DB::raw('sum(rtscommunes.nbvote) as nb'))
        ->groupBy('departements.nom','communes.nom')
        ->where("rtscommunes.candidat_id",$id)
        ->where("regions.id",$idregion)->get();
}
public function  rtsGroupByDepartementByCandidat($id){

    return   DB::table('rtscommunes')
  ->join('communes','communes.commune_id','=','communes.id')
  ->join('departements','communes.departement_id','=','departements.id')
  ->join('pays','departement.juridiction_id','=','pays.id')
  ->select('departements.nom as departement'   ,DB::raw('sum(communes.nbvote) as nb'))
  ->groupBy('departements.nom')
  ->where("rtscommunes.candidat_id",$id)
->get();

}
public function  rtsGroupByCommuneByCandidat($id){

    return   DB::table('rtscommunes')
  ->join('communes','communes.commune_id','=','communes.id')
  ->join('departements','communes.departement_id','=','departements.id')
  ->join('regions','departements.region_id','=','regions.id')
  ->select('communes.nom','communes.nom as commune','departements.nom as departement','regions.nom as region'   ,DB::raw('sum(rtscommunes.nbvote) as nb'))
  ->groupBy('communes.nom','communes.nom','departements.nom','regions.nom')
  ->where("rtscommunes.candidat_id",$id)
->get();

}
public function  rtsByOneCandidat($id){
    return   DB::table('rtscommunes')
  ->where("rtscommunes.candidat_id",$id)
  ->sum('nbvote');

}

public function  rtsGroupByRegionByCandidat($id){

    return   DB::table('rtscommunes')
  ->join('communes','communes.commune_id','=','communes.id')
  ->join('departements','communes.departement_id','=','departements.id')
  ->join('regions','departements.region_id','=','regions.id')
  ->select('regions.nom as region'   ,DB::raw('sum(rtscommunes.nbvote) as nb'))
  ->groupBy('regions.nom')
  ->where("rtscommunes.candidat_id",$id)
->get();

}

public function  rtsGroupByRegionAndCandidat(){

    return  DB::table('rtscommunes')
    ->join('candidats','rtscommunes.candidat_id','=','candidats.id')
  ->join('communes','communes.commune_id','=','communes.id')
  ->join('departements','communes.departement_id','=','departements.id')
  ->join('regions','departements.region_id','=','regions.id')
  ->select('regions.nom as region', 'candidats.nom as candidat' ,DB::raw('sum(rtscommunes.nbvote) as nb'))
  ->groupBy('regions.nom','candidats.nom')
->get(); 

}

}
