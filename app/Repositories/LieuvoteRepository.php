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

    public function nbLieuVoteByEtatAndDepartement($etat,$departement){
        return   DB::table('lieuvotes')
        ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
        ->join("communes","centrevotes.commune_id","=","communes.id")
        ->where("lieuvotes.etat",$etat)
        ->where("communes.departement_id",$departement)
        ->count();
        //->get();


    }
   
    public function nbLieuVoteTemoinByEtat($etat){
        return   DB::table('lieuvotes')
        ->where("etat",$etat)
        ->where("temoin",1)
        ->count();
        //->get();


    }
    public function nbLieuVoteTemoinByEtatAndDepartement($etat,$departement){
        return   DB::table('lieuvotes')
        ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
        ->join("communes","centrevotes.commune_id","=","communes.id")
        ->where("lieuvotes.etat",$etat)
        ->where("temoin",1)
        ->where("communes.departement_id",$departement)
        ->count();
        //->get();


    }
    public function nbElecteurs(){
        return   Lieuvote::sum('nb');

    }

    public function nbElecteursTemoin(){
        return   Lieuvote::where("temoin",true)->sum('nb');

    }
    public function nbElecteursTemoinByRegion($region){
        return  DB::table("lieuvotes")
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->join("communes","centrevotes.commune_id","=","communes.id")
    ->join("departements","communes.departement_id","=","departements.id")
    ->where([["temoin",true],["departements.region_id",$region]])
    ->sum('nb');

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
        ->where([["centrevote_id",$centre],["etat",false],["temoin",false]])
        ->orderBy("nom","asc")
        ->get();
}

public function getByLieuvoteTemoin($centre){
    return DB::table("lieuvotes")
    ->where([["centrevote_id",$centre],["etat",false],["temoin",true]])
    ->orderBy("nom","asc")
    ->get();
}
public function getByLieuvoteTemoinParticipation($centre){
    return DB::table("lieuvotes")
    ->where([["centrevote_id",$centre],["temoin",true]])
    ->orderBy("nom","asc")
    ->get();
}
public function updateEtat($id, $votant,$bulnull,$hs){
    return DB::table("lieuvotes")->where("id",$id)->update(["etat"=>1,"votant"=>$votant,"bulnull"=>$bulnull,"hs"=>$hs]);
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

public function sumByDepartementsTemoin($departements){
    return DB::table("lieuvotes")
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->join("communes","centrevotes.commune_id","=","communes.id")
    ->where([["communes.departement_id",$departements],["temoin",1]])
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



public function  nbBulletinNullByDepartement($departement){
    return   DB::table('lieuvotes')
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
->join("communes","centrevotes.commune_id","=","communes.id")
    ->where("communes.departement_id",$departement)
  ->sum('bulnull');

}

public function  nbHsByDepartement($departement){
    return   DB::table('lieuvotes')
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
->join("communes","centrevotes.commune_id","=","communes.id")
  ->where("communes.departement_id",$departement)
  ->sum('hs');

}

public function  nbBulletinNullByDepartementTemoin($departement){
    return   DB::table('lieuvotes')
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
->join("communes","centrevotes.commune_id","=","communes.id")
    ->where([["communes.departement_id",$departement],["temoin",1]])
  ->sum('bulnull');

}

public function  nbHsByDepartementTemoin($departement){
    return   DB::table('lieuvotes')
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->join("communes","centrevotes.commune_id","=","communes.id")
    ->where([["communes.departement_id",$departement],["temoin",1]])
  ->sum('hs');

}


public function  nbVotantByDepartement($departement){
    return   DB::table('lieuvotes')
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
->join("communes","centrevotes.commune_id","=","communes.id")
  ->where("communes.departement_id",$departement)
  ->sum('votant');

}
public function  nbVotantByDepartementTemoin($departement){
    return   DB::table('lieuvotes')
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
->join("communes","centrevotes.commune_id","=","communes.id")
  ->where([["communes.departement_id",$departement],["temoin",1]])
  ->sum('votant');

}

public function  nbBulletinNull(){
    return   DB::table('lieuvotes')
   ->sum('bulnull');;

}

public function  nbHs(){
    return  DB::table('lieuvotes')

  ->sum('hs');

}
public function  nbVotant(){
    return  DB::table('lieuvotes')

  ->sum('votant');

}

public function  nbBulletinNullTemoin(){
    return   DB::table('lieuvotes')
    ->where("temoin",1)
   ->sum('bulnull');;

}

public function  nbHsTemoin(){
    return  DB::table('lieuvotes')
    ->where("temoin",1)
  ->sum('hs');

}
public function  nbVotantTemoin(){
    return  DB::table('lieuvotes')
    ->where("temoin",1)
  ->sum('votant');

}

public function getByDepartement($departements){
    return DB::table("lieuvotes")
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->join("communes","centrevotes.commune_id","=","communes.id")
    ->select("communes.nom as commune","centrevotes.nom as centre","lieuvotes.nom as  bureau","lieuvotes.nb","lieuvotes.etat",
    "communes.id as commune_id","lieuvotes.id as lieuvote_id","centrevotes.id as centrevote_id")
    ->where([["communes.departement_id",$departements],["lieuvotes.etat",1]])
    ->get();
}
public function  getOnlyById($id){
    return  DB::table('lieuvotes')
   ->find($id);

}

public function search(){
    return DB::table("lieuvotes")
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->join("communes","centrevotes.commune_id","=","communes.id")
    ->select("communes.nom as commune","centrevotes.nom as centre","lieuvotes.nom as  bureau","lieuvotes.nb","lieuvotes.etat",
    "communes.id as commune_id","lieuvotes.id as lieuvote_id","centrevotes.id as centrevote_id")
    ->where("lieuvotes.etat",1)
   ;
}

public function searchNational(){
    return DB::table("lieuvotes")
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->join("communes","centrevotes.commune_id","=","communes.id")
    ->join("departements","communes.departement_id","=","departements.id")
    ->select("communes.nom as commune","centrevotes.nom as centre","lieuvotes.nom as  bureau","lieuvotes.nb","lieuvotes.etat",
    "communes.id as commune_id","lieuvotes.id as lieuvote_id","centrevotes.id as centrevote_id","lieuvotes.temoin")
    //->where("lieuvotes.etat",1)
   ;
}

public function mettreBureauTemoin($id)
{
    return DB::table("lieuvotes")->where("id",$id)->update(["temoin"=>1]);
}

public function enleverBureauTemoin($id)
{
    return DB::table("lieuvotes")->where("id",$id)->update(["temoin"=>0]);
}


    public function nbBureauTemoinByEtat($etat)
    {
        return DB::table("lieuvotes")->where("temoin",1)->where("etat",$etat)->count();
    }
    public function nbBureauTemoin()
    {
        return DB::table("lieuvotes")->where("temoin",1)->count();
    }

    public function nbElecteursTemoinByDepartementGroupByCommune($departement){
        return  DB::table("lieuvotes")
    ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
    ->join("communes","centrevotes.commune_id","=","communes.id")
    ->select("communes.id","communes.nom",DB::raw('sum(lieuvotes.nb) as nb'))
    ->where("communes.departement_id",$departement)
    ->groupBy("communes.id","communes.nom")
    ->get();

    }
}
