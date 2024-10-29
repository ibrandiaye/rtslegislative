<?php
namespace App\Repositories;

use App\Models\Rtscentree;
use App\Repositories\RessourceRepository;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\Regex;
use MongoDB\BSON\ObjectId;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Session;
class RtscentreeRepository extends RessourceRepository{
   protected $id;
    public function __construct(Rtscentree $rtscentree){
        $this->model = $rtscentree;
    }
    public function nbRtscentree(){
        return   DB::table('rtscentrees')
        ->count();
        //->get();


    }
    public function nbVotants(){
        return   Rtscentree::sum('nbvote');


    }
    public function  rtsByCandidat(){

          return   DB::table('rtscentrees')
        ->join('candidats','rtscentrees.candidat_id','=','candidats.id')
        ->select('candidats.id','candidats.nom','candidats.photo','candidats.coalition' ,DB::raw('sum(rtscentrees.nbvote) as nb'))
        ->groupBy('candidats.id','candidats.nom','candidats.photo','candidats.coalition')
    ->get();
   /*  $manager = DB::connection()->getMongoDB()->getManager();

    $session = $manager->startSession();

  //  $collection = $manager->getCollection('rtscentrees');
$result = Rtscentree::raw(function ($collection) use ($manager, $session)  {
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
public function getByCentre($centrevotee_id){
    return DB::table("rtscentrees")->where("centrevotee_id",$centrevotee_id)->get();
}
public function getByCentreAndCandidat($centrevotee_id,$candidat_id){
    return DB::table("rtscentrees")->where([["centrevotee_id",$centrevotee_id],["candidat_id",$candidat_id]])->first();

}
public function updateResulat($id,$nbvote){
    return DB::table("rtscentrees")->where("id",$id)->update(["nbvote"=>$nbvote]);
}
public function deleteByCentre($id){
    return DB::table("rtscentrees")->where("centrevotee_id",$id)->delete();
}

}
