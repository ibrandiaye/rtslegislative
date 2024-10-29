<?php
namespace App\Repositories;

use App\Models\Bureau;
use App\Repositories\RessourceRepository;
use Auth;
use Illuminate\Support\Facades\DB;

class BureauRepository extends RessourceRepository{
    public function __construct(Bureau $bureau){
        $this->model = $bureau;
    }

    public function getByUser()
    {
        return DB::table("bureaus")
        ->join("communes","bureaus.commune_id","=","communes.id")
        ->join("lieuvotes","bureaus.lieuvote_id","=","lieuvotes.id")
        ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
        ->select("bureaus.*","communes.nom as commune","lieuvotes.nom as lieuvote","centrevotes.nom as centrevote")
        ->where("communes.arrondissement_id",Auth::user()->arrondissement_id)
        ->get();
    }

    public function getTel($tel)
    {
        return DB::table("bureaus")
        ->join("communes","bureaus.commune_id","=","communes.id")
        ->join("lieuvotes","bureaus.lieuvote_id","=","lieuvotes.id")
        ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
        ->select("bureaus.*","communes.nom as commune","lieuvotes.nom as lieuvote","centrevotes.nom as centrevote")
        ->where("bureaus.tel",$tel)
        ->get();
    }

    public function getByLieuVote($id)
    {
        return DB::table("bureaus")
        ->join("communes","bureaus.commune_id","=","communes.id")
        ->join("lieuvotes","bureaus.lieuvote_id","=","lieuvotes.id")
        ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
        ->select("bureaus.*","communes.nom as commune","lieuvotes.nom as lieuvote","centrevotes.nom as centrevote")
        ->where("lieuvotes.id",$id)
        ->get();
    }
    public function getByLieuVoteOnly($id)
    {
        return DB::table("bureaus")
      
        ->where("lieuvote_id",$id)
        ->get();
    }
    public function getByBureauVote($id)
    {
        return DB::table("bureaus")
        ->join("communes","bureaus.commune_id","=","communes.id")
        ->join("lieuvotes","bureaus.lieuvote_id","=","lieuvotes.id")
        ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
        ->select("bureaus.*","communes.nom as commune","lieuvotes.nom as lieuvote","centrevotes.nom as centrevote","communes.arrondissement_id as arrondissement_id")
        ->where("bureaus.lieuvote_id",$id)
        ->get();
    }

    public function getByCentreVote($id)
    {
        return DB::table("bureaus")
        ->join("communes","bureaus.commune_id","=","communes.id")
        ->join("lieuvotes","bureaus.lieuvote_id","=","lieuvotes.id")
        ->join("centrevotes","lieuvotes.centrevote_id","=","centrevotes.id")
        ->select("bureaus.*","communes.nom as commune","lieuvotes.nom as lieuvote","centrevotes.nom as centrevote")
        ->where("lieuvotes.centrevote_id",$id)
        ->get();
    }

    public function destroyByLieuVote($id)
    {
       return DB::table("bureaus")->where("lieuvote_id",$id)->delete();
    }
}
