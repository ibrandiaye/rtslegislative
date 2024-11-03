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
                <div class="card-header  text-center">RESULTAT DEPARTEMENT : @if(!empty($departement)) {{$departement->nom}} @endif</div>
                    <div class="card-body">
                       
    
                                <table /*id="datatable-buttons"*/ class="table table-bordered table-striped text-center" style="width: 100% !iùportant;">
                                    <thead>
                                        <tr>
                
                                            <th> Ont OBTENU</th>
                                            <th>Voix</th>
                                            <th>% des voix</th>
                
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($rts as $rt)
                                        <tr>
                                            <td>{{ $rt->candidat }}</td>
                                            <td>{{ $rt->nb }}</td>
                                            <td>@if($votant>0){{ round(($rt->nb *100)/$votant,2)}} @endif</td>
                
                                        </tr>
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
                    <h6 class="badge badge-success" style="font-size: 17px ! important;">Nuls : {{$bullnull}}</h6><br>
                    <h6 class="badge badge-success" style="font-size: 17px ! important;">Exprimés : {{$votant - $bullnull}}</h6><br>
                    <h6 class="badge badge-success" style="font-size: 17px ! important;">Taux de participation : @if($inscrit>0){{ round(($votant*100)/$inscrit,2)}}% @endif</h6><br>
                    <h6 class="badge badge-success" style="font-size: 17px ! important;">Siège  : @if(!empty($departement)){{$departement->nbcandidat}} @endif</h6>
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
