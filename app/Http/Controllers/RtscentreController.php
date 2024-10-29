<?php

namespace App\Http\Controllers;

use App\Models\Rtscentre;
use App\Repositories\CandidatRepository;
use App\Repositories\CentrevoteRepository;
use App\Repositories\RegionRepository;
use App\Repositories\RtscentreRepository;
use Illuminate\Http\Request;
use stdClass;

class RtscentreController extends Controller
{
    protected $rtscentreRepository;
    protected $centrevoteRepository;
    protected $candidatRepository;
    protected $regionRepository;

    public function __construct(RtscentreRepository $rtscentreRepository, CentrevoteRepository $centrevoteRepository,
    CandidatRepository $candidatRepository,RegionRepository $regionRepository){
        $this->rtscentreRepository =$rtscentreRepository;
        $this->centrevoteRepository = $centrevoteRepository;
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
        $rtscentres = $this->rtscentreRepository->getAll();
        return view('rtscentre.index',compact('rtscentres'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       // $centrevotes = $this->centrevoteRepository->getAll();
        $candidats = $this->candidatRepository->getAll();
        $regions = $this->regionRepository->getAll();
        return view('rtscentre.add',compact('candidats',"regions"));
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
        $rtscentres = $this->rtscentreRepository->getByCentre($request["centrevote_id"]);
        if(count($rtscentres) > 0){
            $this->rtscentreRepository->deleteByCentre($request["centrevote_id"]);
        }
       
      for ($i= 0; $i < count($rts); $i++) {
        $rtscentre = new Rtscentre();
        $rtscentre->candidat_id = $candidats[$i];
        $rtscentre->nbvote =(int)$rts[$i];
        $rtscentre->centrevote_id = $request["centrevote_id"];
        $rtscentre->save();
      }
         $this->centrevoteRepository->updateNiveau($request["centrevote_id"]);
         return redirect('rtscentre');

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
        $rtscentre = $this->rtscentreRepository->getById($id);
        return view('rtscentre.show',compact('rtscentre'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $centrevotes = $this->centrevoteRepository->getAll();
        $rtscentre = $this->rtscentreRepository->getById($id);
        $candidats = $this->candidatRepository->getAll();
        return view('rtscentre.edit',compact('rtscentre','centrevotes','candidats'));
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

        $this->rtscentreRepository->update($id, $request->all());
        return redirect('rtscentre');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->rtscentreRepository->destroy($id);
        return redirect('rtscentre');
    }
     public function resultatByRegionByCandidat()
    {
      
       $rts =  $this->rtscentreRepository->rtsGroupByRegionAndCandidat();
       $rtsVoteByRegion =  $this->rtscentreRepository->rtsGroupByRegion();
       $rtsVoteByRegion = json_decode($rtsVoteByRegion,true);
      
     
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
     
        
    
        foreach ($region_votes as $region => $votes) {
           
            $nbvotl = 0;
            foreach($rtsVoteByRegion as $result){
               
               if($region == $result['region']){
                $nbvotl = $result['nb'];
               }
            }
            
            $percentage = ($votes / $nbvotl) * 100;
            $results[$candidat][$region] = round($percentage, 2); // Arrondi à deux décimales
        }
    }

    // Convertir les résultats en tableau d'objets
    $result_objects = array();
    foreach ($results as $candidat => $region_votes) {
        $candidate_obj = new stdClass();
        $candidate_obj->candidat = $candidat;
        $candidate_obj->regions = array();
        foreach ($region_votes as $region => $percentage) {
            $region_obj = new stdClass();
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
