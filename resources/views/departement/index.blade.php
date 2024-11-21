@extends('welcome')
@section('title', '| departement')


@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="btn-group float-right">

                                <ol class="breadcrumb hide-phone p-0 m-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}" >ACCUEIL</a></li>
                                <li class="breadcrumb-item active"><a href="{{ route('departement.create') }}">ENREGISTRER departement</a></li>
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
        <div class="card-header">LISTE D'ENREGISTREMENT DES Départements</div>
            <div class="card-body">
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModalform2">
                    importer
                </button>
                <table id="example1" class="table table-bordered table-responsive-md table-striped text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom département</th>
                            <th>NB Candidat</th>
                            <th>Nom region</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($departements as $departement)
                        <tr>
                            <td>{{ $departement->id }}</td>
                            <td>{{ $departement->nom }}</td>
                            <td>{{ $departement->nbcandidat }}</td>
                            <td>{{ $departement->region->nom }}</td>
                            <td>
                                <a href="{{ route('departement.edit', $departement->id) }}" role="button" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                {!! Form::open(['method' => 'DELETE', 'route'=>['departement.destroy', $departement->id], 'style'=> 'display:inline', 'onclick'=>"if(!confirm('Êtes-vous sûr de vouloir supprimer cet enregistrement ?')) { return false; }"]) !!}
                                <button class="btn btn-danger"><i class="far fa-trash-alt"></i></button>
                                {!! Form::close() !!}


                                <a href="{{ route('editer.rtsdepartement', $departement->id ) }}" role="button" title="Modifier les résultats" class="btn btn-primary"><i class="fas fa-edit"></i></a>

                            </td>

                        </tr>
                        @endforeach

                    </tbody>
                </table>



                <div class="modal fade" id="exampleModalform2" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('importer.departement') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">New message</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group no-margin">
                                            <label for="field-7" class="control-label">Document</label>
                                            <input type="file" name="file" class="form-control" required>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary">Valider</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
@endsection
