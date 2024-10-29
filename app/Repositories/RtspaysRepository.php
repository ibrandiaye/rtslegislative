<?php
namespace App\Repositories;

use App\Models\Rtspays;
use App\Repositories\RessourceRepository;
use Illuminate\Support\Facades\DB;

class RtspaysRepository extends RessourceRepository{
   protected $id;
    public function __construct(Rtspays $rtspays){
        $this->model = $rtspays;
    }
    public function nbRtspays(){
        return   DB::table('rtspays')
        ->count();
        //->get();


    }
    
    public function nbVotants(){
        return   Rtspays::sum('nbvote');


    }
    public function  rtsByCandidat(){

          return   DB::table('rtspays')
        ->join('candidats','rtspays.candidat_id','=','candidats.id')
        ->select('candidats.id','candidats.nom','candidats.photo','candidats.coalition' ,DB::raw('sum(rtspays.nbvote) as nb'))
        ->groupBy('candidats.id','candidats.nom','candidats.photo','candidats.coalition')
    ->get();
   /*  $manager = DB::connection()->getMongoDB()->getManager();

    $session = $manager->startSession();

  //  $collection = $manager->getCollection('rtspays');
$result = Rtspays::raw(function ($collection) use ($manager, $session)  {
    $pipeline = [
        [
            '$lookup' => [
                'from' => 'candidats',
                'localField' => 'id',
                'foreignField' => 'candidat_id',
                'as' => 'candidat'
            ]
        ],
        [
            '$unwind' => '$candidat'
        ],

        [
            '$group' => [
                '_id' => '$candidat.nom',
                'candidat_id' => ['$first' => '$candidat_id'],
                'votes' => ['$sum' => '$nbvote']
            ]
        ],

    ];

    $result = $collection->aggregate($pipeline, [
        'session' => $session
    ]);

    return $result;

});
return $result;
 */
}
public function getByPays($pays_id){
    return DB::table("rtspays")->where("pays_id",$pays_id)->get();
}
public function getByCentreAndCandidat($centrevotee_id,$candidat_id){
    return DB::table("rtspays")->where([["centrevotee_id",$centrevotee_id],["candidat_id",$candidat_id]])->first();

}
public function updateResulat($id,$nbvote){
    return DB::table("rtspays")->where("id",$id)->update(["nbvote"=>$nbvote]);
}
public function deleteByPays($id){
    return DB::table("rtspays")->where("pays_id",$id)->delete();
}

public function updateEtat($id){
    return DB::table("rtspays")->where("id",$id)->update(["etat"=>true]);
}


}
