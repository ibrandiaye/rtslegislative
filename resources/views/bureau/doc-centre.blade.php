<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>DGE </title>
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
            .page-break{
                page-break-after: always;
            }
            td{
                font-size: 18px;
            }
                #sa-params{
                    display: none;
                }
                html{
                    background: white;
                }
                div
                {
                    text-align: center;
                    margin-top: 0px !important;
                    margin-bottom: 0px !important;
                }
                table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
  text-align:  center;
}
table{
    width: 100%;
}
  
        </style>
        @foreach ($centrevote->lieuvotes as $lieuvote)

        
            
       

    <div class="container">



        <!-- Begin page -->

    <div class="row">
        
                <h5>REPUBLIQUE DU SENEGAL </h5><br/>
    </div>

    <div class="row">
        <h6>Un Peuple-Un But-une Foi </h6><br/>

    </div>
    <div class="row">
        <h6>Region de  {{$arrondissement->departement->region->nom}}</h6><br>

    </div>
    <div class="row">
        <h6>Departement de  {{$arrondissement->departement->nom}}</h6><br>

    </div>
    <div class="row">
        <h6>Arrondissement de {{$arrondissement->nom}}</h6><br>

    </div>
    <div class="row">
        <h6>Commune de {{$centrevote->commune->nom}} </h6><br>

    </div>


    <div class="row text-center">
        <div class="col-4"></div>
    
        <div class="col-4">
            <h6><strong>Menbre de Bureau vote</strong></h6>
        </div>
    </div>
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <h6>Bureau  de vote {{ $centrevote->nom}}</h6>

        </div>
    </div>
    <div class="row">
        <div class="col-4"></div>
        <div class="col-4">
            <h6>Bureau de vote numéro {{ $lieuvote->nom}} </h6>

        </div>

    </div>
    
        

    <div class="row">
    
        <div class="col-sm-12">

            <table   class="table table-bordered  table-striped text-center ">
                <thead >
                    <tr>
                        <th>Fonction</th>
                        <th>Prenom et Nom</th>
                       
                        <th>Profession</th>
                
                    </tr>
                </thead>
                <tbody>
                
                    @foreach ($lieuvote->bureaus as $bureau)
                            <tr>
                                <td>{{ $bureau->fonction }}</td>
                                <td>{{ $bureau->prenom }} {{ $bureau->nom }}</td>
                               
                                <td>{{ $bureau->profession }}</td>


                            </tr>
                    @endforeach        
                </tbody>
                    
            </table>
    
                
        </div>
        <div class="col-sm-1">

        </div>
    </div>
    </div>

<div class="page-break"></div>
@endforeach
  
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>


    </body>
</html>
