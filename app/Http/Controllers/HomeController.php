<?php

namespace App\Http\Controllers;

use App\Repositories\CandidatRepository;
use App\Repositories\CarteRepository;
use App\Repositories\CentrevoteeRepository;
use App\Repositories\CentrevoteRepository;
use App\Repositories\CommuneRepository;
use App\Repositories\DepartementRepository;
use App\Repositories\LieuvoteeRepository;
use App\Repositories\LieuvoteRepository;
use App\Repositories\ParticipationRepository;
use App\Repositories\PaysRepository;
use App\Repositories\RtscentreeRepository;
use App\Repositories\RtscentreRepository;
use App\Repositories\RtsdepartementRepository;
use App\Repositories\RtslieueRepository;
use App\Repositories\RtslieuRepository;
use App\Repositories\RtspaysRepository;
use App\Repositories\RtstemoinRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    protected $centrevoteRepository;
    protected $rtscentreRepository;
    protected $rtslieuRepository;
    protected $lieuvoteRepository;
    protected $candidatRepository;
    protected $centrevoteeRepository;
    protected $rtscentreeRepository;
    protected $rtslieueRepository;
    protected $lieuvoteeRepository;
    protected $participationRepository;
    protected $rtsDepartementRepository;
    private $rtsTemoinRepository;
    private $departementRepository;
    private $rtsPayrrepository;
    private $paysRepository;
    private $communeRepository;


    public function __construct(CentrevoteRepository $centrevoteRepository,
    RtscentreRepository $rtscentreRepository, RtslieuRepository $rtslieuRepository,
    LieuvoteRepository $lieuvoteRepository,CandidatRepository $candidatRepository,
    CentrevoteeRepository $centrevoteeRepository,RtscentreeRepository $rtscentreeRepository,
RtslieueRepository $rtslieueRepository,LieuvoteeRepository $lieuvoteeRepository,ParticipationRepository $participationRepository,
RtstemoinRepository $rtstemoinRepository,RtsdepartementRepository $rtsdepartementRepository,
DepartementRepository $departementRepository,RtspaysRepository $rtspaysRepository,PaysRepository $paysRepository,
CommuneRepository $communeRepository){
        $this->centrevoteRepository = $centrevoteRepository;
        $this->rtscentreRepository = $rtscentreRepository;
        $this->rtslieuRepository = $rtslieuRepository;
        $this->lieuvoteRepository = $lieuvoteRepository;
        $this->candidatRepository = $candidatRepository;

        $this->centrevoteeRepository = $centrevoteeRepository;
        $this->rtscentreeRepository = $rtscentreeRepository;
        $this->rtslieueRepository = $rtslieueRepository;
        $this->lieuvoteeRepository = $lieuvoteeRepository;
        $this->participationRepository = $participationRepository;
        $this->rtsTemoinRepository = $rtstemoinRepository;
        $this->rtsDepartementRepository = $rtsdepartementRepository;
        $this->departementRepository = $departementRepository;

        $this->rtsPayrrepository = $rtspaysRepository;
        $this->paysRepository = $paysRepository;

        $this->communeRepository  = $communeRepository;
    }
    public function index(){
        $user = Auth::user();
        if($user->role=="admin" || $user->role=="superviseur" || $user->role=="controlleur" )
        {
            //$nbCentrevotes = $this->departementRepository->nbDepartements();
           // $nbRtsCentre = $this->departementRepository->nbDepartementBEtat(true);
           // dd($nbCentrevotes);
           $totalLieuvote = $this->lieuvoteRepository->nbLieuVote();
           $depouille = $this->lieuvoteRepository->nbLieuVoteByEtat(true);
           $tauxDepouillement = round(($depouille/$totalLieuvote)*100,2);
           $electeurs = $this->lieuvoteRepository->nbElecteurs();
          // $votants = $this->rtslieuRepository->nbVotants();
           //$votants = $this->rtsDepartementRepository->nbVotants();
         //  $votantDiaspores = $this->rtsPayrrepository->nbVotants();
          // dd($votantDiaspores);

          // $rtsParCandidats = $this->rtsDepartementRepository->rtsByCandidat();
         //  $rtsParCandidatDiasporas = $this->rtsPayrrepository->rtsByCandidat();
         $rtsParCandidats = $this->rtslieuRepository->rtsByCandidat();

          $candidats = $this->candidatRepository->getAll();

        //  $electeursDiaspora = $this->lieuvoteeRepository->nbElecteurs();
        //  $nbureauDiaspora = $this->lieuvoteeRepository->nbLieuVotee();
          $nCentreVote = $this->centrevoteeRepository->nbCentrevotee();

          //Taux de particippation

          $tauxDeParticipations = $this->participationRepository->getParticipationGroupByHeure();
          $nbElecteursTemoin = $this->lieuvoteRepository->nbElecteursTemoin();
          $rtsTemoins = $this->rtsTemoinRepository->rtsByCandidat();
          $nbVotantTemoin = $this->rtsTemoinRepository->nbVotants();
          $participationParHeureParcandidat = $this->participationRepository->participationParHeureParcandidat();
           // dd($participationParHeureParcandidat);
            foreach ($tauxDeParticipations as $key => $value) {
                foreach ($participationParHeureParcandidat as $key1 => $value1) {
                    if($value->designation==$value1->designation )
                    {
                           //$tauxDeParticipations[$key] == new \stdClass();
                           $tauxDeParticipations[$key]->nbBureau = $value1->bureau;
                    }
                }
            }
         // dd($tauxDeParticipations);
        //  $nbVotantDiaspora = $this->rtsPayrrepository->nbVotants();
         // $nullNational = $this->departementRepository->nbNull();
         $nullNational = $this->lieuvoteRepository->nbBulletinNull();
        //  $nullEtrangers = $this->paysRepository->nbNull();
         /*  foreach ($rtsParCandidats as $key => $value) {
                foreach ($rtsParCandidatDiasporas as $k => $val) {
                    if($value->id ==$val->id){
                        $rtsParCandidats[$key]->nb = $rtsParCandidats[$key]->nb + $val->nb;
                    }
                }
           }*/
          /* return view("dashboardr",compact("nbCentrevotes","nbRtsCentre","electeurs",
            "tauxDepouillement","votants","tauxDepouillementElecteurs","rtsParCandidats","nbVotantDiaspora",
            "nbureauDiaspora","electeursDiaspora","nCentreVote","tauxDeParticipations",
            "nbElecteursTemoin",'rtsTemoins','nbVotantTemoin',"nullNational","nullEtrangers"));
            */
           // return redirect()->route("centre.by.arrondissement");

          // $rts = $this->rtslieuRepository->rtsByCandidat();

            $departements = $this->departementRepository->getAllOnLy();
            $rtsByDepartements = $this->rtslieuRepository->rtsGroupByDepartementandCandidat();
            $siegesParCirconscription = array();
            //recuperer les departement et les nombre de partement par deputé
            foreach ($departements as $key => $value) {
                $siegesParCirconscription[$value->nom]  = intval($value->nbcandidat);
            }
            //dd($rtsParCandidats);
            $votantVal = 0;
            $votesProportionnels = array();
            foreach ($rtsParCandidats as $key => $rt) {
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
          // dd($circonscriptions);
          $tauxDepouillementElecteurs = round(($totalVotants/$electeurs)*100,2);


            $resultats = $this->rtslieuRepository->calculerSieges($circonscriptions, $siegesParCirconscription, $votesProportionnels, $totalVotants);
           // dd($resultats);

           $nbBureauTemoin = $this->lieuvoteRepository->nbBureauTemoin();
           $nbBureauTemoinByEtat = $this->lieuvoteRepository->nbBureauTemoinByEtat(1);
           
           return view("dashboardr",compact("totalLieuvote","depouille","electeurs",
           "tauxDepouillement","tauxDepouillementElecteurs","rtsParCandidats",
           "nCentreVote","tauxDeParticipations","totalVotants","nbBureauTemoin","nbBureauTemoinByEtat",
           "nbElecteursTemoin",'rtsTemoins','nbVotantTemoin',"nullNational","resultats","nbBureauTemoin","nbBureauTemoinByEtat"));
        }
        else if($user->role=="prefet")
        {
            $rts = $this->rtslieuRepository->rtsGroupByCandidatByDepartement($user->departement_id);
        //$departements= $this->departementRepository->getAll();
        $departement = DB::table("departements")->where("id",$user->departement_id)->first();
        $departement_id = $user->departement_id;

        $votant  = $this->rtslieuRepository->nbVoixByDepartement($departement_id);
        $bullnull  = $this->lieuvoteRepository->nbBulletinNullByDepartement($departement_id);
        $hs  = $this->lieuvoteRepository->nbHsByDepartement($departement_id);
        $inscrit = $this->lieuvoteRepository->sumByDepartements($departement_id);
        // dd($rts,$departement);
        $depouillement= [];

        $depouillement[] = $this->lieuvoteRepository->nbLieuVoteByEtatAndDepartement(1,$departement_id) ?? 0;
        $depouillement[] = $this->lieuvoteRepository->nbLieuVoteByEtatAndDepartement(0,$departement_id) ?? 0;


        return view("rtslieu.rtsdepartement",compact("rts",
        "bullnull","hs","votant","inscrit","departement","depouillement"));
        }
        else  if($user->role=="gouverneur")
        {

            $rts =[];
        //$departements= $this->departementRepository->getAll();
        $departements = DB::table("departements")->where("region_id",$user->region_id)->get();
        $departement_id ="";
        $departement = null;

        $votant  = 0;
        $bullnull  = 0;
        $hs  = 0;
        $inscrit = 0;
        // dd($rts,$departement);
        $depouillement= [];

        $depouillement[]=0 ;
        $depouillement[]=0 ;
       
        //dd($nbElecteursTemoinByRegion);
        return view("rtslieu.rtsdepartement",compact("rts","departement",
        "bullnull","hs","votant","inscrit","departements","depouillement","departement_id"));
        }

    }
    public function carteByDepartement($id,$nom){

    }
    public function resultat(){

        $votants = $this->rtscentreRepository->nbVotants();

       $rtsParCandidats = $this->rtscentreRepository->rtsByCandidat();

       return view("dasboard",compact("rtsParCandidats","votants"));
    }

    public function nbVoteStat(){
        $nbElecteursParRegion = $this->lieuvoteRepository->sommeGroupByRegion();
        $nbVotesParRegion = $this->rtsDepartementRepository->rtsStatGroupByRegion();

        foreach ($nbVotesParRegion as $key => $value) {
            foreach ($nbElecteursParRegion as $key1 => $value1) {
                 if($value->region == $value1->region){
                    $nbVotesParRegion[$key]->tp = new \stdClass();
                    $nbVotesParRegion[$key]->electeur = new \stdClass();
                    $nbVotesParRegion[$key]->tp = round(($value->total/$value1->nb)*100,2);
                    $nbVotesParRegion[$key]->electeur = $value1->nb;


                 }
            }
        }
      //  dd($nbVotesParRegion);
        $nbVotesParDepartement = $this->departementRepository->getAll();
        $nbElecteursParDepartement = $this->lieuvoteRepository->sommeAllGroupByDepartement();
        foreach ($nbVotesParDepartement as $key => $value) {
            foreach ($nbElecteursParDepartement as $key1 => $value1) {
                 if($value->nom == $value1->departement){
                    $nbVotesParDepartement[$key]->tp = new \stdClass();
                    $nbVotesParDepartement[$key]->electeur = new \stdClass();
                    $nbVotesParDepartement[$key]->tp = round(($value->total/$value1->nb)*100,2);
                    $nbVotesParDepartement[$key]->electeur = $value1->nb;


                 }
            }
        }

       return view("rtsdepartement.liste",compact("nbVotesParRegion","nbVotesParDepartement"));
    }
    public function apiDashbord(){
        $votants = $this->rtsDepartementRepository->nbVotants();
        $nbVotantDiaspora = $this->rtsPayrrepository->nbVotants();
        $electeurs = $this->lieuvoteRepository->nbElecteurs();
        $electeursDiaspora = $this->lieuvoteeRepository->nbElecteurs();
        $nbureauDiaspora = $this->lieuvoteeRepository->nbLieuVotee();
        $nCentreVoteDiaspora = $this->centrevoteeRepository->nbCentrevotee();
        $nbureauVote = $this->lieuvoteRepository->nbLieuVote();
        $nCentreVote = $this->centrevoteRepository->nbCentrevote();
        $data = array(
            "nbVotantNational"=>$votants,
            "nbVotantDiaspora"=>$nbVotantDiaspora,
            "electeursNational"=>$electeurs,
            "electeursDiaspora"=>$electeursDiaspora,

            "nbureauVoteDiaspora"=>$nbureauDiaspora,
            "nbCentreVoteDiaspora"=>$nCentreVoteDiaspora,
            "nbureauVoteNational"=>$nbureauVote,
            "nbCentreVote"=>$nCentreVote,
        );
        return response()->json($data);
    }

    public function indexCentre(){
        $user = Auth::user();
        if($user->role=="admin" || $user->role=="superviseur" )
        {

           $totalLieuvote = $this->lieuvoteRepository->nbLieuVote();
           $depouille = $this->lieuvoteRepository->nbLieuVoteByEtat(true);
           $tauxDepouillement = round(($depouille/$totalLieuvote)*100,2);
           $electeurs = $this->lieuvoteRepository->nbElecteurs();

         $rtsParCandidats = $this->rtscentreRepository->rtsByCandidat();

          $candidats = $this->candidatRepository->getAll();

          $nCentreVote = $this->centrevoteeRepository->nbCentrevotee();

          //Taux de particippation

          $tauxDeParticipations = $this->participationRepository->getParticipationGroupByHeure();
          $nbElecteursTemoin = $this->lieuvoteRepository->nbElecteursTemoin();
          $rtsTemoins = $this->rtsTemoinRepository->rtsByCandidat();
          $nbVotantTemoin = $this->rtsTemoinRepository->nbVotants();
          //dd($rtsTemoins);

         $nullNational = $this->lieuvoteRepository->nbBulletinNull();

            $departements = $this->departementRepository->getAllOnLy();
            $rtsByDepartements = $this->rtscentreRepository->rtsGroupByDepartementandCandidat();
            $siegesParCirconscription = array();
            //recuperer les departement et les nombre de partement par deputé
            foreach ($departements as $key => $value) {
                $siegesParCirconscription[$value->nom]  = intval($value->nbcandidat);
            }
            //dd($rtsParCandidats);
            $votantVal = 0;
            $votesProportionnels = array();
            foreach ($rtsParCandidats as $key => $rt) {
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
          // dd($circonscriptions);
          $tauxDepouillementElecteurs = round(($totalVotants/$electeurs)*100,2);


            $resultats = $this->rtslieuRepository->calculerSieges($circonscriptions, $siegesParCirconscription, $votesProportionnels, $totalVotants);
           // dd($resultats);

           return view("dashboardr",compact("totalLieuvote","depouille","electeurs",
           "tauxDepouillement","tauxDepouillementElecteurs","rtsParCandidats",
           "nCentreVote","tauxDeParticipations","totalVotants",
           "nbElecteursTemoin",'rtsTemoins','nbVotantTemoin',"nullNational","resultats"));
        }
        else
        {
            $rts = $this->rtslieuRepository->rtsGroupByCandidatByDepartement($user->departement_id);
        //$departements= $this->departementRepository->getAll();
        $departement = DB::table("departements")->where("id",$user->departement_id)->first();
        $departement_id = $user->departement_id;

        $votant  = $this->rtslieuRepository->nbVoixByDepartement($departement_id);
        $bullnull  = $this->lieuvoteRepository->nbBulletinNullByDepartement($departement_id);
        $hs  = $this->lieuvoteRepository->nbHsByDepartement($departement_id);
        $inscrit = $this->lieuvoteRepository->sumByDepartements($departement_id);
        // dd($rts,$departement);
        $depouillement= [];

        $depouillement[] = $this->lieuvoteRepository->nbLieuVoteByEtatAndDepartement(1,$departement_id) ?? 0;
        $depouillement[] = $this->lieuvoteRepository->nbLieuVoteByEtatAndDepartement(0,$departement_id) ?? 0;


        return view("rtslieu.rtsdepartement",compact("rts",
        "bullnull","hs","votant","inscrit","departement","depouillement"));
        }

    }
}
