<?php

namespace App\Http\Controllers;

use App\Models\Rtscentree;
use App\Models\Rtslieue;
use App\Repositories\CandidatRepository;
use App\Repositories\CentrevoteeRepository;
use App\Repositories\LocaliteRepository;
use App\Repositories\PaysRepository;
use App\Repositories\LieuvoteeRepository;
use App\Repositories\JuridictionRepository;
use App\Repositories\RtscentreeRepository;
use App\Repositories\RtslieueRepository;
use Illuminate\Http\Request;

class RtslieueController extends Controller
{
    protected $rtslieueRepository;
    protected $lieuvoteeRepository;
    protected $centrevoteeRepository;
    protected $candidatRepository;
    protected $juridictionRepository;
protected $paysRepository;
protected $localiteRepository;
protected $rtscentreevoteeRepository;

    public function __construct(RtslieueRepository $rtslieueRepository, LieuvoteeRepository $lieuvoteeRepository,
    CentrevoteeRepository $centrevoteeRepository,CandidatRepository $candidatRepository,JuridictionRepository $juridictionRepository,
    PaysRepository $paysRepository,LocaliteRepository $localiteRepository,RtscentreeRepository $rtscentreevoteeRepository){
        $this->rtslieueRepository =$rtslieueRepository;
        $this->lieuvoteeRepository = $lieuvoteeRepository;
        $this->centrevoteeRepository = $centrevoteeRepository;
        $this->candidatRepository = $candidatRepository;
        $this->juridictionRepository =$juridictionRepository;
        $this->paysRepository=$paysRepository;
        $this->localiteRepository=$localiteRepository;
        $this->centrevoteeRepository =$centrevoteeRepository;
        $this->rtscentreevoteeRepository =$rtscentreevoteeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rtslieues = $this->rtslieueRepository->getAll();
        return view('rtslieue.index',compact('rtslieues'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       // $lieuvotees = $this->lieuvoteeRepository->getAll();
       // $centrevotees = $this->centrevoteeRepository->getAll();
        $candidats = $this->candidatRepository->getAll();
        $juridictions = $this->juridictionRepository->getJuridictionAsc();
        return view('rtslieue.add',compact('candidats',
    "juridictions"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rts = $request["nbvote"];
        $candidats = $request["candidat_id"];
        //VERIER si les resulats ne depasse pas le nombre de perssone inscrit
      $totalRts = 0;
      for ($i= 0; $i < count($rts); $i++) {
        $totalRts = $rts[$i] + $totalRts;

      }
        if($totalRts > $request->nb_electeur)
      {
        return redirect()->back()->withErrors(["erreur"=>"Les resutat ne peuvent être superieur au nombre d'inscrit"]);

      }
      else
      {
        $centrevotee = $this->centrevoteeRepository->getById($request->centrevotee_id);
        for ($i= 0; $i < count($rts); $i++) {
          $rtslieue = new Rtslieue();
          $rtslieue->candidat_id = $candidats[$i];
          $rtslieue->nbvote =(int)$rts[$i];
          $rtslieue->lieuvotee_id = $request["lieuvotee_id"];
          $rtslieue->save();
        }
        $this->lieuvoteeRepository->updateEtat($request["lieuvotee_id"],$request["votant"], $request["bulnull"],$request["hs"]);
        if($centrevotee->niveau==false){
          $rtscentrees = $this->rtscentreevoteeRepository->getByCentre($centrevotee->id);
         
          if(count($rtscentrees) ==0){
            for ($i= 0; $i < count($rts); $i++) {
              $rtscentree = new Rtscentree();
              $rtscentree->candidat_id = $candidats[$i];
              $rtscentree->nbvote =(int)$rts[$i];
              $rtscentree->centrevotee_id = $request["centrevotee_id"];
              $rtscentree->save();
            }
          }else
          {
            for ($i= 0; $i < count($rts); $i++) {
              $rtscentree = $this->rtscentreevoteeRepository->getByCentreAndCandidat($request["centrevotee_id"],$candidats[$i]);
             // dd($rtscentree);
              $rtscentree->nbvote =(int)$rts[$i] + $rtscentree->nbvote ;
              $this->rtscentreevoteeRepository->updateResulat($rtscentree->id,$rtscentree->nbvote);
            }
          }
        }
      

      //  $rtslieues = $this->rtslieueRepository->store($request->all());
        return redirect('rtslieue');
      }

    }
    public function storeApi(Request $request)
    {
       /*  $rts = $request["nbvote"];
        $candidats = $request["candidat_id"];
      for ($i= 0; $i < count($rts); $i++) {
        $rtslieue = new Rtslieue();
        $rtslieue->candidat_id = $candidats[$i];
        $rtslieue->nbvote =(int)$rts[$i];
        $rtslieue->lieuvotee_id = $request["centrevotee_id"];
        $rtslieue->save();
      }*/
      $candidats =  $request["nbVotes"];
    /*   foreach ($candidats as $key => $value) {
        $rtslieue = new Rtslieue();
        $rtslieue->candidat_id = $value[$key]->candidat;
        $rtslieue->nbvote =(int)$value[$key]->nb;
        $rtslieue->lieuvotee_id = $request["lieuvotee_id"];
        $rtslieue->save();
      } */

      foreach ($candidats as $key => $value) {
        $rtslieue = new Rtslieue();
        $rtslieue->candidat_id = $value["candidat_id"];
        $rtslieue->nbvote =(int)$value["nbvote"];
        $rtslieue->lieuvotee_id = $request["lieuvotee_id"];
        $rtslieue->save();
        //$candidat = $value["nbvote"];
      }
      $this->lieuvoteeRepository->updateEtat($request["lieuvotee_id"]);
      //  $rtslieues = $this->rtslieueRepository->store($request->all());
        // return response()->json($candidats[0]->candidat);
        return response()->json("ok");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rtslieue = $this->rtslieueRepository->getById($id);

        return view('rtslieue.show',compact('rtslieue'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lieuvotees = $this->lieuvoteeRepository->getAll();
        $rtslieue = $this->rtslieueRepository->getById($id);
        $centrevotees = $this->centrevoteeRepository->getAll();
        $candidats = $this->candidatRepository->getAll();
        $juridictions = $this->juridictionRepository->getJuridictionAsc();

        return view('rtslieue.edit',compact('rtslieue','lieuvotees','centrevotees'
        ,'candidats','juridictions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->rtslieueRepository->deleteByBureau($request->lieuvotee_id);
        return $this->store($request);
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->rtslieueRepository->destroy($id);
        return redirect('rtslieue');
    }
}
