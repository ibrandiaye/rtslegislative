<?php

namespace App\Http\Controllers;

use App\Repositories\ArrondissementRepository;
use App\Repositories\BureauRepository;
use App\Repositories\CentrevoteRepository;
use App\Repositories\CommuneRepository;
use App\Repositories\LieuvoteRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BureauController extends Controller
{
    protected $bureauRepository;
    protected $lieuvoteRepository;
    protected $centrevoteRepository;
    protected $communeRepository;
    protected $arrondissementRepository;

    public function __construct(BureauRepository $bureauRepository, LieuvoteRepository $lieuvoteRepository,CentrevoteRepository $centrevoteRepository,
    CommuneRepository $communeRepository,ArrondissementRepository $arrondissementRepository){
        $this->bureauRepository =$bureauRepository;
        $this->lieuvoteRepository = $lieuvoteRepository;
        $this->arrondissementRepository = $arrondissementRepository;
        $this->centrevoteRepository = $centrevoteRepository;
        $this->communeRepository = $communeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bureaus = $this->bureauRepository->getByUser();
        return view('bureau.index',compact('bureaus'));
    }

    public function searchTel(Request $request)
    {
        $bureaus = $this->bureauRepository->getTel($request->tel);
        return view('bureau.chercher',compact('bureaus'));
    }

    
    public function getByLieuVote($id)
    {
        $bureaus = $this->bureauRepository->getByLieuVote($id);
        return view('bureau.index',compact('bureaus'));
    }


    public function allBureauApi(){
        $bureaus = $this->bureauRepository->getAll();
        return response()->json($bureaus);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lieuvotes = $this->lieuvoteRepository->getAll();
        $communes = $this->communeRepository->getByArrondissement(Auth::user()->arrondissement_id);
        return view('bureau.add',compact('lieuvotes','communes'));
    }

    public function createByLieuVote($id,$commune)
    {
        $lieuvote_id = $id;
        $commune_id  = $commune;
        $bureaus = $this->bureauRepository->getByLieuVote($id);
        if(count($bureaus) >=3)
        {
            return redirect()->back()->with("error","Vous avez déja atteind les trois membre");
        }
        return view('bureau.add_lieuvote',compact('lieuvote_id','commune_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nom' => 'required',
            'prenom' => 'required',
            'fonction' => 'required|string',
            'tel' => 'required|unique:bureaus,tel|string|min:9|max:9',

            //'g-recaptcha-response' => 'required|captcha',
            ], [
                'tel.unique' => 'Cette personne est déjà affecté.',
                'tel.min' => 'la taille du tel doit être au minimun 9 caractères.',
                'tel.max' => 'la taille du tel doit être au maximum 9 caractères.',
                'cni.unique' => 'le numero cni est déjà affecté.',

            ]);
            $bureaus = $this->bureauRepository->getByLieuVoteOnly($request->lieuvote_id);
            if(count($bureaus) >=3)
            {
                return redirect()->back()->withErrors("Vous avez déja atteind les trois membre");
            }
            foreach ($bureaus as $key => $value) {

                 if($value->fonction==$request->fonction)
                 {
                    return redirect()->back()->withErrors("Cette fonction est déja occupé par : ".$value->prenom.' '.$value->nom.' '.$value->fonction)->withInput();
                 }
            }
        $bureau = $this->bureauRepository->store($request->all());
        if(count($bureaus) ==2)
        {
            $lieuvote = $this->lieuvoteRepository->getById($request->lieuvote_id);
            return redirect()->route("lieu.vote.by.centre",["id"=>$lieuvote->centrevote_id])->with("error","Vous avez terminer pour cette Bureau de vote");
        }
        return redirect()->back()->with("success","enregistrement avec succès");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bureau = $this->bureauRepository->getById($id);
        return view('bureau.show',compact('bureau'));
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
        $bureau = $this->bureauRepository->getById($id);
        return view('bureau.edit',compact('bureau','lieuvotes'));
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

        $this->bureauRepository->update($id, $request->all());
       // return redirect('bureau');
       return redirect()->back()->withInput()->with("success","modification avec succès");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->bureauRepository->destroy($id);
        return redirect('bureau');
    }

    public function docParBureau($id)
    {
        $bureaus = $this->bureauRepository->getByBureauVote($id);
        $arrondissement_id = 0;
        if(count($bureaus)>0 )
        {
            $arrondissement_id = $bureaus[0]->arrondissement_id;
        }
        $arrondissement = $this->arrondissementRepository->getOneArrondissementWithdepartementAndRegion($arrondissement_id);
        return view("bureau.doc",compact("bureaus","arrondissement"));
    }
    public function docParCentre($id)
    {
        $centrevote = $this->centrevoteRepository->getBureauByCentre($id);
        $arrondissement = $this->arrondissementRepository->getOneArrondissementWithdepartementAndRegion($centrevote->commune->arrondissement_id);
        return view("bureau.doc-centre",compact("centrevote","arrondissement"));
    }

    public function destroyByLieuVote($id)
    {
        $this->bureauRepository->destroyByLieuVote($id);
        return redirect()->back();
    }

    /*public function docParCentre($id)
    {
        $bureaus = $this->bureauRepository->getByBureauVote($id);
        $arrondissement = $this->arrondissementRepository->getOneArrondissementWithdepartementAndRegion(Auth::user()->arrondissement_id);
        return view("bureau.doc",compact("bureaus","arrondissement"));
    }*/

    public function chercherBureau()
    {
        $bureaus = [];
        return view("bureau.chercher",compact("bureaus"));
    }
}
