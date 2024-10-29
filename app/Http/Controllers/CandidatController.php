<?php

namespace App\Http\Controllers;

use App\Repositories\CandidatRepository;
use App\Repositories\RtscentreRepository;
use App\Repositories\RtslieuRepository;
use Illuminate\Http\Request;

class CandidatController extends Controller
{

    protected $candidatRepository;
    protected $rtslieuRepository;
    protected $rtscentreRepository;

    public function __construct(CandidatRepository $candidatRepository,RtslieuRepository $rtslieuRepository
    ,RtscentreRepository $rtscentreRepository){
        $this->candidatRepository =$candidatRepository;
        $this->rtslieuRepository = $rtslieuRepository;
        $this->rtscentreRepository = $rtscentreRepository;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $candidats = $this->candidatRepository->getAll();
        return view('candidat.index',compact('candidats'));
    }
    public function allCandidat()
    {
        $candidats = $this->candidatRepository->getAll();
        return response()->json($candidats);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('candidat.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request['image']){
            $destinationPath = 'photo/'; // upload path
            $file = $request['image'];
            $docName = time().".". $file->getClientOriginalExtension();
            $file->move($destinationPath, $docName);
            $request->merge(['photo'=>$docName]);
        }
      //  dd  ($request->all());
        $candidats = $this->candidatRepository->store($request->all());
        return redirect('candidat');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $candidat = $this->candidatRepository->getById($id);
        $resultats = $this->rtscentreRepository->rtsGroupByCentreByCandidat($id);
        $nbvotant = $this->rtscentreRepository->nbVotants();
        $rts = $this->rtscentreRepository->rtsByOneCandidat($id);
        //dd($resultats);
        $resultatRegions = $this->rtslieuRepository->rtsGroupByRegionByCandidat($id);
        $resultatDepartements = $this->rtslieuRepository->rtsGroupByDepartementByCandidat($id);
       // dd($resultatDepartements);
        return view('candidat.show',compact('candidat','rts','resultats','nbvotant',
    'resultatRegions','resultatDepartements'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $candidat = $this->candidatRepository->getById($id);
        return view('candidat.edit',compact('candidat'));
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
        if($request['image']){
            $destinationPath = 'photo/'; // upload path
            $file = $request['image'];
            $docName = time().".". $file->getClientOriginalExtension();
            $file->move($destinationPath, $docName);
            $request->merge(['photo'=>$docName]);
        }
        $this->candidatRepository->update($id, $request->all());
        return redirect('candidat');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->candidatRepository->destroy($id);
        return redirect('candidat');
    }
    public function allCandidats(){
        $candidat = $this->candidatRepository->getAll();
        return response()->json($candidat);
    }
}
