<?php
namespace App\Repositories;

use App\Models\Departement;
use App\Repositories\RessourceRepository;
use Illuminate\Support\Facades\DB;

class DepartementRepository extends RessourceRepository{
    public function __construct(Departement $departement){
        $this->model = $departement;
    }
    public function getAllWithRegion(){
        return Departement::with('region')
        ->get();
    }

    public function getByRegion($region){
        return DB::table("departements")
        ->where("region_id",$region)
        ->orderBy("nom","asc")

        ->get();
}
public function getAllOnLy(){
    return DB::table("departements")
    ->orderBy("nom","asc")
    ->get();
}
public function getByRegionParticipation($region){
    return DB::table("departements")
    ->where("region_id",$region)
    ->orderBy("nom","asc")

    ->get();
}

public function updateEtat($id,$votant,$null,$hb){
    return DB::table("departements")->where("id",$id)->update(["etat"=>true,"total"=>$votant,"null"=>$null,"hb"=>$hb]);
}
public function nbDepartements(){
    return   DB::table('departements')
    ->count();
    //->get();


}
public function nbDepartementBEtat($etat){
    return   DB::table('departements')
    ->where("etat",$etat)
    ->count();
    //->get();


}
public function nbNull(){
    return   Departement::sum('null');
}
public function sumtotalByRegion($id){
    return DB::table("departements")
    ->where("departements.region_id",$id)
    ->sum("total");
}
public function sumNullByRegion($id){
    return DB::table("departements")
    ->where("departements.region_id",$id)
    ->sum("null");
}
public function  nbBulletinNull(){
    return   DB::table('departements')
   ->sum('null');;

}

public function  nbHs(){
    return  DB::table('departements')

  ->sum('hb');

}
public function  nbVotant(){
    return  DB::table('departements')

  ->sum('total');

}

}
