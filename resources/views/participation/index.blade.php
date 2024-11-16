@extends('welcome')
@section('title', '| participation')


@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="btn-group float-right">


                                <ol class="breadcrumb hide-phone p-0 m-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}" >ACCUEIL</a></li>
                                <li class="breadcrumb-item active"><a href="{{ route('participation.create') }}" >Liste des participations</a></li>
                                </ol>
                            </div>
                            <h4 class="page-title">Starter</h4>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div class="alert alert-danger">
            <p>{{ $message }}</p>
        </div>
    @endif

<div class="col-12">
    <div class="card ">
        <div class="card-header  text-center">LISTE D'ENREGISTREMENT DES participations</div>
            <div class="card-body">
                <form method="POST" action="{{ route('search.participation') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3">
                            <label>Heure</label>
                            <select class="form-control" id="heure_id" name="heure_id" required="">
                                <option value="">Selectionner</option>
                                @foreach ($heures as $heure)
                                <option value="{{$heure->id}}"  {{$heure_id==$heure->id ? 'selected' : ''}}>{{$heure->designation}}</option>
                                    @endforeach

                            </select>
                        </div>
                        @if(Auth::user()->role=='admin')
                       <div class="col-3">
                            <label>Département</label>
                            <select class="form-control" id="departement_id" name="departement_id" >
                                <option value="">Selectionner</option>
        
                                @foreach ($departements as $departement)
                                    <option value="{{$departement->id}}" {{ $departement_id==$departement->id ? 'selected' : '' }}>{{$departement->nom}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-lg-3">
                            <label>Etat</label>
                            <select class="form-control" id="etat" name="etat"  >
                                <option value="">Selectionner</option>
                                <option value="reseigner" {{$etat=="reseigner" ? 'selected' : ''}}>Renseigner</option>
                                <option value="nonrenseigner" {{$etat=="nonrenseigner" ? 'selected' : ''}}>Non Renseigner</option>
                            </select>
                        </div>
        
    
                        <div class="col-lg-2">
                            <input type="submit" value="Valider" class="btn btn-primary" style="margin-top: 30px;">
                        </div>
                    </div>

                </form>
                <table id="datatable-buttons" class="table table-bordered table-responsive-md table-striped text-center">
                    <thead>
                        <tr>
                           
                          {{--   <th>Region</th> --}}
                            <th>Departement</th>
                            <th>Heure</th>
                            <th>nombre de votes</th>
                            <th>Centre de vote</th>
                            <th>Bureau  de vote</th>
                            
{{--                              <th>nombre de vote valide</th>
  --}}                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($participations as $participation)
                        <tr>
                         {{--    <td>{{ $participation->region->nom }}</td> --}}
                            
                            <td> @if (!empty($participation->departement))
                                
                               {{ $participation->departement }} @endif</td>
                            <td>@if (!empty($participation->heure)){{ $participation->heure }} @endif</td>
                            
                            <td>@if (!empty($participation->resultat)){{ $participation->resultat }} @endif</td>
                            <td>{{ $participation->centrevote }}</td>
                            <td>{{ $participation->lieuvote  }}</td>
                             <td>
                             @if($etat=="reseigner")   <a href="{{ route('participation.edit', $participation->id) }}" role="button" class="btn btn-primary"><i class="fas fa-edit"></i></a> @endif
                               @if (!empty($participation->id)) {!! Form::open(['method' => 'DELETE', 'route'=>['participation.destroy', $participation->id], 'style'=> 'display:inline', 'onclick'=>"if(!confirm('Êtes-vous sûr de vouloir supprimer cet enregistrement ?')) { return false; }"]) !!}
                                <button class="btn btn-danger"><i class="far fa-trash-alt"></i></button>
                                {!! Form::close() !!} @endif



                            </td>

                        </tr>
                        @endforeach

                    </tbody>
                </table>



            </div>

    </div>
</div>

@endsection
