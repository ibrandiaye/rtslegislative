<?php

namespace App\Http\Controllers;

use App\Repositories\HeureRepository;
use Illuminate\Http\Request;

class HeureController extends Controller
{
    protected $heureRepository;

    public function __construct(HeureRepository $heureRepository){
        $this->heureRepository =$heureRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $heures = $this->heureRepository->getAll();
        return view('heure.index',compact('heures'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('heure.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $heures = $this->heureRepository->store($request->all());
        return redirect('heure');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $heure = $this->heureRepository->getById($id);
        return view('heure.show',compact('heure'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $heure = $this->heureRepository->getById($id);
        return view('heure.edit',compact('heure'));
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
        $this->heureRepository->update($id, $request->all());
        return redirect('heure');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->heureRepository->destroy($id);
        return redirect('heure');
    }
 
    
}
