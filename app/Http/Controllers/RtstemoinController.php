<?php

namespace App\Http\Controllers;

use App\Models\Rtscentre;
use App\Models\Rtslieu;
use App\Models\Rtstemoin;
use App\Repositories\CandidatRepository;
use App\Repositories\CentrevoteRepository;
use App\Repositories\CommuneRepository;
use App\Repositories\DepartementRepository;
use App\Repositories\LieuvoteeRepository;
use App\Repositories\LieuvoteRepository;
use App\Repositories\RegionRepository;
use App\Repositories\RtscentreRepository;
use App\Repositories\RtslieuRepository;
use App\Repositories\RtstemoinRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RtstemoinController extends Controller
{
    protected $rtstemoinRepository;
    protected $lieuvoteRepository;
    protected $centrevoteRepository;
    protected $candidatRepository;
    protected $regionRepository;
protected $departementRepository;
protected $communeRepository;
protected $rtsCentrevoteRepository;
protected $rtslieuvoteRepository;


    public function __construct(RtstemoinRepository $rtstemoinRepository, LieuvoteRepository $lieuvoteRepository,
    CentrevoteRepository $centrevoteRepository,CandidatRepository $candidatRepository,RegionRepository $regionRepository,
    DepartementRepository $departementRepository,CommuneRepository $communeRepository,RtscentreRepository $rtsCentrevoteRepository,
    RtslieuRepository $rtslieuRepository){
        $this->rtstemoinRepository =$rtstemoinRepository;
        $this->lieuvoteRepository = $lieuvoteRepository;
        $this->centrevoteRepository = $centrevoteRepository;
        $this->candidatRepository = $candidatRepository;
        $this->regionRepository =$regionRepository;
        $this->departementRepository=$departementRepository;
        $this->communeRepository=$communeRepository;
        $this->centrevoteRepository =$centrevoteRepository;
        $this->rtsCentrevoteRepository =$rtsCentrevoteRepository;
        $this->rtsLieuRepository =$rtslieuRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rtstemoins = $this->rtstemoinRepository->getAll();
        return view('rtstemoin.index',compact('rtstemoins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       // $lieuvotes = $this->lieuvoteRepository->getAll();
       // $centrevotes = $this->centrevoteRepository->getAll();
        $candidats = $this->candidatRepository->getAll();
        $regions = $this->regionRepository->getRegionAsc();
        return view('rtstemoin.add',compact('candidats',
    "regions"));
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

        $centreVote = $this->centrevoteRepository->getById($request->centrevote_id);
       
        for ($i= 0; $i < count($rts); $i++) {
          $rtstemoin = new Rtstemoin();
          $rtstemoin->candidat_id = $candidats[$i];
          $rtstemoin->nbvote =(int)$rts[$i];
          $rtstemoin->lieuvote_id = $request["lieuvote_id"];
          $rtstemoin->save();
          $rtslieu = new Rtslieu();
          $rtslieu->candidat_id = $candidats[$i];
          $rtslieu->nbvote =(int)$rts[$i];
          $rtslieu->lieuvote_id = $request["lieuvote_id"];
          $rtslieu->save();
        }
        $this->lieuvoteRepository->updateEtat($request["lieuvote_id"]);
        if($centreVote->niveau==false){
          $rtsCentres = $this->rtsCentrevoteRepository->getByCentre($centreVote->id);
         
          if(count($rtsCentres) ==0){
            for ($i= 0; $i < count($rts); $i++) {
              $rtscentre = new Rtscentre();
              $rtscentre->candidat_id = $candidats[$i];
              $rtscentre->nbvote =(int)$rts[$i];
              $rtscentre->centrevote_id = $request["centrevote_id"];
              $rtscentre->save();
            }
          }else
          {
            for ($i= 0; $i < count($rts); $i++) {
              $rtscentre = $this->rtsCentrevoteRepository->getByCentreAndCandidat($request["centrevote_id"],$candidats[$i]);
             // dd($rtscentre);
              $rtscentre->nbvote =(int)$rts[$i] + $rtscentre->nbvote ;
              $this->rtsCentrevoteRepository->updateResulat($rtscentre->id,$rtscentre->nbvote);
            }
          }
        }
      

      //  $rtstemoins = $this->rtstemoinRepository->store($request->all());
        return redirect('rtstemoin');
      }

    }
    public function storeApi(Request $request)
    {
       /*  $rts = $request["nbvote"];
        $candidats = $request["candidat_id"];
      for ($i= 0; $i < count($rts); $i++) {
        $rtstemoin = new Rtstemoin();
        $rtstemoin->candidat_id = $candidats[$i];
        $rtstemoin->nbvote =(int)$rts[$i];
        $rtstemoin->lieuvote_id = $request["centrevote_id"];
        $rtstemoin->save();
      }*/
      $candidats =  $request["nbVotes"];
    /*   foreach ($candidats as $key => $value) {
        $rtstemoin = new Rtstemoin();
        $rtstemoin->candidat_id = $value[$key]->candidat;
        $rtstemoin->nbvote =(int)$value[$key]->nb;
        $rtstemoin->lieuvote_id = $request["lieuvote_id"];
        $rtstemoin->save();
      } */

      foreach ($candidats as $key => $value) {
        $rtstemoin = new Rtstemoin();
        $rtstemoin->candidat_id = $value["candidat_id"];
        $rtstemoin->nbvote =(int)$value["nbvote"];
        $rtstemoin->lieuvote_id = $request["lieuvote_id"];
        $rtstemoin->save();
        //$candidat = $value["nbvote"];
      }
      $this->lieuvoteRepository->updateEtat($request["lieuvote_id"]);
      //  $rtstemoins = $this->rtstemoinRepository->store($request->all());
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
        $rtstemoin = $this->rtstemoinRepository->getById($id);

        return view('rtstemoin.show',compact('rtstemoin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $rtstemoin = $this->rtstemoinRepository->getById($id);
         /*  $lieuvotes = $this->lieuvoteRepository->getAll();
      $rtstemoin = $this->rtstemoinRepository->getById($id);
        $centrevotes = $this->centrevoteRepository->getAll();
       
        $regions = $this->regionRepository->getRegionAsc(); */
        $candidats = $this->candidatRepository->getAll();
        $lieuvote = $this->lieuvoteRepository->getById($rtstemoin->lieuvote_id);
        $rtstemoins = $this->rtstemoinRepository->rtsByLieu($rtstemoin->lieuvote_id);
        $centrevote = $this->centrevoteRepository->getById($lieuvote->centrevote_id);
        $regions = $this->regionRepository->getAll();
        $departements = $this->departementRepository->getAll();
        $communes = $this->communeRepository->getAll();
        $centrevotes = $this->centrevoteRepository->getCentreTemoin($centrevote->commune_id);

        return view('rtstemoin.edit',compact('rtstemoin','lieuvote','centrevotes','departements'
        ,'candidats','regions',"communes","rtstemoins"));
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

      DB::table("rtstemoins")->where("lieuvote_id",$request->lieuvote_id)->delete();
      //VERIER si les resulats ne depasse pas le nombre de perssone inscrit
      $totalRts = 0;
      $rts = $request["nbvote"];
      $candidats = $request["candidat_id"];
     /* 
      for ($i= 0; $i < count($rts); $i++) {
        $totalRts = $rts[$i] + $totalRts;

      }
      dd($request->$request->nb_electeur);
        if($totalRts > $request->nb_electeur)
      {
        return redirect()->back()->withErrors(["erreur"=>"Les resutat ne peuvent être superieur au nombre d'inscrit"]);

      }
      else
      {
        dd($rts);
 */
        for ($i= 0; $i < count($rts); $i++) {

          $rtstemoin = new Rtstemoin();
          $rtstemoin->candidat_id = $candidats[$i];
          $rtstemoin->nbvote =(int)$rts[$i];
          $rtstemoin->lieuvote_id = $request["lieuvote_id"];
          $rtstemoin->save();
        
        }
        return redirect('rtstemoin');

     // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->rtstemoinRepository->destroy($id);
        return redirect('rtstemoin');
    }
    public function getCentreTemoin($id)
    {
        $centres = $this->centrevoteRepository->getCentreTemoin($id);
        return response()->json($centres);
    }
    public function getByLieuvoteTemoin($id)
    {
        $centres = $this->lieuvoteRepository->getByLieuvoteTemoin($id);
        return response()->json($centres);
    }
}
