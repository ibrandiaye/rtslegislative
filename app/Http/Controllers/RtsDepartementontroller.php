<?php

namespace App\Http\Controllers;

use App\Models\Rtsdepartement;
use App\Repositories\CandidatRepository;
use App\Repositories\DepartementRepository;
use App\Repositories\RegionRepository;
use App\Repositories\RtsdepartementRepository;
use Illuminate\Http\Request;

class RtsDepartementontroller extends Controller
{
    protected $rtsdepartementRepository;
    protected $departementRepository;
    protected $candidatRepository;
    protected $regionRepository;

    public function __construct(RtsdepartementRepository $rtsdepartementRepository, DepartementRepository $departementRepository,
    CandidatRepository $candidatRepository,RegionRepository $regionRepository){
        $this->rtsdepartementRepository =$rtsdepartementRepository;
        $this->departementRepository = $departementRepository;
        $this->candidatRepository = $candidatRepository;
        $this->regionRepository = $regionRepository;
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
        return view('rtsdepartement.add',compact('candidats',"regions"));
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
         $this->departementRepository->updateEtat($request["departement_id"]);
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
    }
