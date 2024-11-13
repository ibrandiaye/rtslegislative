<?php

namespace App\Http\Controllers;

use App\Repositories\ArrondissementRepository;
use App\Repositories\CentrevoteRepository;
use App\Repositories\CommuneRepository;
use App\Repositories\DepartementRepository;
use App\Repositories\HeureRepository;
use App\Repositories\LieuvoteRepository;
use App\Repositories\ParticipationRepository;
use App\Repositories\RegionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParticipationController extends Controller
{
    protected $participationRepository;
    protected $heureRepository;
    protected $lieuvoteRepository;
    protected $regionRepository;

    protected $departementRepository;
    protected $communeRepository;

    protected $arrondissementRepository;

    protected $centrevoteRepository;

    public function __construct(ParticipationRepository $participationRepository, HeureRepository $heureRepository,
    LieuvoteRepository $lieuvoteRepository,RegionRepository $regionRepository,DepartementRepository $departementRepository,
    CommuneRepository $communeRepository,CentrevoteRepository $centrevoteRepository,ArrondissementRepository $arrondissementRepository){
        $this->participationRepository =$participationRepository;
        $this->heureRepository = $heureRepository;
        $this->lieuvoteRepository = $lieuvoteRepository;
        $this->regionRepository = $regionRepository;
        $this->departementRepository = $departementRepository;
        $this->communeRepository = $communeRepository;
        $this->centrevoteRepository = $centrevoteRepository;
        $this->arrondissementRepository = $arrondissementRepository;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $heure_id = "";
        $heures = $this->heureRepository->getAlls();
        $departement = $this->departementRepository->getAllOnLy();
        $commune_id             ="";
        $centrevote_id          = "";
        $lieuvote_id            = "";
        $arrondissement_id      = "";
        $departement_id         = "";
        $region_id              = "";
        $communes = [];
        $centreVotes =[];
        $lieuVotes  =[];
        $departements =[];
        $etat      = "";
        if($user->role=="admin")
        {
            $participations = $this->participationRepository->getAll();
        }
        elseif($user->role=="prefet")
        {
            $participations = $this->participationRepository->getByDepartement($user->departement_id);
        }
        
        return view('participation.index',compact('participations',"heures","heure_id"));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $heures = $this->heureRepository->getAll();
        $user = Auth::user();

      
        $arrondissement_id      = "";
        $commune_id             = "";
        $centrevote_id          = "";
        $lieuvote_id            = "";

        $communes = [];
        $centreVotes =[];
        $lieuVotes  =[];
        if($user->role=='admin')
        {
          $departements = [];
          $regions = $this->regionRepository->getRegionAsc();
          $region_id              ="";
          $departement_id         = "";
          $arrondissements =[];
          return view('participation.add',compact('heures',
          "regions","region_id","departement_id","arrondissement_id","commune_id","centrevote_id",
          "lieuvote_id","regions","departements","arrondissements","communes","centreVotes","lieuVotes"));
        }
        elseif ($user->role=="prefet") 
        {
          $arrondissements =$this->arrondissementRepository->getByDepartement($user->departement_id);
          $departement_id = $user->departement_id;
          return view('participation.addprefet',compact("departement_id","arrondissement_id","commune_id","centrevote_id",
          "lieuvote_id","arrondissements","communes","centreVotes",'heures',"lieuVotes"));
        }
        
      
       // return view('participation.add',compact('heures','lieuvotes','regions'));
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
        if(empty($participation))
        {
            //return redirect()->back()->withErrors(["erreur"=>"Bureau déja saisi"]);
            $departement_id         = $request["departement_id"];
            $arrondissement_id      = $request["arrondissement_id"];
            $commune_id             = $request["commune_id"];
            $centrevote_id          = $request["centrevote_id"];
            $lieuvote_id            = $request["lieuvote_id"];
            $heures = $this->heureRepository->getAll();

            $arrondissements = $this->arrondissementRepository->getByDepartement($departement_id);
            $communes = $this->communeRepository->getByArrondissement($arrondissement_id);
            $centreVotes = $this->centrevoteRepository->getByCommune($commune_id);
            $lieuVotes  = $this->lieuvoteRepository->getByLieuvoteTemoin($centrevote_id);
    
          //  $rtstemoins = $this->rtstemoinRepository->store($request->all());
          $user = Auth::user();
          if($user->role=='admin')
            {
              $region_id              = $request["region_id"];
              $departements = $this->departementRepository->getByRegion($region_id);
              $regions = $this->regionRepository->getRegionAsc();
              return view('participation.add',compact("heures",
              "regions","region_id","departement_id","arrondissement_id","commune_id","centrevote_id",
              "lieuvote_id","regions","departements","arrondissements","communes","centreVotes","lieuVotes"))->withErrors(["erreur"=>"Bureau déja saisi"]);
            }
            elseif ($user->role=="prefet") 
            {
              return view('participation.addprefet',compact("heures","departement_id","arrondissement_id","commune_id","centrevote_id",
              "lieuvote_id","arrondissements","communes","centreVotes","lieuVotes"))->withErrors(["erreur"=>"Bureau déja saisi"]);
            }

        }
        else
        {
           // return redirect()->back()->with("success","Enregistrement avec succés");
            $departement_id         = $request["departement_id"];
            $arrondissement_id      = $request["arrondissement_id"];
            $commune_id             = $request["commune_id"];
            $centrevote_id          = $request["centrevote_id"];
            $lieuvote_id            = $request["lieuvote_id"];
            $heures = $this->heureRepository->getAll();

            $arrondissements = $this->arrondissementRepository->getByDepartement($departement_id);
            $communes = $this->communeRepository->getByArrondissement($arrondissement_id);
            $centreVotes = $this->centrevoteRepository->getByCommune($commune_id);
            $lieuVotes  = $this->lieuvoteRepository->getByLieuvoteTemoin($centrevote_id);
           
            $user = Auth::user();
            $participation = DB::table("participations")->where("lieuvote_id",$request["lieuvote_id"])->orderBy("id","desc")->first();

            if( empty($participation) || $participation->resultat <= $request["resultat"])
            {
                $participations = $this->participationRepository->store($request->all());
                if($request["heure_id"]==1)
                {
                    DB::table("lieuvotes")->where("id",$request["lieuvote_id"])->update(['heure1'=>1]);
                }
                else if($request["heure_id"]==2)
                {
                    DB::table("lieuvotes")->where("id",$request["lieuvote_id"])->update(['heure2'=>1]);

                }
                else if($request["heure_id"]==3)
                {
                    DB::table("lieuvotes")->where("id",$request["lieuvote_id"])->update(['heure3'=>1]);

                }
                else if($request["heure_id"]==4)
                {
                    DB::table("lieuvotes")->where("id",$request["lieuvote_id"])->update(['heure4'=>1]);

                }
                if($user->role=='admin')
                {
                    $region_id              = $request["region_id"];
                    $departements = $this->departementRepository->getByRegion($region_id);
                    $regions = $this->regionRepository->getRegionAsc();
                    return view('participation.add',compact("heures",
                    "regions","region_id","departement_id","arrondissement_id","commune_id","centrevote_id",
                    "lieuvote_id","regions","departements","arrondissements","communes","centreVotes","lieuVotes"))->with( "success","enregistrement avec succès");
                }
            elseif ($user->role=="prefet") 
            {
              return view('participation.addprefet',compact("departement_id","arrondissement_id","commune_id","centrevote_id",
             "heures", "lieuvote_id","arrondissements","communes","centreVotes","lieuVotes"))->with( "success","enregistrement avec succès");
            }

            }
            else
            {
                if($user->role=='admin')
            {
              $region_id              = $request["region_id"];
              $departements = $this->departementRepository->getByRegion($region_id);
              $regions = $this->regionRepository->getRegionAsc();
              return view('participation.add',compact("heures",
              "regions","region_id","departement_id","arrondissement_id","commune_id","centrevote_id",
              "lieuvote_id","regions","departements","arrondissements","communes","centreVotes","lieuVotes"))->withErrors(["erreur"=>"Nombre Inferieur à la dernière enregistrement"]);
            }
            elseif ($user->role=="prefet") 
            {
              return view('participation.addprefet',compact("departement_id","arrondissement_id","commune_id","centrevote_id",
             "heures", "lieuvote_id","arrondissements","communes","centreVotes","lieuVotes"))->withErrors(["erreur"=>"Nombre Inferieur à la dernière enregistrement"]);
            }
            }
    
          //  $rtstemoins = $this->rtstemoinRepository->store($request->all());          

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

    public function search(Request $request)
    {
       
        $user = Auth::user();
        $heure_id = $request->heure_id;
        $heures = $this->heureRepository->getAlls();
        if($user->role=="admin")
        {
            $participations = $this->participationRepository->getByHeure($request->heure_id);
        }
        elseif($user->role=="prefet")
        {
            $participations = $this->participationRepository->getByHeureAndDepartement($request->heure_id,$user->departement_id);
        }
        
        return view('participation.index',compact('participations',"heures","heure_id"));
    }


}
