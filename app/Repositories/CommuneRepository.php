<?php
namespace App\Repositories;

use App\Models\Commune;
use App\Models\Departement;
use App\Repositories\RessourceRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommuneRepository extends RessourceRepository{
    public function __construct(Commune $commune){
        $this->model = $commune;
    }
    public function getAllWithdepartement(){
        return Commune::with(['departement','departement.region'])
        ->get();
    }
    public function getOneCommuneWithdepartementAndRegion($id){
        return Commune::with(['departement','departement.region'])
        ->where('id',$id)
        ->first();
    }
    public function getByDepartement($departement){
        return DB::table("communes")
        ->where("departement_id",$departement)
        ->orderBy("nom","asc")
        ->get();
}
public function getAllOnLy(){
    return DB::table("communes")
    ->orderBy("nom","asc")
    ->get();
}
public function updateEtat($id){
    return DB::table("communes")->where("id",$id)->update(["etat"=>true]);
}

public function getCommuneByNom($nom){
    return DB::table("communes")->where('nom', 'like', '%'.$nom.'%')->get();
} 
public function getByArrondissement($id)
{
    return DB::table("communes")->where("arrondissement_id",$id)->get();
}
public function getOneCommuneWithArrondissementdepartementAndRegion($id){
    return Commune::with(['departement','departement.region','arrondissement'])
    ->where('id',$id)
    ->first();
}
 public function getByArrondissment($id)
 {
    return Commune::with(['centrevotes','centrevotes.lieuvotes','centrevotes.lieuvotes.bureaus'])
    ->where('arrondissement_id',$id)
    ->get();
 }
 public function getByCommune($id)
 {
    return Commune::with(['centrevotes','centrevotes.lieuvotes','centrevotes.lieuvotes.bureaus'])
    ->where('id',$id)
    ->get();
 }
 public function getByDepartements($id)
 {
    return Commune::with(['centrevotes','centrevotes.lieuvotes','centrevotes.lieuvotes.bureaus'])
    ->where('departement_id',$id)
    ->get();
 }
 public function getByRegions($id)
 {
    return Departement::with(['communes','communes.centrevotes','communes.centrevotes.lieuvotes','communes.centrevotes.lieuvotes.bureaus'])
    ->where('region_id',$id)
    ->get();
 }
 
}
