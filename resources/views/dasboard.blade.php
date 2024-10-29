<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>Zoter - Responsive Bootstrap 4 Admin Dashboard</title>
        <meta content="Admin Dashboard" name="description" />
        <meta content="Mannatthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/icons.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css">

    </head>
    <body>


    <!-- Begin page -->
    <div class="container">
        <div class="row" style="margin-top: 10px;">
            <div class="col-md-12">
                <div class="card" style="border-radius: 10px; border : 2px solid #0099FF;">
                    <div class="card-header">
                        <h4 class="text-center">Résultats de l'election présedentielle de 2024 </h4>
                    </div>
                </div>

            </div>
        </div>
        <br>
        <div class="row" >

            @foreach ($rtsParCandidats as $rtsParCandidat)
                <div class="col-md-6 col-lg-6 col-xl-3">

                    <div class="card" style="border-radius: 10px; border : 2px solid #ED1C23;">
                        <div class="card-body">
                            <h4 class="card-title font-20 mt-0 text-center">{{ $rtsParCandidat->nom }}</h4>
                            <p class=" text-center">{{  $rtsParCandidat->coalition }}</p>
                        </div>
                        <img class="rounded-circle" src="{{ asset('photo/'.$rtsParCandidat->photo) }}" alt="Card image cap" style="height: 200px;">
                        <div class="card-body">
                           <h4 style="border-radius: 10px; border : 2px solid #0099FF;"> <p class="card-text text-center text-danger" style="margin-bottom: 3px;margin-top: 3px;">{{round(($rtsParCandidat->nb/$votants)*100,2) }}%</p></h4>

                        </div>
                    </div>

                </div><!-- end col -->
                @endforeach
        </div>
    </div>



        <!-- jQuery  -->
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/popper.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/modernizr.min.js') }}"></script>
        <script src="{{ asset('js/detect.js') }}"></script>
        <script src="{{ asset('js/fastclick.js') }}"></script>
        <script src="{{ asset('js/jquery.blockUI.js') }}"></script>
        <script src="{{ asset('js/waves.js') }}"></script>
        <script src="{{ asset('js/jquery.nicescroll.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('js/app.js') }}"></script>


    </body>
</html>
