@extends('welcome')
@section('title', '| bureau')


@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="btn-group float-right">
                <ol class="breadcrumb hide-phone p-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" >ACCUEIL</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('bureau.create') }}" >Liste des bureaus</a></li>
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
        <div class="card-header  text-center">LISTE D'ENREGISTREMENT DES bureaus</div>
            <div class="card-body">
                <form method="POST" action="{{ route('search.tel') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3">
                            <label>TEL</label>
                            <input type="text" name="tel" class="form form-control">
                        </div>
                        
                        <div class="col-lg-2">
                            <input type="submit" value="Valider" class="btn btn-primary" style="margin-top: 30px;">
                        </div>
                    </div>
                    
                </form>
                <br>
                <table id="datatable" class="table table-bordered table-responsive-md table-striped text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Prenom </th>
                            <th>Nom </th>
                            <th>Tel </th>
                            <th>Fonction </th>
                            <th>Profession </th>
                            <th>Bureau de vote </th>
                            <th>Centre de vote </th>
                            <th>Commune</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($bureaus as $bureau)
                        <tr>
                            <td>{{ $bureau->id }}</td>
                            <td>{{ $bureau->prenom }}</td>
                            <td>{{ $bureau->nom }}</td>
                            <td>{{ $bureau->tel }}</td>
                            <td>{{ $bureau->fonction }}</td>
                            <td>{{ $bureau->profession }}</td>
                            <td>{{ $bureau->lieuvote }}</td>
                            <td>{{ $bureau->centrevote }}</td>
                            <td>{{ $bureau->commune }}</td>
                           

                        </tr>
                        @endforeach

                    </tbody>
                </table>



            </div>

    </div>
</div>

@endsection
