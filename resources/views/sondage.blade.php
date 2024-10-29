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
                        <h4 class="text-center">Sondage pour l'election présedentielle de 2024 </h4>
                    </div>
                </div>

            </div>
        </div>
        <div class="row" >
            <div class="col-md-12">
                <div class="card" style="border-radius: 10px; border : 2px solid #0099FF;">
                    <div class="card-header text-center">
                        <h7 class="text-center">Votants : {{ $total }} </h7>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            @foreach ($candidats as $candidat)
                <div class="col-md-6 col-lg-6 col-xl-3">

                    <div class="card" style="border-radius: 10px; border : 2px solid #ED1C23;;">
                        <div class="card-body">
                            <h4 class="card-title font-20 mt-0 text-center">{{ $candidat->nom }}</h4>
                        </div>
                        <img class="rounded-circle" src="{{ asset('photo/'.$candidat->photo) }}" alt="Card image cap" style="height: 200px;">
                     <div class="card-body">
                        @foreach ($rtsParCandidats as $rtsParCandidat)
                            @if( $rtsParCandidat->nom==$candidat->nom )
                              <h4 style="border-radius: 10px; border : 2px solid #0099FF;"> <p class="card-text text-center" style="margin-bottom: 3px;margin-top: 3px;">{{round(($rtsParCandidat->nb/$votants)*100,2) }}% </p>
                            <p class="card-text text-center">Votants : {{ $rtsParCandidat->nb }}</p>
                            </h4>
                              @endif
                              @endforeach
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-lg btn-block btn-outline-danger waves-effect waves-light" data-toggle="modal" data-animation="bounce" data-target="#bs-example-modal-center{{ $candidat->id }}">Voter</button>

                     </div>
                    </div>


                </div><!-- end col -->
                <div class="modal fade " id="bs-example-modal-center{{ $candidat->id }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"> Voter Pour : {{ $candidat->nom }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('sondage.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" value="{{ $candidat->id }}" name="candidat_id">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email"  value="{{ old('email') }}" class="form-control"  required>
                                        </div>
                                    </div>
                                    <center><button type="submit" class="btn btn-success btn btn-lg "> ENREGISTRER</button></center>

                                </form>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
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
