@extends('welcome')
@section('title', '| rtslieue')


@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="btn-group float-right">


                                <ol class="breadcrumb hide-phone p-0 m-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}" >ACCUEIL</a></li>
                                <li class="breadcrumb-item active"><a href="{{ route('rtslieue.create') }}" >Liste des rtslieues</a></li>
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
        <div class="card-header  text-center">LISTE D'ENREGISTREMENT DES rtslieues</div>
            <div class="card-body">

                <table id="datatable-buttons" class="table table-bordered table-responsive-md table-striped text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>centre de vote</th>
                            <th>Bureau vote</th>
                            <th>Candidat</th>
                            <th>nombre de votes</th>
{{--                              <th>nombre de vote valide</th>
  --}}                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($rtslieues as $rtslieue)
                        <tr>
                            <td>{{ $rtslieue->id }}</td>
                            <td>{{ $rtslieue->lieuvotee->centrevotee->nom }}</td>
                            <td>{{ $rtslieue->lieuvotee->nom }}</td>
                            <td>{{ $rtslieue->candidat->nom }}</td>
                            <td>{{ $rtslieue->nbvote }}</td>
{{--                              <td>{{ $rtslieue->nbvv }}</td>
  --}}                            <td>
                                <a href="{{ route('rtslieue.edit', $rtslieue->id) }}" role="button" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                {!! Form::open(['method' => 'DELETE', 'route'=>['rtslieue.destroy', $rtslieue->id], 'style'=> 'display:inline', 'onclick'=>"if(!confirm('Êtes-vous sûr de vouloir supprimer cet enregistrement ?')) { return false; }"]) !!}
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
