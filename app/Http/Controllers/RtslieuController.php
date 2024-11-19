<?php

namespace App\Http\Controllers;

use App\Exports\RtsDepartemmentExport;
use App\Models\Rtscentre;
use App\Models\Rtslieu;
use App\Repositories\ArrondissementRepository;
use App\Repositories\CandidatRepository;
use App\Repositories\CentrevoteRepository;
use App\Repositories\CommuneRepository;
use App\Repositories\DepartementRepository;
use App\Repositories\LieuvoteRepository;
use App\Repositories\ParticipationRepository;
use App\Repositories\RegionRepository;
use App\Repositories\RtscentreRepository;
use App\Repositories\RtslieuRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Excel;
use Spatie\SimpleExcel\SimpleExcelReader;


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
protected $arrondissementRepository;
protected $participationRepository;

    public function __construct(RtslieuRepository $rtslieuRepository, LieuvoteRepository $lieuvoteRepository,
    CentrevoteRepository $centrevoteRepository,CandidatRepository $candidatRepository,RegionRepository $regionRepository,
    DepartementRepository $departementRepository,CommuneRepository $communeRepository,RtscentreRepository $rtsCentrevoteRepository,
    ArrondissementRepository $arrondissementRepository,ParticipationRepository $participationRepository){
        $this->rtslieuRepository =$rtslieuRepository;
        $this->lieuvoteRepository = $lieuvoteRepository;
        $this->centrevoteRepository = $centrevoteRepository;
        $this->candidatRepository = $candidatRepository;
        $this->regionRepository =$regionRepository;
        $this->departementRepository=$departementRepository;
        $this->communeRepository=$communeRepository;
        $this->centrevoteRepository =$centrevoteRepository;
        $this->rtsCentrevoteRepository =$rtsCentrevoteRepository;
        $this->arrondissementRepository = $arrondissementRepository;
        $this->participationRepository  = $participationRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rtslieus = $this->rtslieuRepository->getPaginate(500);
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
       $user = Auth::user();

       if($user->role=="admin" || $user->role=="superviseur")
       {
        $region_id              ="";
        $departement_id         = "";
        $arrondissement_id      = "";
        $commune_id             = "";
        $centrevote_id          = "";
        $lieuvote_id            = "";

        $regions = $this->regionRepository->getRegionAsc();
        $departements = [];
        $arrondissements =[];
        $communes = [];
        $centreVotes =[];
        $lieuVotes  =[];
        return view('rtslieu.add',compact('candidats',"regions","region_id","departement_id","arrondissement_id","commune_id","centrevote_id",
    "lieuvote_id","regions","departements","arrondissements","communes","centreVotes","lieuVotes"));

       }
       else if($user->role=="prefet")
       {

        $arrondissement_id      = "";
        $commune_id             = "";
        $centrevote_id          = "";
        $lieuvote_id            = "";
        $communes = [];
        $centreVotes =[];
        $lieuVotes  =[];
        $arrondissements = $this->arrondissementRepository->getByDepartement($user->departement_id);
        return view('rtslieu.addprefet',compact('candidats',"arrondissements","arrondissement_id","commune_id","centrevote_id",
        "lieuvote_id","communes","centreVotes","lieuVotes"));
       }
       else if($user->role=="sous_prefet")
       {

        $commune_id             = "";
        $centrevote_id          = "";
        $lieuvote_id            = "";
        $communes = [];
        $centreVotes =[];
        $lieuVotes  =[];
        $communes = $this->communeRepository->getByArrondissement($user->arrondissement_id);
        return view('rtslieu.add_sousprefet',compact('candidats',"communes","centrevote_id",
        "lieuvote_id","centreVotes","lieuVotes"));
       }

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
     // dd($request["nbvote"]);
      $candidats = $request["candidat_id"];
      //VERIER si les resulats ne depasse pas le nombre de perssone inscrit
      $totalRts = 0;
      for ($i= 0; $i < count($rts); $i++) {
        $totalRts = $rts[$i] + $totalRts;

      }
      //dd($totalRts);
      if($totalRts <1)
      {
        return redirect()->back()->withErrors(["erreur"=>"Toutes les résultats ne peuvent être egale à zéro"]);

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
          $rtslieu->departement_id = $request["departement_id"];
          $rtslieu->save();
        }
        $rtslieu->votant = $request["votant"];

        $this->lieuvoteRepository->updateEtat($request["lieuvote_id"],$request["votant"], $request["bulnull"],$request["hs"]);
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
        $user = Auth::user();
        $candidats = $this->candidatRepository->getAll();
        if($user->role=="admin")
       {
        $region_id              = $request["region_id"];
        $departement_id         = $request["departement_id"];
        $arrondissement_id      = $request["arrondissement_id"];
        $commune_id             = $request["commune_id"];
        $centrevote_id          = $request["centrevote_id"];
        $lieuvote_id            = $request["lieuvote_id"];

        $regions = $this->regionRepository->getRegionAsc();
        $departements = $this->departementRepository->getByRegion($region_id);
        $arrondissements = $this->arrondissementRepository->getByDepartement($departement_id);
        $communes = $this->communeRepository->getByArrondissement($arrondissement_id);
        $centreVotes = $this->centrevoteRepository->getByCommune($commune_id);
        $lieuVotes  = $this->lieuvoteRepository->getByCentre($centrevote_id);
        return view('rtslieu.add',compact('candidats',"regions","region_id","departement_id","arrondissement_id","commune_id","centrevote_id",
    "lieuvote_id","regions","departements","arrondissements","communes","centreVotes","lieuVotes"))->with( "success","enregistrement avec succès");
       }
       else if($user->role=="prefet")
       {
        $arrondissement_id      = $request["arrondissement_id"];
        $commune_id             = $request["commune_id"];
        $centrevote_id          = $request["centrevote_id"];
        $lieuvote_id            = $request["lieuvote_id"];
        $arrondissements = $this->arrondissementRepository->getByDepartement($user->departement_id);
        $communes = $this->communeRepository->getByArrondissement($arrondissement_id);
        $centreVotes = $this->centrevoteRepository->getByCommune($commune_id);
        $lieuVotes  = $this->lieuvoteRepository->getByCentre($centrevote_id);
        return view('rtslieu.addprefet',compact('candidats',"arrondissements","arrondissement_id","commune_id","centrevote_id",
        "lieuvote_id","communes","centreVotes","lieuVotes"))->with( "success","enregistrement avec succès");
       }
       else if($user->role=="sous_prefet")
       {
        $commune_id             = $request["commune_id"];
        $centrevote_id          = $request["centrevote_id"];
        $lieuvote_id            = $request["lieuvote_id"];
        $communes = $this->communeRepository->getByArrondissement($user->arrondissement_id);
        $centreVotes = $this->centrevoteRepository->getByCommune($commune_id);
        $lieuVotes  = $this->lieuvoteRepository->getByCentre($centrevote_id);
        return view('rtslieu.add_sousprefet',compact('candidats',"communes","centrevote_id",
        "lieuvote_id","centreVotes","lieuVotes"))->with( "success","enregistrement avec succès");
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
    public function showEdit($centrevoteId,$lieuvoteId,$communeId)
    {
      $user = Auth::user();
        $lieuVote = $this->lieuvoteRepository->getOnlyById($lieuvoteId);
        $rtslieus = $this->rtslieuRepository->getByCandidatAndLieuvote($lieuvoteId);
        $commune = $this->communeRepository->getOnlyById($communeId);
        $arrondissement = $this->arrondissementRepository->getOneOnly($commune->arrondissement_id);
        $centreVote  = $this->centrevoteRepository->getOneOnly($lieuVote->centrevote_id);
     // dd($commune);



     //   dd($lieuvote);
        return view('rtslieu.edit',compact("centreVote","commune","commune","lieuVote","arrondissement","rtslieus"));
    }

    public function updatePerso(Request $request)
    {
      $rts = $request["nbvote"];
      $nbvoteold = $request["nbvoteold"];
     // dd($request["nbvote"]);
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
      $this->rtslieuRepository->deleteByBureau($request->lieuvote_id);

        $centreVote = $this->centrevoteRepository->getById($request->centrevote_id);

        for ($i= 0; $i < count($rts); $i++) {
          $rtslieu = new Rtslieu();
          $rtslieu->candidat_id = $candidats[$i];
          $rtslieu->nbvote =(int)$rts[$i];
          $rtslieu->lieuvote_id = $request["lieuvote_id"];
          $rtslieu->departement_id = $request["departement_id"];
          $rtslieu->save();
        }
        $rtslieu->votant = $request["votant"];

        $this->lieuvoteRepository->updateEtat($request["lieuvote_id"],$request["votant"], $request["bulnull"],$request["hs"]);
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
              $rtscentre->nbvote =(int)$rts[$i] - (int)$nbvoteold[$i] + $rtscentre->nbvote ;
              $this->rtsCentrevoteRepository->updateResulat($rtscentre->id,$rtscentre->nbvote);
            }
          }
        }
        $user = Auth::user();
        $candidats = $this->candidatRepository->getAll();
        if($user->role=="admin")
       {
        return redirect("bureau/by/national")->with( "success","Modification avec succès");
       }
       else if($user->role=="prefet")
       {
        return redirect("bureau/by/departement")->with( "success","Modification avec succès");
       }
      /* else if($user->role=="sous_prefet")
       {

       }*/



      //  $rtslieus = $this->rtslieuRepository->store($request->all());
      //  return redirect('rtslieu');
      return redirect()->back()->with( "success","enregistrement avec succès");

      }
       // $this->rtslieuRepository->deleteByBureau($request->lieuvote_id);
       // $this->rtslieuRepository->update($id, $request->all());
      //  return $this->store($request);
        //return redirect('rtslieu');
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
      $rts = $request["nbvote"];
      $nbvoteold = $request["nbvoteold"];
     // dd($request["nbvote"]);
      $candidats = $request["candidat_id"];
      //VERIER si les resulats ne depasse pas le nombre de perssone inscrit
      $totalRts = 0;
      for ($i= 0; $i < count($rts); $i++) {
        $totalRts = $rts[$i] + $totalRts;

      }
       /* if($totalRts > $request->nb_electeur)
      {
        return redirect()->back()->withErrors(["erreur"=>"Les resutat ne peuvent être superieur au nombre d'inscrit"]);

      }
      else
      {*/
      $this->rtslieuRepository->deleteByBureau($request->lieuvote_id);

        $centreVote = $this->centrevoteRepository->getById($request->centrevote_id);

        for ($i= 0; $i < count($rts); $i++) {
          $rtslieu = new Rtslieu();
          $rtslieu->candidat_id = $candidats[$i];
          $rtslieu->nbvote =(int)$rts[$i];
          $rtslieu->lieuvote_id = $request["lieuvote_id"];
          $rtslieu->departement_id = $request["departement_id"];
          $rtslieu->save();
        }
        $rtslieu->votant = $request["votant"];

        $this->lieuvoteRepository->updateEtat($request["lieuvote_id"],$request["votant"], $request["bulnull"],$request["hs"]);
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
              $rtscentre->nbvote =(int)$rts[$i] - (int)$nbvoteold[$i] + $rtscentre->nbvote ;
              $this->rtsCentrevoteRepository->updateResulat($rtscentre->id,$rtscentre->nbvote);
            }
          }
        }
        $user = Auth::user();
        $candidats = $this->candidatRepository->getAll();
        if($user->role=="admin")
       {
        return redirect("")->with( "success","Modification avec succès");
       }
       else if($user->role=="prefet")
       {
        return redirect("")->with( "success","Modification avec succès");
       }
      /* else if($user->role=="sous_prefet")
       {

       }*/



      //  $rtslieus = $this->rtslieuRepository->store($request->all());
      //  return redirect('rtslieu');
      return redirect()->back()->with( "success","enregistrement avec succès");

     // }

        //$this->rtslieuRepository->deleteByBureau($request->lieuvote_id);

       // $this->rtslieuRepository->update($id, $request->all());
        //return $this->store($request);
        //return redirect('rtslieu');
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
        $votant  = $this->rtslieuRepository->nbVoixByDepartement($departement_id);
        $bullnull  = $this->lieuvoteRepository->nbBulletinNullByDepartement($departement_id);
        $hs  = $this->lieuvoteRepository->nbHsByDepartement($departement_id);
        $inscrit = $this->lieuvoteRepository->sumByDepartements($departement_id);
        // dd($rts,$departement);
        $depouillement= [];

        $depouillement[] = $this->lieuvoteRepository->nbLieuVoteByEtatAndDepartement(1,$departement_id) ?? 0;
        $depouillement[] = $this->lieuvoteRepository->nbLieuVoteByEtatAndDepartement(0,$departement_id) ?? 0;
     // dd($rts);
        return view("rtslieu.rtsdepartement",compact("region_id","departement_id","departements","regions","rts",
        "bullnull","hs","votant","inscrit","departement","depouillement","rts"));
        // dd($rts,$departement);
    }

    public function resultatParDepartement()
    {
        $region_id = "";
        $departement_id = "";
        $departements = [];
        $departement = null;
        $rts = [];
        $inscrit = null;
        $votant = null;
        $bullnull = null;
        $hs = null;
        $candidat = null;
        $regions  = $this->regionRepository->getAll();
        $depouillement= [];

        $depouillement[] =  0;
        $depouillement[] =  0;

        return view("rtslieu.rtsdepartement",compact("region_id","departement_id","departements","regions","rts",
            "bullnull","hs","votant","inscrit","departement","depouillement"));
  }


    //temoin

    public function rtsDepartementTemoin(Request $request)
    {
        $rts = $this->rtslieuRepository->rtsGroupByCandidatByDepartementTemoin($request->departement_id);
     // dd($rts);
        //$departements= $this->departementRepository->getAll();
        $departement = DB::table("departements")->where("id",$request->departement_id)->first();
        $departement_id = $request->departement_id;
        $region_id = $request->region_id;
        $regions  = $this->regionRepository->getAll();
        $departements = $this->departementRepository->getByRegion($request->region_id);
        $candidat = DB::table("candidats")->first();
        $votant  = $this->lieuvoteRepository->nbVotantByDepartementTemoin($departement_id);
       // dd($votant);
        $bullnull  = $this->lieuvoteRepository->nbBulletinNullByDepartementTemoin($departement_id);
        $hs  = $this->lieuvoteRepository->nbHsByDepartementTemoin($departement_id);
        $inscrit = $this->lieuvoteRepository->sumByDepartementsTemoin($departement_id);
        // dd($rts,$departement);
        $tauxDeParticipations = $this->participationRepository->getParticipationGroupByHeureByDepartement($request->departement_id);
        $depouillement= [];

        $depouillement[] = $this->lieuvoteRepository->nbLieuVoteTemoinByEtatAndDepartement(1,$departement_id) ?? 0;
        $depouillement[] = $this->lieuvoteRepository->nbLieuVoteTemoinByEtatAndDepartement(0,$departement_id) ?? 0;


        return view("rtslieu.rtsdepartementtemoin",compact("region_id","departement_id","departements","regions","rts",
        "bullnull","hs","votant","inscrit","departement","tauxDeParticipations","depouillement","rts"));
        // dd($rts,$departement);
    }

    public function rtsDepartementTemoinPrefet()
    {
        $user = Auth::user();
        $rts = $this->rtslieuRepository->rtsGroupByCandidatByDepartementTemoin($user->departement_id);
     // dd($rts);
        //$departements= $this->departementRepository->getAll();
        $departement = DB::table("departements")->where("id",$user->departement_id)->first();


        $candidat = DB::table("candidats")->first();
        $votant  = $this->lieuvoteRepository->nbVotantByDepartementTemoin($user->departement_id);
       // dd($votant);
        $bullnull  = $this->lieuvoteRepository->nbBulletinNullByDepartementTemoin($user->departement_id);
        $hs  = $this->lieuvoteRepository->nbHsByDepartementTemoin($user->departement_id);
        $inscrit = $this->lieuvoteRepository->sumByDepartementsTemoin($user->departement_id);
        // dd($rts,$departement)
        $tauxDeParticipations = $this->participationRepository->getParticipationGroupByHeureByDepartement($user->departement_id);
        $depouillement= [];

        $depouillement[] = $this->lieuvoteRepository->nbLieuVoteTemoinByEtatAndDepartement(1,$user->departement_id) ?? 0;
        $depouillement[] = $this->lieuvoteRepository->nbLieuVoteTemoinByEtatAndDepartement(0,$user->departement_id) ?? 0;
       //// $nbElecteursTemoin = $this->lieuvoteRepository->nbElecteursTemoinByDepartement($user->departement_id);
       // dd($inscrit,$tauxDeParticipations);
        return view("rtslieu.rtsdepartementtemoin",compact("rts","bullnull","hs","votant","inscrit",
        "tauxDeParticipations","departement","depouillement","rts"));
        // dd($rts,$departement);
    }

    public function resultatParDepartementTemoin()
    {
        $region_id = "";
        $departement_id = "";
        $departements = [];
        $departement = null;
        $rts = [];
        $inscrit = null;
        $votant = null;
        $bullnull = null;
        $hs = null;
        $candidat = null;
        $regions  = $this->regionRepository->getAll();
        $tauxDeParticipations = [];
        $depouillement= [];

        $depouillement[] =  0;
        $depouillement[] =  0;
        $user = Auth::user();

        $tauxDeParticipations = $this->participationRepository->participationParHeureParcandidatByRegion($user->region_id);
        $inscrit = $this->lieuvoteRepository->nbElecteursTemoinByRegion($user->region_id);

        if($user->region_id)
        {
          $departements = DB::table("departements")->where("region_id",$user->region_id)->get();
        }


        return view("rtslieu.rtsdepartementtemoin",compact("region_id","departement_id","departements","regions","rts",
            "bullnull","hs","votant","inscrit","departement","tauxDeParticipations","depouillement"));
  }

  public function rtsByCandidat()
  {
        $rts = $this->rtslieuRepository->rtsByCandidat();
    //dd($rts);
        $departements = $this->departementRepository->getAllOnLy();
        $rtsByDepartements = $this->rtslieuRepository->rtsGroupByDepartementandCandidat();
        $siegesParCirconscription = array();
        //recuperer les departement et les nombre de partement par deputé
        foreach ($departements as $key => $value) {
            $siegesParCirconscription[$value->nom]  = intval($value->nbcandidat);
        }
    //dd($siegesParCirconscription);
        $votantVal = 0;
        $votesProportionnels = array();
        foreach ($rts as $key => $rt) {
        $votantVal = $votantVal + $rt->nb;
        $votesProportionnels[$rt->coalition]  = intval($rt->nb);
        }
        //dd($votesProportionnels);
        $quotiant = round( $votantVal/53,0);

        $circonscriptions = array();

        foreach ($departements as $key => $value) {
            $resultat = array();
            foreach ($rtsByDepartements as $key => $rtsByDepartement) {
                if($value->nom == $rtsByDepartement->departement)
                {
                    $resultat[$rtsByDepartement->coalition] = intval($rtsByDepartement->nb);
                }

            }
            $circonscriptions[$value->nom] = $resultat;
        }

    //dd($circonscriptions);
    // dd(round($quotiant,0));

    //dd($rts);
    $totalVotants = array_sum($votesProportionnels);  // Calcul du total de votants
    $bulletinnull = $this->lieuvoteRepository->nbBulletinNull();
    $hs = $this->lieuvoteRepository->nbHs();
    //$votant = $this->lieuvoteRepository->nbVotant();
    $inscrit = $this->lieuvoteRepository->nbElecteurs();
   // dd($votant);

    // Calcul
    $resultats = $this->lieuvoteRepository->calculerSieges($circonscriptions, $siegesParCirconscription, $votesProportionnels, $totalVotants);
   // dd($rts);

    foreach ($rts as $key => $rt) {
        $resultats[$rt->coalition]["nb"] = $rt->nb;
        $resultats[$rt->coalition]["restant"] = $rt->nb%$quotiant;
       // $resultats[$rt->coalition]["photo"] = $rt->photo;
    }
    //dd(10%14);
    $depouillement= [];

    $depouillement[] = $this->lieuvoteRepository->nbLieuVoteByEtat(1) ?? 0;
    $depouillement[] = $this->lieuvoteRepository->nbLieuVoteByEtat(0) ?? 0;
    //dd($resultats);
    uasort($resultats, function ($a, $b) {
        // Comparaison en tant qu'entiers, car 'nb' est une chaîne de caractères
        return (int)$b['nb'] - (int)$a['nb'];
    });
    return view("rtslieu.rtsnational",compact("resultats","totalVotants","hs","bulletinnull","inscrit","quotiant","depouillement","rts"));

  }


   /*public function calculerSieges($circonscriptions, $siegesParCirconscription, $votesProportionnels, $totalVotants) {
    $resultatSiegesMajoritaires = [];
    $siegesProportionnels = [];
    $siegesProp = 53;  // Nombre de sièges pour la proportionnelle

    // Distribution des sièges majoritaires : tout au parti ayant la majorité des voix
    foreach ($circonscriptions as $circonscription => $resultats) {
        arsort($resultats);  // Trier les votes par ordre décroissant pour la circonscription
        $partiGagnant = key($resultats);  // Parti ayant obtenu le plus de votes
        $nombreSieges = $siegesParCirconscription[$circonscription] ?? 0;

        // Attribuer tous les sièges de la circonscription au parti gagnant
        $resultatSiegesMajoritaires[$partiGagnant] = ($resultatSiegesMajoritaires[$partiGagnant] ?? 0) + $nombreSieges;
    }

    // Calcul du quotient électoral pour la répartition proportionnelle
    $quotientElectoral = $totalVotants / $siegesProp;
   //dd($quotientElectoral);

    // Distribution des sièges proportionnels en fonction du quotient électoral
    foreach ($votesProportionnels as $parti => $votes) {
        $siegesProportionnels[$parti] = intdiv($votes, $quotientElectoral);
    }

    // Attribution des sièges restants par la méthode des plus forts restes
    $siegesAttribues = array_sum($siegesProportionnels);
    $siegesRestants = $siegesProp - $siegesAttribues;

    if ($siegesRestants > 0) {
        // Calculer les restes pour chaque parti
        $restes = [];
        foreach ($votesProportionnels as $parti => $votes) {
            $reste = $votes % $quotientElectoral;
            $restes[$parti] = $reste;
        }

        // Trier les partis par ordre décroissant des restes
        arsort($restes);

        // Distribuer les sièges restants aux partis avec les plus grands restes
        foreach (array_keys($restes) as $parti) {
            if ($siegesRestants <= 0) break;
            $siegesProportionnels[$parti]++;
            $siegesRestants--;
        }
    }

    // Combinaison des résultats
    $siegesTotal = array();
      foreach (array_merge(array_keys($resultatSiegesMajoritaires), array_keys($siegesProportionnels)) as $parti) {
            $siege = array();
            $siege['proportionnel'] =  $siegesProportionnels[$parti] ?? 0;
            $siege['majoritaire']   = $resultatSiegesMajoritaires[$parti] ?? 0;
            $siege['total']         = ($resultatSiegesMajoritaires[$parti] ?? 0) + ($siegesProportionnels[$parti] ?? 0);
          $siegesTotal[$parti] = $siege;
      }

      return $siegesTotal;
  }*/
/*
    // Exemples de données d'entrée
  $circonscriptions = [
    'Dakar' => ['Parti A' => 50000, 'Parti B' => 30000, 'Parti C' => 20000],
    'Thies' => ['Parti A' => 20000, 'Parti B' => 25000, 'Parti C' => 15000],
    // Ajouter d'autres circonscriptions
];

$siegesParCirconscription = [
    'Dakar' => 5,
    'Thies' => 3,
    // Nombre de sièges pour chaque circonscription
];

$votesProportionnels = [
    'Parti A' => 150000,
    'Parti B' => 100000,
    'Parti C' => 50000,
    // Votes des partis pour le scrutin proportionnel
];

/*function calculerSieges($circonscriptions, $siegesParCirconscription, $votesProportionnels, $totalVotants) {
    $resultatSiegesMajoritaires = [];
    $siegesProportionnels = [];
    $siegesProp = 53;  // Nombre de sièges proportionnels disponibles

    // Étape 1 : Attribution des sièges majoritaires
    foreach ($circonscriptions as $circonscription => $resultats) {
        arsort($resultats); // Trier par ordre décroissant des votes
        $partiGagnant = key($resultats); // Parti gagnant
        $nombreSieges = $siegesParCirconscription[$circonscription] ?? 0;

        $resultatSiegesMajoritaires[$partiGagnant] = ($resultatSiegesMajoritaires[$partiGagnant] ?? 0) + $nombreSieges;
    }

    // Étape 2 : Calcul du quotient électoral
    $quotientElectoral = $totalVotants / $siegesProp;

    // Étape 3 : Distribution initiale des sièges proportionnels
    $restes = [];
    foreach ($votesProportionnels as $parti => $votes) {
        $siegesProportionnels[$parti] = intdiv($votes, $quotientElectoral);
        $reste = $votes % $quotientElectoral; // Fraction restante
        $restes[$parti] = $reste / $quotientElectoral; // Proportion du reste
    }

    // Vérification initiale du total des sièges attribués
    $siegesAttribues = array_sum($siegesProportionnels);
    $siegesRestants = $siegesProp - $siegesAttribues;

    // Étape 4 : Distribution des sièges restants
    if ($siegesRestants > 0) {
        // Trier les restes par ordre décroissant
        arsort($restes);

        foreach (array_keys($restes) as $parti) {
            if ($siegesRestants <= 0) break; // Stopper si tous les sièges sont attribués
            $siegesProportionnels[$parti]++;
            $siegesRestants--;
        }
    }

    // Étape 5 : Ajustement final si dépassement (sécurité supplémentaire)
    while (array_sum($siegesProportionnels) > $siegesProp) {
        foreach ($siegesProportionnels as $parti => &$sieges) {
            if ($sieges > 0) {
                $sieges--; // Retirer un siège
                if (array_sum($siegesProportionnels) == $siegesProp) break 2; // Arrêter dès que l'ajustement est terminé
            }
        }
    }

    // Étape 6 : Fusion des résultats majoritaires et proportionnels
    $siegesTotal = [];
    foreach (array_merge(array_keys($resultatSiegesMajoritaires), array_keys($siegesProportionnels)) as $parti) {
      //  $siegesTotal[$parti] = ($resultatSiegesMajoritaires[$parti] ?? 0) + ($siegesProportionnels[$parti] ?? 0);
      $siege = array();
      $siege['proportionnel'] =  $siegesProportionnels[$parti] ?? 0;
      $siege['majoritaire']   = $resultatSiegesMajoritaires[$parti] ?? 0;
      $siege['total']         = ($resultatSiegesMajoritaires[$parti] ?? 0) + ($siegesProportionnels[$parti] ?? 0);
    $siegesTotal[$parti] = $siege;
    }

    return $siegesTotal;
}*/
public function rtsByBureatTemoin()
  {
        $rts = $this->rtslieuRepository->rtsByCandidatTemoin();
        $departements = $this->departementRepository->getAllOnLy();
        $rtsByDepartements = $this->rtslieuRepository->rtsGroupByDepartementandCandidatByTemoin();
        $siegesParCirconscription = array();
        $depouillement= [];

        $depouillement[] = $this->lieuvoteRepository->nbLieuVoteTemoinByEtat(1) ?? 0;
        $depouillement[] = $this->lieuvoteRepository->nbLieuVoteTemoinByEtat(0) ?? 0;

        //recuperer les departement et les nombre de partement par deputé
        foreach ($departements as $key => $value) {
            $siegesParCirconscription[$value->nom]  = intval($value->nbcandidat);
        }
    //dd($siegesParCirconscription);
        $votantVal = 0;
        $votesProportionnels = array();
        foreach ($rts as $key => $rt) {
        $votantVal = $votantVal + $rt->nb;
        $votesProportionnels[$rt->coalition]  = intval($rt->nb);
        }
        //dd($votesProportionnels);
        $quotiant = round( $votantVal/53,0);

        $circonscriptions = array();

        foreach ($departements as $key => $value) {
            $resultat = array();
            foreach ($rtsByDepartements as $key => $rtsByDepartement) {
                if($value->nom == $rtsByDepartement->departement)
                {
                    $resultat[$rtsByDepartement->coalition] = intval($rtsByDepartement->nb);
                }

            }
            $circonscriptions[$value->nom] = $resultat;
        }

    //dd($circonscriptions);
    // dd(round($quotiant,0));


    $totalVotants = array_sum($votesProportionnels);  // Calcul du total de votants
    $bulletinnull = $this->lieuvoteRepository->nbBulletinNullTemoin();
    $hs = $this->lieuvoteRepository->nbHsTemoin();
    $votant = $this->lieuvoteRepository->nbVotantTemoin();
    $inscrit = $this->lieuvoteRepository->nbElecteursTemoin();
    //dd($bulletinnull);

    // Calcul
    $resultats = $this->lieuvoteRepository->calculerSieges($circonscriptions, $siegesParCirconscription, $votesProportionnels, $totalVotants);
    //dd($rts);

    foreach ($rts as $key => $rt) {
        $resultats[$rt->coalition]["nb"] = $rt->nb;
        $resultats[$rt->coalition]["restant"] = $rt->nb%$quotiant;
    }
    //dd(10%14);

    uasort($resultats, function ($a, $b) {
        // Comparaison en tant qu'entiers, car 'nb' est une chaîne de caractères
        return (int)$b['nb'] - (int)$a['nb'];
    });

    return view("rtslieu.rtsnationaltemoin",compact("resultats","totalVotants","hs","votant","bulletinnull","inscrit"
    ,"quotiant","depouillement","rts"));

  }

  public function showRtsByLieuvote($lieuvote)
  {
    $rtslieus = $this->rtslieuRepository->getByCandidatAndLieuvote($lieuvote);
    $lieuvote  = $this->lieuvoteRepository->getOnlyById($lieuvote);
    $centrevote = $this->centrevoteRepository->getOneOnly($lieuvote->centrevote_id);
    return view("rtslieu.rtsbylieuvote",compact("rtslieus","lieuvote","centrevote"));

  }

  public function rtsDepartementImpression($departement_id,$type )
  {
    if($type=='1')
    {
      $rts = $this->rtslieuRepository->rtsGroupByCandidatByDepartement($departement_id);
      //$departements= $this->departementRepository->getAll();
      $departement = DB::table("departements")->where("id",$departement_id)->first();


      $votant  = $this->lieuvoteRepository->nbVotantByDepartement($departement_id);
      $bullnull  = $this->lieuvoteRepository->nbBulletinNullByDepartement($departement_id);
      $hs  = $this->lieuvoteRepository->nbHsByDepartement($departement_id);
      $inscrit = $this->lieuvoteRepository->sumByDepartements($departement_id);
    }
    else if($type==2)
    {
      $rts = $this->rtslieuRepository->rtsGroupByCandidatByDepartementTemoin($departement_id);

         $departement = DB::table("departements")->where("id",$departement_id)->first();

         $votant  = $this->lieuvoteRepository->nbVotantByDepartementTemoin($departement_id);
        // dd($votant);
         $bullnull  = $this->lieuvoteRepository->nbBulletinNullByDepartementTemoin($departement_id);
         $hs  = $this->lieuvoteRepository->nbHsByDepartementTemoin($departement_id);
         $inscrit = $this->lieuvoteRepository->sumByDepartementsTemoin($departement_id);
    }


      return view("rtslieu.impdepartemental",compact("rts",
      "bullnull","hs","votant","inscrit","departement"));
      // dd($rts,$departement);
  }

  public function rtsByCandidatImpression($type)
  {
    if($type==1)
    {
        $rts = $this->rtslieuRepository->rtsByCandidat();
        $departements = $this->departementRepository->getAllOnLy();
        $rtsByDepartements = $this->rtslieuRepository->rtsGroupByDepartementandCandidat();
        $siegesParCirconscription = array();
        //recuperer les departement et les nombre de partement par deputé
        foreach ($departements as $key => $value) {
            $siegesParCirconscription[$value->nom]  = intval($value->nbcandidat);
        }
    //dd($siegesParCirconscription);
        $votantVal = 0;
        $votesProportionnels = array();
        foreach ($rts as $key => $rt) {
        $votantVal = $votantVal + $rt->nb;
        $votesProportionnels[$rt->coalition]  = intval($rt->nb);
        }
        //dd($votesProportionnels);
        $quotiant = round( $votantVal/53,0);

        $circonscriptions = array();

        foreach ($departements as $key => $value) {
            $resultat = array();
            foreach ($rtsByDepartements as $key => $rtsByDepartement) {
                if($value->nom == $rtsByDepartement->departement)
                {
                    $resultat[$rtsByDepartement->coalition] = intval($rtsByDepartement->nb);
                }

            }
            $circonscriptions[$value->nom] = $resultat;
        }

      $totalVotants = array_sum($votesProportionnels);  // Calcul du total de votants
      $bulletinnull = $this->lieuvoteRepository->nbBulletinNull();
      $hs = $this->lieuvoteRepository->nbHs();
      $votant = $this->lieuvoteRepository->nbVotant();
      $inscrit = $this->lieuvoteRepository->nbElecteurs();
      //dd($bulletinnull);

    // Calcul
    $resultats = $this->lieuvoteRepository->calculerSieges($circonscriptions, $siegesParCirconscription, $votesProportionnels, $totalVotants);
    //dd($rts);

    foreach ($rts as $key => $rt) {
        $resultats[$rt->coalition]["nb"] = $rt->nb;
        $resultats[$rt->coalition]["restant"] = $rt->nb%$quotiant;
    }
  }
  else if ($type==2)
  {
    $rts = $this->rtslieuRepository->rtsByCandidatTemoin();
    $departements = $this->departementRepository->getAllOnLy();
    $rtsByDepartements = $this->rtslieuRepository->rtsGroupByDepartementandCandidatByTemoin();
    $siegesParCirconscription = array();

    //recuperer les departement et les nombre de partement par deputé
    foreach ($departements as $key => $value) {
        $siegesParCirconscription[$value->nom]  = intval($value->nbcandidat);
    }
//dd($siegesParCirconscription);
    $votantVal = 0;
    $votesProportionnels = array();
    foreach ($rts as $key => $rt) {
    $votantVal = $votantVal + $rt->nb;
    $votesProportionnels[$rt->coalition]  = intval($rt->nb);
    }
    //dd($votesProportionnels);
    $quotiant = round( $votantVal/53,0);

    $circonscriptions = array();

    foreach ($departements as $key => $value) {
        $resultat = array();
        foreach ($rtsByDepartements as $key => $rtsByDepartement) {
            if($value->nom == $rtsByDepartement->departement)
            {
                $resultat[$rtsByDepartement->coalition] = intval($rtsByDepartement->nb);
            }

        }
        $circonscriptions[$value->nom] = $resultat;
    }

//dd($circonscriptions);
// dd(round($quotiant,0));


  $totalVotants = array_sum($votesProportionnels);  // Calcul du total de votants
  $bulletinnull = $this->lieuvoteRepository->nbBulletinNullTemoin();
  $hs = $this->lieuvoteRepository->nbHsTemoin();
  $votant = $this->lieuvoteRepository->nbVotantTemoin();
  $inscrit = $this->lieuvoteRepository->nbElecteursTemoin();
  //dd($bulletinnull);

  // Calcul
  $resultats = $this->lieuvoteRepository->calculerSieges($circonscriptions, $siegesParCirconscription, $votesProportionnels, $totalVotants);
  //dd($rts);

  foreach ($rts as $key => $rt)
  {
      $resultats[$rt->coalition]["nb"] = $rt->nb;
      $resultats[$rt->coalition]["restant"] = $rt->nb%$quotiant;
  }
  //dd($resultats);

  }
  uasort($resultats, function ($a, $b) {
    // Comparaison en tant qu'entiers, car 'nb' est une chaîne de caractères
    return (int)$b['nb'] - (int)$a['nb'];
});

    return view("rtslieu.impnational",compact("resultats","totalVotants","hs","votant","bulletinnull","inscrit","quotiant"));

  }

  public function rtsDepartementExcel($departement_id,$type )
  {
    if($type=='1')
    {
      $rts = $this->rtslieuRepository->rtsGroupByCandidatByDepartementExcel($departement_id);
      //$departements= $this->departementRepository->getAll();
      $departement = DB::table("departements")->where("id",$departement_id)->first();


      $votant  = $this->rtslieuRepository->nbVoixByDepartement($departement_id);
        $bullnull  = $this->lieuvoteRepository->nbBulletinNullByDepartement($departement_id);
        $hs  = $this->lieuvoteRepository->nbHsByDepartement($departement_id);
        $inscrit = $this->lieuvoteRepository->sumByDepartements($departement_id);
      foreach ($rts as $key => $value) {
        $rts[$key]->taux = round(($rts[$key]->nb*100)/$votant,2);
        $rts[$key]->taux =   $rts[$key]->taux.'%';
      }
      //dd($rts,$votant);
      $rts[0]->inscrit =  $inscrit;
      $rts[0]->votant =  $votant + $bullnull;
      $rts[0]->bullnull = $bullnull;
      $rts[0]->exprime = $votant;
      $rts[0]->participation  =  round(($votant*100)/($inscrit ? $inscrit : 1) ,2);
      $rts[0]->siege = $departement->nbcandidat;


    }
    else if($type==2)
    {
      $rts = $this->rtslieuRepository->rtsGroupByCandidatByDepartementExcel($departement_id);

         $departement = DB::table("departements")->where("id",$departement_id)->first();

         $votant  = $this->lieuvoteRepository->nbVotantByDepartementTemoin($departement_id);
       // dd($votant);
         $bullnull  = $this->lieuvoteRepository->nbBulletinNullByDepartementTemoin($departement_id);
         $hs  = $this->lieuvoteRepository->nbHsByDepartementTemoin($departement_id);
         $inscrit = $this->lieuvoteRepository->sumByDepartementsTemoin($departement_id);
       //  dd($rts);
    }


      return  Excel::download(new RtsDepartemmentExport($rts),$departement->nom.'.xlsx');
      ;

  }

  public function rtsDepartementExcelBycommune($departement_id )
  {
   // $user = Auth::user();
    $communes = $this->communeRepository->getByDepartement($departement_id);
    $inscrits = $this->lieuvoteRepository->nbElecteursTemoinByDepartementGroupByCommune($departement_id);
    $votants = $this->rtslieuRepository->nbVoixByDepartementGroupByCommune($departement_id);
   // $inscrit = 
  }

  public function importExcel(Request $request)
  {
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', '3600');
  
     /*   $data =  Excel::import(new RegionImport,$request['file']);
   //   dd($data);
  
      return redirect()->back()->with('success', 'Données importées avec succès.'); */
      $this->validate($request, [
          'file' => 'bail|required|file|mimes:xlsx'
      ]);
  
      // 2. On déplace le fichier uploadé vers le dossier "public" pour le lire
      $fichier = $request->file->move(public_path(), $request->file->hashName());
  
      // 3. $reader : L'instance Spatie\SimpleExcel\SimpleExcelReader
      $reader = SimpleExcelReader::create($fichier);
  
      // On récupère le contenu (les lignes) du fichier
      $rows = $reader->getRows();
  
      // $rows est une Illuminate\Support\LazyCollection
  
      // 4. On insère toutes les lignes dans la base de données
    //  $rows->toArray());
      //$status = Rtslieu::insert($rows->toArray());
      foreach ($rows as $key => $rtslieu) {
    
                Rtslieu::create([
                    "nbvote"=>$rtslieu['nbvote'],
                    "lieuvote_id"=>$rtslieu['lieuvote_id'],
                    "candidat_id"=>$rtslieu['candidat_id'],
                    "departement_id"=>$rtslieu['departement_id'],
                   
                ]);
    }
  
      // Si toutes les lignes sont insérées
    
          // 5. On supprime le fichier uploadé
          $reader->close(); // On ferme le $reader
         // unlink($fichier);
  
          // 6. Retour vers le formulaire avec un message $msg
          return back()->withMsg("Importation réussie !");
  
    
  }
  
  


}
