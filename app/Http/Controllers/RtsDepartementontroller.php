<?php

namespace App\Http\Controllers;

use App\Models\Rtsdepartement;
use App\Repositories\CandidatRepository;
use App\Repositories\DepartementRepository;
use App\Repositories\LieuvoteRepository;
use App\Repositories\RegionRepository;
use App\Repositories\RtsdepartementRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Excel;
use Spatie\SimpleExcel\SimpleExcelReader;

class RtsDepartementontroller extends Controller
{
    protected $rtsdepartementRepository;
    protected $departementRepository;
    protected $candidatRepository;
    protected $regionRepository;
    protected $lieuvoteRepository;

    public function __construct(RtsdepartementRepository $rtsdepartementRepository, DepartementRepository $departementRepository,
    CandidatRepository $candidatRepository,RegionRepository $regionRepository,LieuvoteRepository $lieuvoteRepository){
        $this->rtsdepartementRepository =$rtsdepartementRepository;
        $this->departementRepository = $departementRepository;
        $this->candidatRepository = $candidatRepository;
        $this->regionRepository = $regionRepository;
        $this->lieuvoteRepository = $lieuvoteRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rtsdepartements = $this->rtsdepartementRepository->getAll();
        return view('rtsdepartement.index',compact('rtsdepartements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       // $departements = $this->departementRepository->getAll();
        $candidats = $this->candidatRepository->getAll();
        $regions = $this->regionRepository->getAll();
        $region_id = "";
        $departements = [];
        $departement_id = "";
        return view('rtsdepartement.add',compact('candidats',"regions","region_id","departements","departement_id"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // dd   ($request["nbvote"]);
      $rts = $request["nbvote"];
      $candidats = $request["candidat_id"];
      //VERIER si les resulats ne depasse pas le nombre de perssone inscrit
      $totalRts = 0;
      for ($i= 0; $i < count($rts); $i++) {
        $totalRts = $rts[$i] + $totalRts;

      }
      if($totalRts > $request->nb_electeur)
      {
        return redirect()->back()->withErrors(["erreur"=>"Les resutat ne peut être superieur au nombre d'inscrit"])->withInput();

      }
      else
      {
        $rtsdepartements = $this->rtsdepartementRepository->getByDepartement($request["departement_id"]);
        if(count($rtsdepartements) > 0){
            $this->rtsdepartementRepository->deleteByDepartement($request["departement_id"]);
        }

      for ($i= 0; $i < count($rts); $i++) {
        $rtsdepartement = new Rtsdepartement();
        $rtsdepartement->candidat_id = $candidats[$i];
        $rtsdepartement->nbvote =(int)$rts[$i];
        $rtsdepartement->departement_id = $request["departement_id"];
        $rtsdepartement->save();
      }
         $this->departementRepository->updateEtat($request["departement_id"],$request["votant"],$request["bulnull"],$request["hs"]);
         return redirect('rtsdepartement');

      }



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rtsdepartement = $this->rtsdepartementRepository->getById($id);
        return view('rtsdepartement.show',compact('rtsdepartement'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $departements = $this->departementRepository->getAll();
        $rtsdepartement = $this->rtsdepartementRepository->getById($id);
        $candidats = $this->candidatRepository->getAll();
        return view('rtsdepartement.edit',compact('rtsdepartement','departements','candidats'));
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

        $this->rtsdepartementRepository->update($id, $request->all());
        return redirect('rtsdepartement');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->rtsdepartementRepository->destroy($id);
        return redirect('rtsdepartement');
    }
     public function resultatByRegionByCandidat()
    {

       $rts =  $this->rtsdepartementRepository->rtsGroupByRegionAndCandidat();


    // Initialiser un tableau pour stocker les résultats
    $results = array();

    // Parcourir les données pour calculer le pourcentage pour chaque candidat par région
    foreach ($rts as $entry) {
        $region = $entry->region;
        $candidat = $entry->candidat;
        $nb_votes = intval($entry->nb);

        // Si la région n'est pas encore présente dans les résultats, l'initialiser
        if (!isset($results[$candidat][$region])) {
            $results[$candidat][$region] = 0;
        }

        // Ajouter le nombre de votes pour ce candidat dans cette région
        $results[$candidat][$region] += $nb_votes;
    }

    // Calculer les pourcentages
    foreach ($results as $candidat => $region_votes) {
        $total_votes = array_sum($region_votes);

        foreach ($region_votes as $region => $votes) {
            $percentage = ($votes / $total_votes) * 100;
            $results[$candidat][$region] = round($percentage, 2); // Arrondi à deux décimales
        }
    }

    // Convertir les résultats en tableau d'objets
    $result_objects = array();
    foreach ($results as $candidat => $region_votes) {
        $candidate_obj = new \stdClass();
        $candidate_obj->candidat = $candidat;
        $candidate_obj->regions = array();
        foreach ($region_votes as $region => $percentage) {
            $region_obj = new \stdClass();
            $region_obj->region = $region;
            $region_obj->percentage = $percentage;
            $candidate_obj->regions[] = $region_obj;
        }
        $result_objects[] = $candidate_obj;
    }

    // Retourner les résultats sous forme de tableau d'objets
    //return $result_objects;

       return response()->json($result_objects);
    //dd($results);

  }

  public function rtsByCandidat()
  {
        $rts = $this->rtsdepartementRepository->rtsByCandidat();

        $departements = $this->departementRepository->getAllOnLy();
        $rtsByDepartements = $this->rtsdepartementRepository->rtsGroupByDepartementandCandidat();
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
    $bulletinnull = $this->departementRepository->nbBulletinNull();
    $hs = $this->departementRepository->nbHs();
    //$votant = $this->lieuvoteRepository->nbVotant();
    $inscrit = $this->lieuvoteRepository->nbElecteurs();
   // dd($votant);

    // Calcul
    $resultats = $this->rtsdepartementRepository->calculerSieges($circonscriptions, $siegesParCirconscription, $votesProportionnels, $totalVotants);
   // dd($rts);

    foreach ($rts as $key => $rt) {
        $resultats[$rt->coalition]["nb"] = $rt->nb;
        $resultats[$rt->coalition]["restant"] = $rt->nb%$quotiant;
       // $resultats[$rt->coalition]["photo"] = $rt->photo;
    }
    $resultats[""]["nb"]=0;
   //dd($resultats);
    uasort($resultats, function ($a, $b) {
        // Comparaison en tant qu'entiers, car 'nb' est une chaîne de caractères

        return (int)$b['nb'] - (int)$a['nb'];
    });

   // dd($resultats);
    return view("rtsdepartement.rtsnational",compact("resultats","totalVotants","hs","bulletinnull","inscrit","quotiant","rts"));

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

      return view("rtsdepartement.rtsdepartement",compact("region_id","departement_id","departements","regions","rts",
          "bullnull","hs","votant","inscrit","departement","depouillement"));
}


  public function rtsDepartement(Request $request)
  {
      $rts = $this->rtsdepartementRepository->rtsGroupByCandidatByDepartement($request->departement_id);
      //$departements= $this->departementRepository->getAll();
      $departement = DB::table("departements")->where("id",$request->departement_id)->first();
      $departement_id = $request->departement_id;
      $region_id = $request->region_id;
      $regions  = $this->regionRepository->getAll();
      $departements = $this->departementRepository->getByRegion($request->region_id);
      $candidat = DB::table("candidats")->first();
      $votant  = $departement->total;
      $bullnull  = $departement->null;
      $hs  =  $departement->hb;
      $inscrit = $this->lieuvoteRepository->sumByDepartements($departement_id);
      // dd($rts,$departement);
      $depouillement= [];

      $depouillement[] = $this->lieuvoteRepository->nbLieuVoteByEtatAndDepartement(1,$departement_id) ?? 0;
      $depouillement[] = $this->lieuvoteRepository->nbLieuVoteByEtatAndDepartement(0,$departement_id) ?? 0;
   // dd($rts);
      return view("rtsdepartement.rtsdepartement",compact("region_id","departement_id","departements","regions","rts",
      "bullnull","hs","votant","inscrit","departement","depouillement","rts"));
      // dd($rts,$departement);
  }

  public function importExcel(Request $request)
  {
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', '3600');


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
      $departement = $rtslieu['departement_id'];


                $rtsdepartement = new Rtsdepartement();

                $rtsdepartement->nbvote = $rtslieu['nbvote'];
                $rtsdepartement->candidat_id  = $rtslieu['candidat_id'];
                $rtsdepartement->departement_id = $departement;
                $rtsdepartement->save();

    }

      // Si toutes les lignes sont insérées

          // 5. On supprime le fichier uploadé
          $reader->close(); // On ferme le $reader
         // unlink($fichier);

          // 6. Retour vers le formulaire avec un message $msg
          return back()->withMsg("Importation réussie !");


  }



}
