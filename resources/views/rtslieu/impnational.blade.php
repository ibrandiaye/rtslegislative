<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>DGE</title>
        <meta content="Admin Dashboard" name="description" />
        <meta content="Mannatthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        {{--<link href="{{ asset('css/icons.css') }}" rel="stylesheet" type="text/css"> --}}
        <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css">

    </head>
    <body style="background: white;">
        <style>
                p{
                    font-size:18px;
                }
               input
               {
                width: 50px;
               }
        </style>


    <!-- Begin page -->
<div class="content">
    <div class="row">
        <div class="col-8">
            <div class="card ">
                <div class="card-header  text-center">RESULTAT Nationnal</div>
                    <div class="card-body">
    
                                <table /*id="datatable-buttons"*/ class="table table-bordered  table-striped text-center" style="width: 100%;">
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
        
            </div>
        </div>
        <div class="col-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Informations Générales</h5>
                    <h6 class="badge badge-success" style="font-size: 17px ! important;">Inscrits : {{$inscrit}}</h6><br>
                    <h6 class="badge badge-success" style="font-size: 17px ! important;">Votant : {{$votant}}</h6><br>
                    <h6 class="badge badge-success" style="font-size: 17px ! important;">Nuls : {{$bulletinnull}}</h6><br>
                    <h6 class="badge badge-success" style="font-size: 17px ! important;">Exprimés : {{$votant - $bulletinnull}}</h6><br>
                    <h6 class="badge badge-success" style="font-size: 17px ! important;">Taux de participation : @if($inscrit>0){{ round(($votant*100)/$inscrit,2)}}% @endif</h6><br>
                    <h6 class="badge badge-success" style="font-size: 17px ! important;">Qutotient  : {{ $quotiant }}</h6><br>
                </div>
            </div>
        </div>
        </div>
</div>
		 
 <!-- <div class="page-break"></div>
       jQuery  -->
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>


    </body>
</html>
