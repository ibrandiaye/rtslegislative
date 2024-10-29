<?php

namespace App\Http\Controllers;

use App\Models\Rtscentree;
use App\Repositories\CandidatRepository;
use App\Repositories\CentrevoteeRepository;
use App\Repositories\JuridictionRepository;
use App\Repositories\RtscentreeRepository;
use Illuminate\Http\Request;

class RtscentreeController extends Controller
{
    protected $rtscentreeRepository;
    protected $centrevoteeRepository;
    protected $candidatRepository;
    protected $juridictionRepository;

    public function __construct(RtscentreeRepository $rtscentreeRepository, CentrevoteeRepository $centrevoteeRepository,
    CandidatRepository $candidatRepository,JuridictionRepository $juridictionRepository){
        $this->rtscentreeRepository =$rtscentreeRepository;
        $this->centrevoteeRepository = $centrevoteeRepository;
        $this->candidatRepository = $candidatRepository;
        $this->juridictionRepository = $juridictionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rtscentrees = $this->rtscentreeRepository->getAll();
        return view('rtscentree.index',compact('rtscentrees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       // $centrevotees = $this->centrevoteeRepository->getAll();
        $candidats = $this->candidatRepository->getAll();
        $juridictions = $this->juridictionRepository->getAll();
        return view('rtscentree.add',compact('candidats',"juridictions"));
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
        return redirect()->back()->withErrors(["erreur"=>"Les resutat ne peuvent être superieur au nombre d'inscrit"]);

      }
      else
      {
        $rtscentrees = $this->rtscentreeRepository->getByCentre($request["centrevotee_id"]);
        if(count($rtscentrees) > 0){
            $this->rtscentreeRepository->deleteByCentre($request["centrevotee_id"]);
        }
       
      for ($i= 0; $i < count($rts); $i++) {
        $rtscentree = new Rtscentree();
        $rtscentree->candidat_id = $candidats[$i];
        $rtscentree->nbvote =(int)$rts[$i];
        $rtscentree->centrevotee_id = $request["centrevotee_id"];
        $rtscentree->save();
      }
         $this->centrevoteeRepository->updateNiveau($request["centrevotee_id"]);
         return redirect('rtscentree');

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
        $rtscentree = $this->rtscentreeRepository->getById($id);
        return view('rtscentree.show',compact('rtscentree'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $centrevotees = $this->centrevoteeRepository->getAll();
        $rtscentree = $this->rtscentreeRepository->getById($id);
        $candidats = $this->candidatRepository->getAll();
        return view('rtscentree.edit',compact('rtscentree','centrevotees','candidats'));
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

        $this->rtscentreeRepository->update($id, $request->all());
        return redirect('rtscentree');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->rtscentreeRepository->destroy($id);
        return redirect('rtscentree');
    }
   

}
