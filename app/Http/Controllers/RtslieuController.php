<?php

namespace App\Http\Controllers;

use App\Models\Rtscentre;
use App\Models\Rtslieu;
use App\Repositories\ArrondissementRepository;
use App\Repositories\CandidatRepository;
use App\Repositories\CentrevoteRepository;
use App\Repositories\CommuneRepository;
use App\Repositories\DepartementRepository;
use App\Repositories\LieuvoteRepository;
use App\Repositories\RegionRepository;
use App\Repositories\RtscentreRepository;
use App\Repositories\RtslieuRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
protected $arrondissementRepository;

    public function __construct(RtslieuRepository $rtslieuRepository, LieuvoteRepository $lieuvoteRepository,
    CentrevoteRepository $centrevoteRepository,CandidatRepository $candidatRepository,RegionRepository $regionRepository,
    DepartementRepository $departementRepository,CommuneRepository $communeRepository,RtscentreRepository $rtsCentrevoteRepository,
    ArrondissementRepository $arrondissementRepository){
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
       $user = Auth::user();

       if($user->role=="admin")
       {
        $regions = $this->regionRepository->getRegionAsc();
        return view('rtslieu.add',compact('candidats',
    "regions"));
       }
       else if($user->role=="prefet")
       {
        $arrondissements = $this->arrondissementRepository->getByDepartement($user->departement_id);
        return view('rtslieu.add',compact('candidats',
    "arrondissements"));
       }
       else if($user->role=="sous_prefet")
       {
        $communes = $this->communeRepository->getByArrondissement($user->arrondissement_id);
        return view('rtslieu.add',compact('candidats',
    "communes"));
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
      $votant  = $this->rtslieuRepository->nbVoixByDepartement($departement_id);
      $bullnull  = $this->rtslieuRepository->nbBulletinNullByDepartement($candidat->id,$departement_id);
      $hs  = $this->rtslieuRepository->nbHsByDepartement($candidat->id,$departement_id);
      $inscrit = $this->lieuvoteRepository->sumByDepartements($departement_id);
       // dd($rts,$departement);
      return view("rtslieu.rtsdepartement",compact("region_id","departement_id","departements","regions","rts",
    "bullnull","hs","votant","inscrit","departement"));
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
 return view("rtslieu.rtsdepartement",compact("region_id","departement_id","departements","regions","rts",
    "bullnull","hs","votant","inscrit","departement"));    
  }

  public function rtsByCandidat()
  {
    $rts = $this->rtslieuRepository->rtsByCandidat();
    dd($rts);
    $votant = 0;
    foreach ($rts as $key => $rt) {
       $votant = $votant + $rt->nb;
    }
    dd($votant);
    $quotiant = $votant/53;
    foreach ($rts as $key => $value) {
      
    }
   // dd(round($quotiant,0));
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

$totalVotants = array_sum($votesProportionnels);  // Calcul du total de votants

// Calcul
$resultat = $this->calculerSieges($circonscriptions, $siegesParCirconscription, $votesProportionnels, $totalVotants);
dd($resultat);


    return view("rtslieu.rtsnational",compact("rts"));
  
  }
  

  public function calculerSieges($circonscriptions, $siegesParCirconscription, $votesProportionnels, $totalVotants) {
      $resultatSiegesMajoritaires = [];
      $siegesProportionnels = [];
      $siegesProp = 60;  // Nombre de sièges pour la proportionnelle
  
      // Distribution des sièges majoritaires par circonscription
      foreach ($circonscriptions as $circonscription => $resultats) {
          arsort($resultats);  // Trier les votes par ordre décroissant pour la circonscription
          $nombreSieges = $siegesParCirconscription[$circonscription] ?? 0;
  
          $index = 0;
          foreach ($resultats as $parti => $votes) {
              if ($index < $nombreSieges) {
                  $resultatSiegesMajoritaires[$parti] = ($resultatSiegesMajoritaires[$parti] ?? 0) + 1;
                  $index++;
              } else {
                  break;
              }
          }
      }
  
      // Calcul du quotient électoral pour la répartition proportionnelle
      $quotientElectoral = $totalVotants / $siegesProp;
  
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
      $siegesTotal = [];
      foreach (array_merge(array_keys($resultatSiegesMajoritaires), array_keys($siegesProportionnels)) as $parti) {
          $siegesTotal[$parti] = ($resultatSiegesMajoritaires[$parti] ?? 0) + ($siegesProportionnels[$parti] ?? 0);
      }
  
      return $siegesTotal;
  }
  

  
}
