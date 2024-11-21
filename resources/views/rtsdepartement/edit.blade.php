{{-- \resources\views\permissions\create.blade.php --}}
@extends('welcome')

@section('title', '| Modifier Région')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="btn-group float-right">

                        <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" role="button" class="btn btn-primary">ACCUEIL</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('rtsdepartement.index') }}" role="button" class="btn btn-primary">RETOUR</a></li>

                        </ol>

                    </div>
                    <h4 class="page-title">Starter</h4>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <form action="{{ route('update.perso.rtsdepartement') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @csrf
             <div class="card ">
                        <div class="card-header text-center">FORMULAIRE DE MODIFICATION D'une rtsdepartement</div>
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="row">
                                    <input type="hidden" name="departement_id"  value="{{ $departement->id }}" required>
                                    <div class="col-4">
                                        <div class="row">
                                            <div class="col-12">
                                                <label>Région</label>
                                                <select class="form-control" id="region_id" name="region_id" required="">


                                                    <option value="{{$region->id}}">{{$region->nom}}</option>


                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label>Département</label>
                                                <select class="form-control" id="departement_id" name="departement_id" required>

                                                    <option value="{{$departement->id}}" >{{$departement->nom}}</option>

                                                </select>
                                            </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label>Votant </label>
                                                        <input type="number" name="votant" id="votant"  value="{{ $departement->total }}" class="form-control"  required>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label>Nuls </label>
                                                        <input type="number" name="bulnull" id="bulnull"  value="{{ $departement->null }}" class="form-control"  required>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label>Hors Bureau </label>
                                                        <input type="number" name="hs"  value="{{ $departement->hb }}" class="form-control"  >
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label>Suffrage valablement Exprimé </label>
                                                        <input type="number" name="suffval" id="suffval"  value="{{  $departement->total - $departement->null }}" class="form-control"  required>
                                                    </div>
                                                </div>

                                        </div>

                                    </div>
                                    <div class="col-8">
                                        <table class="table table-bordered table-responsive-md table-striped text-center">
                                            <thead>
                                                <th>Liste</th>
                                                <th>Resultat</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($rtsdepartement as $candidat )
                                                <tr>
                                                    <td>
                                                        <label> {{ $candidat->coalition }} <img src="{{ asset('photo/'.$candidat->photo) }}" class="img img-rounded" style="height: 30px;"></label>
                                                    </td>
                                                    <td><input type="number" name="nbvote[]" data-parsley-min="0" data-parsley-type="number"  value="{{ $candidat->nbvote }}" class="form-control"  required>
                                                        <input type="hidden" name="candidat_id[]" value="{{ $candidat->id }}">
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div>
                                    <input type="hidden" id="nb_electeur" name="nb_electeur" value="{{ $nbVote }}">


                                <div>
                                    <center>
                                        <button type="submit" class="btn btn-success btn btn-lg "> MODIFIER</button>
                                    </center>
                                </div>


                            </div>
                        </div>
    {!! Form::close() !!}


@endsection
