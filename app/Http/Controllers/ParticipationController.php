<?php

namespace App\Http\Controllers;

use App\Repositories\CentrevoteRepository;
use App\Repositories\CommuneRepository;
use App\Repositories\DepartementRepository;
use App\Repositories\HeureRepository;
use App\Repositories\LieuvoteRepository;
use App\Repositories\ParticipationRepository;
use App\Repositories\RegionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParticipationController extends Controller
{
    protected $participationRepository;
    protected $heureRepository;
    protected $lieuvoteRepository;
    protected $regionRepository;

    protected $departementRepository;
    protected $communeRepository;
    protected $centrevoteRepository;

    public function __construct(ParticipationRepository $participationRepository, HeureRepository $heureRepository,
    LieuvoteRepository $lieuvoteRepository,RegionRepository $regionRepository,DepartementRepository $departementRepository,
    CommuneRepository $communeRepository,CentrevoteRepository $centrevoteRepository){
        $this->participationRepository =$participationRepository;
        $this->heureRepository = $heureRepository;
        $this->lieuvoteRepository = $lieuvoteRepository;
        $this->regionRepository = $regionRepository;
        $this->departementRepository = $departementRepository;
        $this->communeRepository = $communeRepository;
        $this->centrevoteRepository = $centrevoteRepository;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $participations = $this->participationRepository->getAll();
        return view('participation.index',compact('participations'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $heures = $this->heureRepository->getAll();
        $lieuvotes = $this->lieuvoteRepository->getAll();
        $regions = $this->regionRepository->getAll();
        return view('participation.add',compact('heures','lieuvotes','regions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $participation = DB::table("participations")->where(["lieuvote_id"=>$request->lieuvote_id,"heure_id"=>$request->heure_id])->first();
        if(!empty($participation))
        {
            return redirect()->back()->withErrors(["erreur"=>"Bureau déja saisi"]);

        }
        else
        {
            $participations = $this->participationRepository->store($request->all());
            return redirect()->back()->with("success","Enregistrement avec succés");


        }
       // DB::table("departements")->where("id",$request->departement_id)->update(["participation"=>true]);
       /*  $participation = $this->participationRepository->getParticpationByBureaudeVote($request->lieuvote_id);
        if($participation){
            if($participation->heure_id==$request->heure_id){
                //dd($participation);
                DB::table("participations")->where("id",$participation->id)->update(["resultat"=>$request->resultat]);
            }else{
                $participations = $this->participationRepository->store($request->all());
            }
        }else{
            $participations = $this->participationRepository->store($request->all());
        }

        return redirect('participation');*/

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $participation = $this->participationRepository->getById($id);
        return view('participation.show',compact('participation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $participation = $this->participationRepository->getById($id);
        $lieuvote = $this->lieuvoteRepository->getById($participation->lieuvote_id);
        $centrevote = $this->centrevoteRepository->getById($lieuvote->centrevote_id);
        $heures = $this->heureRepository->getAll();
        $regions = $this->regionRepository->getAll();
        $departements = $this->departementRepository->getAll();
        $communes = $this->communeRepository->getAll();
        $centrevotes = $this->centrevoteRepository->getCentreTemoin($centrevote->commune_id);
        return view('participation.edit',compact('participation','heures','regions',
    'departements','communes','centrevotes','lieuvote'));
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
        $this->participationRepository->update($id, $request->all());
        return redirect('participation');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->participationRepository->destroy($id);
        return redirect('participation');
    }
    public function    byHeure($heure){
        $participations = $this->participationRepository->getByHeure($heure);
        return response()->json($participations);
    }

    public function getByHeure($heure){
        $participations = $this->participationRepository->getByHeure($heure);
        return response()->json($participations);
    }
    public function getParticipationByHeure($heure)
    {
        $participations =  $this->participationRepository->getParticipationByHeure($heure);
       // dd($participations);
        return redirect('participation');
    }

    public function nbElecteursTemoinByDepartement($departement){
        $nb = $this->lieuvoteRepository->nbElecteursTemoinByDepartement($departement);
        return response()->json($nb);
    }

    public function getByRegionParticipation($departement){
        $data = $this->departementRepository->getByRegionParticipation($departement);
        return response()->json($data);
    }


}
