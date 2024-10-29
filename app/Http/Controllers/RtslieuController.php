<?php

namespace App\Http\Controllers;

use App\Models\Rtscentre;
use App\Models\Rtslieu;
use App\Repositories\CandidatRepository;
use App\Repositories\CentrevoteRepository;
use App\Repositories\CommuneRepository;
use App\Repositories\DepartementRepository;
use App\Repositories\LieuvoteRepository;
use App\Repositories\RegionRepository;
use App\Repositories\RtscentreRepository;
use App\Repositories\RtslieuRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RtslieuController extends Controller
{
    protected $rtslieuRepository;
    protected $lieuvoteRepository;
    protected $centrevoteRepository;
    protected $candidatRepository;
    protected $regionRepository;
protected $departementRepository;
protected $communeRepository;
protected $rtsCentrevoteRepository;

    public function __construct(RtslieuRepository $rtslieuRepository, LieuvoteRepository $lieuvoteRepository,
    CentrevoteRepository $centrevoteRepository,CandidatRepository $candidatRepository,RegionRepository $regionRepository,
    DepartementRepository $departementRepository,CommuneRepository $communeRepository,RtscentreRepository $rtsCentrevoteRepository){
        $this->rtslieuRepository =$rtslieuRepository;
        $this->lieuvoteRepository = $lieuvoteRepository;
        $this->centrevoteRepository = $centrevoteRepository;
        $this->candidatRepository = $candidatRepository;
        $this->regionRepository =$regionRepository;
        $this->departementRepository=$departementRepository;
        $this->communeRepository=$communeRepository;
        $this->centrevoteRepository =$centrevoteRepository;
        $this->rtsCentrevoteRepository =$rtsCentrevoteRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rtslieus = $this->rtslieuRepository->getAll();
        return view('rtslieu.index',compact('rtslieus'));
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
        return view('rtslieu.add',compact('candidats',
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
          $rtslieu = new Rtslieu();
          $rtslieu->candidat_id = $candidats[$i];
          $rtslieu->nbvote =(int)$rts[$i];
          $rtslieu->lieuvote_id = $request["lieuvote_id"];
          $rtslieu->votant = $request["votant"];
          $rtslieu->bulnull = $request["bulnull"];
          $rtslieu->hs = $request["hs"];
          $rtslieu->departement_id = $request["departement_id"];
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


      //  $rtslieus = $this->rtslieuRepository->store($request->all());
      //  return redirect('rtslieu');
      return redirect()->back()->with( "success","enregistrement avec succès");

      }

    }
    public function storeApi(Request $request)
    {
       /*  $rts = $request["nbvote"];
        $candidats = $request["candidat_id"];
      for ($i= 0; $i < count($rts); $i++) {
        $rtslieu = new Rtslieu();
        $rtslieu->candidat_id = $candidats[$i];
        $rtslieu->nbvote =(int)$rts[$i];
        $rtslieu->lieuvote_id = $request["centrevote_id"];
        $rtslieu->save();
      }*/
      $candidats =  $request["nbVotes"];
    /*   foreach ($candidats as $key => $value) {
        $rtslieu = new Rtslieu();
        $rtslieu->candidat_id = $value[$key]->candidat;
        $rtslieu->nbvote =(int)$value[$key]->nb;
        $rtslieu->lieuvote_id = $request["lieuvote_id"];
        $rtslieu->save();
      } */

      foreach ($candidats as $key => $value) {
        $rtslieu = new Rtslieu();
        $rtslieu->candidat_id = $value["candidat_id"];
        $rtslieu->nbvote =(int)$value["nbvote"];
        $rtslieu->lieuvote_id = $request["lieuvote_id"];
        $rtslieu->save();
        //$candidat = $value["nbvote"];
      }
      $this->lieuvoteRepository->updateEtat($request["lieuvote_id"]);
      //  $rtslieus = $this->rtslieuRepository->store($request->all());
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
        $rtslieu = $this->rtslieuRepository->getById($id);

        return view('rtslieu.show',compact('rtslieu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lieuvotes = $this->lieuvoteRepository->getAll();
        $rtslieu = $this->rtslieuRepository->getById($id);
        $centrevotes = $this->centrevoteRepository->getAll();
        $candidats = $this->candidatRepository->getAll();
        $regions = $this->regionRepository->getRegionAsc();

        return view('rtslieu.edit',compact('rtslieu','lieuvotes','centrevotes'
        ,'candidats','regions'));
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

        $this->rtslieuRepository->update($id, $request->all());
        return redirect('rtslieu');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->rtslieuRepository->destroy($id);
        return redirect('rtslieu');
    }
    public function rtsDepartement(Request $request)
    {
      $rts = $this->rtslieuRepository->rtsGroupByCandidatByDepartement($request->departement_id);
      //$departements= $this->departementRepository->getAll();
      $departement = DB::table("departements")->where("id",$request->departement_id)->first();
      $departement_id = $request->departement_id;
      $region_id = $request->region_id;
      $regions  = $this->regionRepository->getAll();
      $departements = $this->departementRepository->getByRegion($request->region_id);
      $candidat = DB::table("candidats")->first();

      $bullnull  = $this->rtslieuRepository->nbBulletinNullByDepartement($candidat->id,$departement_id);
      $hs  = $this->rtslieuRepository->nbHsByDepartement($candidat->id,$departement_id);
      $inscrit = $this->lieuvoteRepository->countByDepartement($departement_id);
      return view("rtslieu.rtsdepartement",compact("region_id","departement_id","departements","regions","rts",
    "bullnull","hs"));
     // dd($rts,$departement);
    }

    public function resultatParDepartement()
    {
        $region_id = "";
        $departement_id = "";
        $departements = [];
        $rts = [];
        $regions  = $this->regionRepository->getAll();
        return view("rtslieu.rtsdepartement",compact("region_id","departement_id","departements","regions","rts"));
    }
}
