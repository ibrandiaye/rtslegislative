<?php
namespace App\Repositories;

use App\Models\Pays;
use App\Models\Rtspays;
use App\Repositories\RessourceRepository;
use Illuminate\Support\Facades\DB;

class PaysRepository extends RessourceRepository{
    public function __construct(Pays $pays){
        $this->model = $pays;
    }
    public function getAllWithJuridiction(){
        return Pays::with('juridiction')
        ->get();
    }

    public function getByJuridiction($juridiction){
        return DB::table("pays")
        ->where("juridiction_id",$juridiction)
        ->orderBy("nom","asc")

        ->get();
}

public function getAllOnLy(){
    return DB::table("pays")
    ->orderBy("nom","asc")
    ->get();
}

public function updateEtat($id,$votant,$null,$hb){
return DB::table("pays")->where("id",$id)->update(["etat"=>true,"total"=>$votant,"null"=>$null,"hb"=>$hb]);
}

public function nbNull(){
return   Pays::sum('null');
}
}
