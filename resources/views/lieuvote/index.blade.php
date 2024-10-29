@extends('welcome')
@section('title', '| lieuvote')


@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="btn-group float-right">


                                <ol class="breadcrumb hide-phone p-0 m-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}" >ACCUEIL</a></li>
                                <li class="breadcrumb-item active"><a href="{{ route('lieuvote.create') }}" >Liste des lieuvotes</a></li>
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
        <div class="card-header  text-center">LISTE D'ENREGISTREMENT DES lieuvotes</div>
            <div class="card-body">
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModalform2">
                    importer
                </button>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModalform3">
                    importer Bureau Temoin
                </button>

                
                <table  id="datatable-buttons" class="table table-bordered table-responsive-md table-striped text-center datatable-buttons">
                    <thead>
                        <tr>
                            {{-- <th>#</th>
                            <th>Numéro Bureau de Vote</th>
                            <th>Centre de vote</th>
                            <th>Commune</th>
                            <th>Temoin</th>
                            <th>Actions</th> --}}
                            <th>Region</th>
                            <th>Departement</th>
                            <th>Commune</th>
                            <th>Centre de vote</th>
                            <th>Bureau</th>
                            <th>Electeurs</th>
                            <th>etat</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($lieuvotes as $lieuvote)
                        {{-- <tr>
                            <td>{{ $lieuvote->id }}</td>
                            <td>{{ $lieuvote->nom }}</td>
                            <td>{{ $lieuvote->centrevote->nom }}</td>
                            <td>{{ $lieuvote->centrevote->commune->nom }}</td>
                            <td>{{ $lieuvote->temoin }}</td>
                            <td>
                                <a href="{{ route('lieuvote.edit', $lieuvote->id) }}" role="button" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                {!! Form::open(['method' => 'DELETE', 'route'=>['lieuvote.destroy', $lieuvote->id], 'style'=> 'display:inline', 'onclick'=>"if(!confirm('Êtes-vous sûr de vouloir supprimer cet enregistrement ?')) { return false; }"]) !!}
                                <button class="btn btn-danger"><i class="far fa-trash-alt"></i></button>
                                {!! Form::close() !!}



                            </td> --}}
                            <tr>
                                <td>{{ $lieuvote->region}}</td>
                                <td>{{ $lieuvote->departement }}</td>
                                <td>{{ $lieuvote->commune }}</td>
                                <td>{{ $lieuvote->centre }}</td>
                                <td>{{ $lieuvote->bureau }}</td>
                                <td>{{ $lieuvote->nb }}</td>
                                <td>{{ $lieuvote->etat==1 ? "deja renseigne" : "pas encore renseigne" }}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>



            </div>

    </div>
</div>
<div class="modal fade" id="exampleModalform2" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('importer.lieuvote') }}" method="POST" enctype="multipart/form-data">
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


<div class="modal fade" id="exampleModalform3" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('importer.temoin') }}" method="POST" enctype="multipart/form-data">
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
