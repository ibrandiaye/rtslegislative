@extends('welcome')
@section('title', '| Candidat')


@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="btn-group float-right">

                                <ol class="breadcrumb hide-phone p-0 m-0">
                                <li class="breadcrumb-item"><a href="#" role="button">ACCUEIL</a></li>
                                <li class="breadcrumb-item active"><a href="{{ route('candidat.index') }}" role="button" >Liste des candidats</a></li>
                                </ol>
                            </div><!-- /.col -->
                        </div>
                    </div>
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

    <div class="row">
            <div class="col-md-6 col-lg-3 col-xl-3">

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title font-20 mt-0">{{ $candidat->nom }}</h4>

                    </div>
                    <img class="rounded-circle" src="{{ asset('photo/'.$candidat->photo) }}" alt="Card image cap" style="height: 250px;">
                    <div class="card-body">
                       <h4> <p class="card-text text-center">{{round(($rts/$nbvotant)*100,2) }}%</p></h4>

                    </div>
                </div>

            </div><!-- end col -->
            <div class="col-md-12 col-lg-9 col-xl-9">

                <div class="card">
                    <div class="card-header">
                     <h3>Nombre de voix obtenues : {{ $rts }}</h3>
                    </div>
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered table-responsive-md table-striped text-center">
                            <thead>
                                <tr>
                                    <th>Region</th>
                                    <th>Departement</th>
                                    <th>Commune</th>
                                    <th>Centre de vote</th>
                                    <th>Resultat</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($resultats as $resultat)
                                <tr>
                                    <td>{{ $resultat->region }}</td>
                                    <td>{{ $resultat->departement }}</td>
                                    <td>{{ $resultat->commune }}</td>
                                    <td>{{ $resultat->nom }}</td>
                                    <td>{{ $resultat->nb }}</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            </div><!-- end col -->

    </div>
    <div class="row">
        <div class="col-md-6 col-lg-6 col-xl-6">

            <div class="card">
                <div class="card-header"><h3>Resultats Par Departement</h3></div>
                <div class="card-body">
                    <table id="datatable" class="table datatable table-bordered table-responsive-md table-striped text-center">
                        <thead>
                            <tr>
                                <th>Departement</th>
                                <th>Resultat</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($resultatDepartements as $resultatDepartement)
                            <tr>
                                <td>{{ $resultatDepartement->departement }}</td>

                                <td>{{ $resultatDepartement->nb }}</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

        </div><!-- end col -->
        <div class="col-md-12 col-lg-6 col-xl-6">

            <div class="card">
                <div class="card-header">
                 <h3>Resultats Par Region</h3>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-bordered datatable table-responsive-md table-striped text-center">
                        <thead>
                            <tr>
                                <th>Region</th>
                                <th>Resultat</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($resultatRegions as $resultatRegion)
                            <tr>
                                <td>{{ $resultatRegion->region }}</td>

                                <td>{{ $resultatRegion->nb }}</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

        </div><!-- end col -->
    </div>
</div>

@endsection
