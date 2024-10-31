@extends('welcome')
@section('title', '| rtslieu')


@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="btn-group float-right">


                                <ol class="breadcrumb hide-phone p-0 m-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}" >ACCUEIL</a></li>
                                <li class="breadcrumb-item active"><a href="{{ route('rtslieu.create') }}" >Liste des rtslieus</a></li>
                                </ol>
                            </div>
                            <h4 class="page-title">DGE</h4>
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
        <div class="card-header  text-center">RESULTAT Nationnal</div>
            <div class="card-body">

                <div class="row">
                    <div class="col-8">
                        <table /*id="datatable-buttons"*/ class="table table-bordered table-responsive-md table-striped text-center">
                            <thead>
                                <tr>

                                    <th> Ont OBTENU</th>
                                    <th>Voix</th>
                                    <th>% des Voix</th>
                                    <th>Proportionnel</th>
                                    <th>Majortiaire</th>
                                    <th>Siege</th>
                                    <th>Restant</th>

                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($resultats as $parti => $sieges)
                            @if(!empty($parti))
                                <tr>

                                    <td>{{ $parti }}</td>
                                    <td>{{ $sieges['nb']}}</td>
                                    <td>@if($totalVotants>0){{ round(($sieges['nb'] *100)/$totalVotants,2)}}% @endif</td>

                                    <td>{{ $sieges['proportionnel'] ?? 0 }}</td>
                                    <td>{{ $sieges['majoritaire'] ?? 0 }}</td>
                                    <td>{{ $sieges['total'] ?? 0 }}</td>
                                    <td>{{ $sieges['restant']  ?? 0 }}</td>

                                </tr>
                                @endif
                                @endforeach

                            </tbody>
                        </table>


                    </div>
                    <div class="col-4">
                        <h6>Inscrits : {{$inscrit}}</h6>
                        <h6>Votant : {{$votant}}</h6>
                        <h6>Nuls : {{$bulletinnull}}</h6>
                        <h6>Exprimés : {{$votant - $bulletinnull}}</h6>
                        <h6>Taux de participation : @if($inscrit>0){{ round(($votant*100)/$inscrit,2)}}% @endif</h6>
                        <h6>Qutotient  : {{ $quotiant }}</h6>
                    </div>
                </div>



            </div>

    </div>
</div>

@endsection
