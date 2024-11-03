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

                <table id="example1" class="table table-bordered table-responsive-md table-striped text-center">
                    <thead>
                        <tr>
                            <th>#</th>
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
                            <td>{{ $participation->id }}</td>
                         {{--    <td>{{ $participation->region->nom }}</td> --}}
                            
                            <td> @if ($participation->departement)
                                
                               {{ $participation->departement->nom }} @endif</td>
                            <td>{{ $participation->heure->designation }}</td>
                            
                            <td>{{ $participation->resultat }}</td>
                            <td>{{ $participation->lieuvote->centrevote->nom }}</td>
                            <td>{{ $participation->lieuvote->nom }}</td>
                             <td>
                               {{--  <a href="{{ route('participation.edit', $participation->id) }}" role="button" class="btn btn-primary"><i class="fas fa-edit"></i></a> --}}
                                {!! Form::open(['method' => 'DELETE', 'route'=>['participation.destroy', $participation->id], 'style'=> 'display:inline', 'onclick'=>"if(!confirm('Êtes-vous sûr de vouloir supprimer cet enregistrement ?')) { return false; }"]) !!}
                                <button class="btn btn-danger"><i class="far fa-trash-alt"></i></button>
                                {!! Form::close() !!}



                            </td>

                        </tr>
                        @endforeach

                    </tbody>
                </table>



            </div>

    </div>
</div>

@endsection
