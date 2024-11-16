<?php
namespace App\Repositories;

use App\Models\Lieuvotee;
use App\Repositories\RessourceRepository;
use Illuminate\Support\Facades\DB;

class LieuvoteeRepository extends RessourceRepository{
    public function __construct(Lieuvotee $lieuvotee){
        $this->model = $lieuvotee;
    }
    public function nbLieuvotee(){
        return   DB::table('lieuvotees')
        ->count();
        //->get();


    }
    public function nbLieuvoteeByEtat($etat){
        return   DB::table('lieuvotees')
        ->where("etat",$etat)
        ->count();
        //->get();


    }
    public function nbElecteurs(){
        return   Lieuvotee::sum('nb');


    }
    public function getAllLieuvotee(){
        return Lieuvotee::with('centrevotee')
        ->get();
    }
    public function getByCentre($centre){
        return DB::table("lieuvotees")
        ->where([["centrevotee_id",$centre],["etat",0]])
        ->orderBy("nom","asc")
        ->get();
}
public function updateEtat($id, $votant,$bulnull,$hs){
    return DB::table("lieuvotees")->where("id",$id)->update(["etat"=>1,"votant"=>$votant,"bulnull"=>$bulnull,"hs"=>$hs]);
}

public function getAllOnly(){
    return DB::table("lieuvotees")->get();
}

public function getByJuridiction($juridiction){
    return DB::table("lieuvotees")
    ->join("centrevotees","lieuvotees.centrevotee_id","=","centrevotees.id")
    ->join("localites","centrevotees.localite_id","=","localites.id")
    ->join("pays","localites.pays_id","=","pays.id")
    ->join("juridictions","pays.juridiction_id","=","juridictions.id")
    ->select("pays.nom as pays","juridictions.nom as juridiction","localites.nom as localite",
    "centrevotees.nom as centrevote","lieuvotees.nom as lieuvote","lieuvotees.nb as electeurs")
    ->where("juridictions.id",$juridiction)
    ->get();
}
public function countByJuridiction($juridiction){
    return DB::table("lieuvotees")
    ->join("centrevotees","lieuvotees.centrevotee_id","=","centrevotees.id")
    ->join("localites","centrevotees.localite_id","=","localites.id")
    ->join("pays","localites.pays_id","=","pays.id")
    ->join("juridictions","pays.juridiction_id","=","juridictions.id")
    ->where("juridictions.id",$juridiction)
    ->count();
}

public function sumByJuridiction($juridiction){
    return DB::table("lieuvotees")
    ->join("centrevotees","lieuvotees.centrevotee_id","=","centrevotees.id")
    ->join("localites","centrevotees.localite_id","=","localites.id")
    ->join("pays","localites.pays_id","=","pays.id")
    ->join("juridictions","pays.juridiction_id","=","juridictions.id")
    ->where("juridictions.id",$juridiction)
    ->sum("lieuvotees.nb");;
}

public function getByPays($pays){
    return DB::table("lieuvotees")
    ->join("centrevotees","lieuvotees.centrevotee_id","=","centrevotees.id")
    ->join("localites","centrevotees.localite_id","=","localites.id")
    ->join("pays","localites.pays_id","=","pays.id")
    ->join("juridictions","pays.juridiction_id","=","juridictions.id")
    ->select("pays.nom as pays","juridictions.nom as juridiction","localites.nom as localite",
    "centrevotees.nom as centrevote","lieuvotees.nom as lieuvote","lieuvotees.nb as electeurs")
    ->where("pays.id",$pays)
    ->get();
}

public function countByPays($pays){
    return DB::table("lieuvotees")
    ->join("centrevotees","lieuvotees.centrevotee_id","=","centrevotees.id")
    ->join("localites","centrevotees.localite_id","=","localites.id")
    ->join("pays","localites.pays_id","=","pays.id")
    ->where("pays.id",$pays)
    ->count();
}

public function sumByPays($pays){
    return DB::table("lieuvotees")
    ->join("centrevotees","lieuvotees.centrevotee_id","=","centrevotees.id")
    ->join("localites","centrevotees.localite_id","=","localites.id")
    ->join("pays","localites.pays_id","=","pays.id")
    ->where("pays.id",$pays)
    ->sum("lieuvotees.nb");;
}
public function getByLocalite($localite){
    return DB::table("lieuvotees")
    ->join("centrevotees","lieuvotees.centrevotee_id","=","centrevotees.id")
    ->join("localites","centrevotees.localite_id","=","localites.id")
    ->join("pays","localites.pays_id","=","pays.id")
    ->join("juridictions","pays.juridiction_id","=","juridictions.id")
    ->select("pays.nom as pays","juridictions.nom as juridiction","localites.nom as localite",
    "centrevotees.nom as centrevote","lieuvotees.nom as lieuvote","lieuvotees.nb as electeurs")
    ->where("localites.id",$localite)
    ->get();
}

public function countByLocalite($localite){
    return DB::table("lieuvotees")
    ->join("centrevotees","lieuvotees.centrevotee_id","=","centrevotees.id")
    ->join("localites","centrevotees.localite_id","=","localites.id")
    ->where("localites.id",$localite)
    ->count();
}

public function sumByLocalite($localites){
    return DB::table("lieuvotees")
    ->join("centrevotees","lieuvotees.centrevotee_id","=","centrevotees.id")
    ->join("localites","centrevotees.localite_id","=","localites.id")
    ->where("localites.id",$localites)
    ->sum("lieuvotees.nb");;
}
public function countByCentrevote($centrevote){
    return DB::table("lieuvotees")
    ->join("centrevotees","lieuvotees.centrevotee_id","=","centrevotees.id")
    ->where("centrevotees.id",$centrevote)
    ->count();
}
public function sumElecteurByCentrevote($centrevote){
    return DB::table("lieuvotees")
    ->join("centrevotees","lieuvotees.centrevotee_id","=","centrevotees.id")
    ->where("centrevotees.id",$centrevote)
    ->sum("lieuvotees.nb");
}

public function sumElecteurByLieudevote($lieuvote){
    return DB::table("lieuvotees")
    ->where("lieuvotees.id",$lieuvote)
    ->sum("lieuvotees.nb");
}

}
