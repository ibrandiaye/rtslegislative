<?php
namespace App\Repositories;

use App\Models\Centrevotee;
use App\Repositories\RessourceRepository;
use Illuminate\Support\Facades\DB;

class CentrevoteeRepository extends RessourceRepository{
    public function __construct(Centrevotee $centrevotee){
        $this->model = $centrevotee;
    }
    public function nbCentrevotee(){
        return   DB::table('centrevotees')
        ->count();
        //->get();


    }
    public function getAllCentre(){
        return Centrevotee::with('localite')
        ->get();
    }
    public function getByLocalite($localite){
        return DB::table("centrevotees")
        ->where("localite_id",$localite)
        ->orderBy("nom","asc")
        ->get();
}
public function getAllOnly(){
    return DB::table("centrevotees")->get();
}

public function countJuridiction($juridiction){
    return DB::table("centrevotees")
    ->join("localites","centrevotees.localite_id","=","localites.id")
    ->join("pays","localites.pays_id","=","pays.id")
    ->join("juridictions","pays.juridiction_id","=","juridictions.id")
    ->where("juridictions.id",$juridiction)
    ->count();
}

public function countByPays($pays){
    return DB::table("centrevotees")
    ->join("localites","centrevotees.localite_id","=","localites.id")
    ->join("pays","localites.pays_id","=","pays.id")
    ->where("pays.id",$pays)
    ->count();
}
public function countByLocalite($localite){
    return DB::table("centrevotees")
    ->join("localites","centrevotees.localite_id","=","localites.id")
    ->where("localites.id",$localite)
    ->count();
}
public function updateNiveau($id){
    return DB::table("centrevotees")->where("id",$id)->update(["niveau"=>true]);
}

public function sumElecteurByCentree($id){
    return DB::table("centrevotees")
    ->join("lieuvotees","centrevotees.id","=","lieuvotees.centree_id")
    ->where("centrevotees.id",$id)
    ->sum("lieuvotees.nb");
}
}
