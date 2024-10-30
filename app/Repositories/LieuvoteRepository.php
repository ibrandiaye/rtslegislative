<?php
namespace App\Repositories;

use App\Models\Lieuvote;
use App\Repositories\RessourceRepository;
use Illuminate\Support\Facades\DB;

class LieuvoteRepository extends RessourceRepository{
    public function __construct(Lieuvote $lieuvote){
        $this->model = $lieuvote;
    }
    public function nbLieuVote(){
        return   DB::table('lieuvotes')
        ->count();
        //->get();


    }
    public function nbLieuVoteByEtat($etat){
        return   DB::table('lieuvotes')
        ->where("etat",$etat)
        ->count();
        //->get();


    }
    public function nbElecteurs(){
        return   Lieuvote::sum('nb');

    }

    public function nbElecteursTemoin(){
        return   Lieuvote::where("temoin",true)->sum('nb');

    }

    public function nbElecteursTemoinByDepartement($departement){
        return  DB::table("lieuvotes")
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->join("communes","centrevotes.commune_id","=","communes.id")
    ->join("departements","communes.departement_id","=","departements.id")
    ->where([["temoin",true],["departements.id",$departement]])
    ->sum('nb');

    }
    public function getAllLieuVote(){
        return Lieuvote::with(['centrevote','centrevote.commune'])
        ->get();
    }
    public function getByCentre($centre){
        return DB::table("lieuvotes")
        ->where([["centrevote_id",$centre],["etat",false]])
        ->orderBy("nom","asc")
        ->get();
}
public function getByLieuvoteTemoin($centre){
    return DB::table("lieuvotes")
    ->where([["centrevote_id",$centre],["etat",false],["temoin",true]])
    ->orderBy("nom","asc")
    ->get();
}
public function updateEtat($id){
    return DB::table("lieuvotes")->where("id",$id)->update(["etat"=>1]);
}
public function getAllOnly(){
    return DB::table("lieuvotes")->get();
}

public function sumByRegion($region){
    return DB::table("lieuvotes")
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->join("communes","centrevotes.commune_id","=","communes.id")
    ->join("departements","communes.departement_id","=","departements.id")
    ->join("regions","departements.region_id","=","regions.id")
    ->where("regions.id",$region)
    ->sum("lieuvotes.nb");;
}
public function sumByDepartements($departements){
    return DB::table("lieuvotes")
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->join("communes","centrevotes.commune_id","=","communes.id")
    ->where("communes.departement_id",$departements)
    ->sum("lieuvotes.nb");;
}

public function sumByCommune($communes){
    return DB::table("lieuvotes")
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->join("communes","centrevotes.commune_id","=","communes.id")
    ->where("communes.id",$communes)
    ->sum("lieuvotes.nb");;
}
public function sumByArrondissement($arrondissement){
    return DB::table("lieuvotes")
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->join("communes","centrevotes.commune_id","=","communes.id")
    ->where("communes.arrondissement_id",$arrondissement)
    ->sum("lieuvotes.nb");;
}
public function sumElecteurByCentrevote($centrevote){
    return DB::table("lieuvotes")
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->where("centrevotees.id",$centrevote)
    ->sum("lieuvotes.nb");
}
public function getByCommuneAndCentreVote(){
    return DB::table("lieuvotes")
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->join("communes","centrevotes.commune_id","=","communes.id")
    ->select("communes.nom as commune","centrevotes.nom as centre","lieuvotes.id")
    ->get();
}
public function getBureauTemoin(){
    return DB::table("lieuvotes")
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->join("communes","centrevotes.commune_id","=","communes.id")
    ->join("departements","communes.departement_id","=","departements.id")
    ->join("regions","departements.region_id","=","regions.id")
    ->select("communes.nom as commune","departements.nom as departement","regions.nom as region","centrevotes.nom as centre","lieuvotes.nom as  bureau","lieuvotes.nb")
    ->where("temoin",true)
    ->get();
}

public function getAllWithLocalite(){
    return DB::table("lieuvotes")
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->join("communes","centrevotes.commune_id","=","communes.id")
    ->join("departements","communes.departement_id","=","departements.id")
    ->join("regions","departements.region_id","=","regions.id")
    ->select("communes.nom as commune","departements.nom as departement","regions.nom as region","centrevotes.nom as centre","lieuvotes.nom as  bureau","lieuvotes.nb","lieuvotes.etat")
    ->get();
}
public function sommeByCommune($id){
    return DB::table("lieuvotes")
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->join("communes","centrevotes.commune_id","=","communes.id")
    ->where("communes.id",$id)
    ->sum("lieuvotes.nb");
}

public function sommeByDepartement($id){
    return DB::table("lieuvotes")
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->join("communes","centrevotes.commune_id","=","communes.id")
    ->join("departements","communes.departement_id","=","departements.id")
    ->where("departements.id",$id)
    ->sum("lieuvotes.nb");
}
public function getByCentreVote($id)
{
    return Lieuvote::with(["centrevote","centrevote.commune","bureaus"])
    ->where("centrevote_id",$id)
    ->get();
}

public function countByArrondissementt($id){

    return DB::table("lieuvotes")
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->join("communes","centrevotes.commune_id","=","communes.id")
    ->where("communes.arrondissement_id",$id)
    ->count();
}
public function countByCommune($id){

    return DB::table("lieuvotes")
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->join("communes","centrevotes.commune_id","=","communes.id")
    ->where("communes.id",$id)
    ->count();
}

public function countByDepartement($id){

    return DB::table("lieuvotes")
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->join("communes","centrevotes.commune_id","=","communes.id")
    ->where("communes.departement_id",$id)
    ->count();
}

public function countByRegion($id){

    return DB::table("lieuvotes")
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->join("communes","centrevotes.commune_id","=","communes.id")
    ->join("departements","communes.departement_id","=","departements.id")
    ->where("departements.region_id",$id)
    ->count();
}
public function getAllWithBureau(){
    return Lieuvote::with(["bureaus"])
    ->get();
}

public function nbElecteursByDepartement($departement){
    return  DB::table("lieuvotes")
->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
->join("communes","centrevotes.commune_id","=","communes.id")
->where("communes.departement_id",$departement)
->sum('nb');

}

}
