<?php

namespace App\Http\Controllers;

use App\Repositories\CollecteurRepository;
use Illuminate\Http\Request;

class CollecteurControlleur extends Controller
{
    protected $collecteurRepository;

    public function __construct(CollecteurRepository $collecteurRepository){
        $this->collecteurRepository =$collecteurRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collecteurs = $this->collecteurRepository->getAll();
        return view('collecteur.index',compact('collecteurs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('collecteur.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $collecteurs = $this->collecteurRepository->store($request->all());
        return redirect('collecteur');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $collecteur = $this->collecteurRepository->getById($id);
        return view('collecteur.show',compact('collecteur'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $collecteur = $this->collecteurRepository->getById($id);
        return view('collecteur.edit',compact('collecteur'));
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
        $this->collecteurRepository->update($id, $request->all());
        return redirect('collecteur');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->collecteurRepository->destroy($id);
        return redirect('collecteur');
    }
    public function verifierCollecteur(Request $request){
        $collecteur = $this->collecteurRepository->getCollecteurByTel($request["tel"]);
        return response()->json($collecteur);
    }
}
