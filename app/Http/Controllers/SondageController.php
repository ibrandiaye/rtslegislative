<?php

namespace App\Http\Controllers;

use App\Http\Requests\SondageRequest;
use App\Repositories\CandidatRepository;
use App\Repositories\SondageRepository;
use Illuminate\Http\Request;

class SondageController extends Controller
{
    protected $sondageRepository;

    protected $candidatRepository;

    public function __construct(SondageRepository $sondageRepository,
    CandidatRepository $candidatRepository){
        $this->sondageRepository =$sondageRepository;
        $this->candidatRepository = $candidatRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sondages = $this->sondageRepository->getAll();
        return view('sondage.index',compact('sondages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
 $candidats = $this->candidatRepository->getAll();
              /*  return view('sondage.add',compact('candidats')); */
        $rtsParCandidats = $this->sondageRepository->rtsSondageByCandidat();
        $total  = 0;
        foreach ($rtsParCandidats as $key => $value) {
            $total = $total + $value->nb;
        }
        $votants = $this->sondageRepository->nbSondage();
       // dd($votants);
        return view("sondage",compact('rtsParCandidats','votants','candidats','total'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SondageRequest $request)
    {
      //  var_dump($request);
      $request->merge(['ipaddress' =>request()->ip()]);
      $sondages = $this->sondageRepository->store($request->all());
        return redirect('sondage/create');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sondage = $this->sondageRepository->getById($id);
        return view('sondage.show',compact('sondage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sondage = $this->sondageRepository->getById($id);
        $candidats = $this->candidatRepository->getAll();
        return view('sondage.edit',compact('sondage','candidats'));
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

        $this->sondageRepository->update($id, $request->all());
        return redirect('sondage');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->sondageRepository->destroy($id);
        return redirect('sondage');
    }
    public function sondage(){
        $rtsParCandidats = $this->sondageRepository->rtsSondageByCandidat();
        return view("sondage",compact('rtsParCandidats'));
    }
}
