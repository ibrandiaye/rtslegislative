<?php

namespace App\Http\Controllers;

use App\Models\Rtspays;
use App\Repositories\CandidatRepository;
use App\Repositories\PaysRepository;
use App\Repositories\JuridictionRepository;
use App\Repositories\RtspaysRepository;
use Illuminate\Http\Request;

class RtspaysController extends Controller
{
    protected $rtspaysRepository;
    protected $paysRepository;
    protected $candidatRepository;
    protected $juridictionRepository;

    public function __construct(RtspaysRepository $rtspaysRepository, PaysRepository $paysRepository,
    CandidatRepository $candidatRepository,JuridictionRepository $juridictionRepository){
        $this->rtspaysRepository =$rtspaysRepository;
        $this->paysRepository = $paysRepository;
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
        $rtspayss = $this->rtspaysRepository->getAll();
        return view('rtspays.index',compact('rtspayss'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       // $payss = $this->paysRepository->getAll();
        $candidats = $this->candidatRepository->getAll();
        $juridictions = $this->juridictionRepository->getAll();
        return view('rtspays.add',compact('candidats',"juridictions"));
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
        $rtspayss = $this->rtspaysRepository->getByPays($request["pays_id"]);
        if(count($rtspayss) > 0){
            $this->rtspaysRepository->deleteByPays($request["pays_id"]);
        }
       
      for ($i= 0; $i < count($rts); $i++) {
        $rtspays = new Rtspays();
        $rtspays->candidat_id = $candidats[$i];
        $rtspays->nbvote =(int)$rts[$i];
        $rtspays->pays_id = $request["pays_id"];
        $rtspays->save();
      }
         $this->paysRepository->updateEtat($request["pays_id"]);
         return redirect('rtspays');

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
        $rtspays = $this->rtspaysRepository->getById($id);
        return view('rtspays.show',compact('rtspays'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payss = $this->paysRepository->getAll();
        $rtspays = $this->rtspaysRepository->getById($id);
        $candidats = $this->candidatRepository->getAll();
        return view('rtspays.edit',compact('rtspays','payss','candidats'));
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

        $this->rtspaysRepository->update($id, $request->all());
        return redirect('rtspays');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->rtspaysRepository->destroy($id);
        return redirect('rtspays');
    }
   
}
