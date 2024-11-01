{{-- \resources\views\permissions\create.blade.php --}}
@extends('welcome')

@section('title', '| Tableau de bord')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="btn-group float-right">
                <ol class="breadcrumb hide-phone p-0 m-0">
                    <li class="breadcrumb-item-active"><a href="{{ route('home') }}">Election 2024</a></li>
                </ol>
            </div>
            <h4 class="page-title">Tableau de Bord</h4>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
@if (Auth::user()->role=="admin")
{{-- <h4>Diaspora : {{ $electeursDiaspora }} inscrits</h4>
<div class="row">


    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="col-3 align-self-center">
                        <div class="round">
                            <i class="mdi mdi-eye"></i>
                        </div>
                    </div>
                    <div class="col-9 align-self-center text-right">
                        <div class="m-l-10">
                            <h5 class="mt-0">{{ $nbVotantDiaspora + $nullEtrangers}}</h5>
                            <p class="mb-0 text-muted">Nombre de votants <span class="badge bg-soft-success"></span></p>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3" style="height:3px;">
                    <div class="progress-bar  bg-success" role="progressbar" style="width: 35%;" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div><!--end col-->


     <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="search-type-arrow"></div>
                <div class="d-flex flex-row">
                    <div class="col-3 align-self-center">
                        <div class="round ">
                            <i class="mdi mdi-cart"></i>
                        </div>
                    </div>
                    <div class="col-9 align-self-center text-right">
                        <div class="m-l-10 ">
                            <h5 class="mt-0">{{ $nullEtrangers}}</h5>
                            <p class="mb-0 text-muted">Nombre de bulletin null </p>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3" style="height:3px;">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 61%;" aria-valuenow="61" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div><!--end col-->
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="col-3 align-self-center">
                        <div class="round">
                            <i class="mdi mdi-eye"></i>
                        </div>
                    </div>
                    <div class="col-9 align-self-center text-right">
                        <div class="m-l-10">
                            <h5 class="mt-0">{{ $nbVotantDiaspora  }}</h5>
                            <p class="mb-0 text-muted">surfrage valablement exprime <span class="badge bg-soft-success"></span></p>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3" style="height:3px;">
                    <div class="progress-bar  bg-success" role="progressbar" style="width: 35%;" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div><!--end col-->
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="search-type-arrow"></div>
                <div class="d-flex flex-row">
                    <div class="col-3 align-self-center">
                        <div class="round ">
                            <i class="mdi mdi-cart"></i>
                        </div>
                    </div>
                    <div class="col-9 align-self-center text-right">
                        <div class="m-l-10 ">
                            <h5 class="mt-0">@if($nbVotantDiaspora >0){{ round(($nbVotantDiaspora/$electeursDiaspora)*100,2)  }}%
                                @else 0%
                                @endif</h5>
                            <p class="mb-0 text-muted">% de Participation</p>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3" style="height:3px;">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 61%;" aria-valuenow="61" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div><!--end col-->
    </div>  --}}
    <h4>National : {{ $electeurs }} inscrits</h4>
<div class="row">

    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="search-type-arrow"></div>
                <div class="d-flex flex-row">
                    <div class="col-3 align-self-center">
                        <div class="round ">
                            <i class="mdi mdi-cart"></i>
                        </div>
                    </div>
                    <div class="col-9 align-self-center text-right">
                        <div class="m-l-10 ">
                            <h5 class="mt-0">{{ $votants + $nullNational}}</h5>
                          <a href="{{ route('nbVoteStat') }}">  <p class="mb-0 text-muted">Nombre de votants</p></a>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3" style="height:3px;">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 61%;" aria-valuenow="61" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div><!--end col-->

     <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="search-type-arrow"></div>
                <div class="d-flex flex-row">
                    <div class="col-3 align-self-center">
                        <div class="round ">
                            <i class="mdi mdi-cart"></i>
                        </div>
                    </div>
                    <div class="col-9 align-self-center text-right">
                        <div class="m-l-10 ">
                            <h5 class="mt-0">{{$nullNational}}
                            </h5>
                            <p class="mb-0 text-muted">Nombre bulletin null</p>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3" style="height:3px;">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 61%;" aria-valuenow="61" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div><!--end col-->
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="col-3 align-self-center">
                        <div class="round">
                            <i class="mdi mdi-eye"></i>
                        </div>
                    </div>
                    <div class="col-9 align-self-center text-right">
                        <div class="m-l-10">
                            <h5 class="mt-0">{{ $votants  }}</h5>
                            <p class="mb-0 text-muted">surfrage valablement exprime <span class="badge bg-soft-success"></span></p>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3" style="height:3px;">
                    <div class="progress-bar  bg-success" role="progressbar" style="width: 35%;" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div><!--end col-->
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="search-type-arrow"></div>
                <div class="d-flex flex-row">
                    <div class="col-3 align-self-center">
                        <div class="round ">
                            <i class="mdi mdi-cart"></i>
                        </div>
                    </div>
                    <div class="col-9 align-self-center text-right">
                        <div class="m-l-10 ">
                            <h5 class="mt-0">@if($votants >0)
                                {{ round( ($votants/$electeurs)*100,2) }}% @else 0%
                                @endif
                            </h5>
                            <p class="mb-0 text-muted">Taux de Participation</p>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3" style="height:3px;">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 61%;" aria-valuenow="61" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div><!--end col-->
    {{-- <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="col-3 align-self-center">
                        <div class="round">
                            <i class="mdi mdi-eye"></i>
                        </div>
                    </div>
                    <div class="col-9 align-self-center text-right">
                        <div class="m-l-10">
                            <h5 class="mt-0">{{ $nbCentrevotes }}</h5>
                            <p class="mb-0 text-muted">Nombre de Bureaux votes <span class="badge bg-soft-success"></span></p>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3" style="height:3px;">
                    <div class="progress-bar  bg-success" role="progressbar" style="width: 35%;" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div><!--end col-->




    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="col-3 align-self-center">
                        <div class="round">
                            <i class="mdi mdi-eye"></i>
                        </div>
                    </div>
                    <div class="col-9 align-self-center text-right">
                        <div class="m-l-10">
                            <h5 class="mt-0">{{ $tauxDepouillement }}%</h5>
                            <p class="mb-0 text-muted">Depouillement Par departement  {{$nbRtsCentre }} sur  {{$nbCentrevotes - $nbRtsCentre}} <span class="badge bg-soft-success"></span></p>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3" style="height:3px;">
                    <div class="progress-bar  bg-success" role="progressbar" style="width: 35%;" aria-valuenow="{{ $tauxDepouillement }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div><!--end col-->

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="col-3 align-self-center">
                        <div class="round">
                            <i class="mdi mdi-account-multiple-plus"></i>
                        </div>
                    </div>
                    <div class="col-9 text-right align-self-center">
                        <div class="m-l-10 ">
                            <h5 class="mt-0">{{ $tauxDepouillementElecteurs }}%</h5>
                            <p class="mb-0 text-muted">Taux de Participation</p>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3" style="height:3px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 48%;" aria-valuenow="48" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div><!--end col-->
    --}}
</div>
{{-- <h4>Général : {{ $electeurs + $electeursDiaspora}} inscrits</h4>
<div class="row">

    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="search-type-arrow"></div>
                <div class="d-flex flex-row">
                    <div class="col-3 align-self-center">
                        <div class="round ">
                            <i class="mdi mdi-cart"></i>
                        </div>
                    </div>
                    <div class="col-9 align-self-center text-right">
                        <div class="m-l-10 ">
                            <h5 class="mt-0">{{ $votants + $nbVotantDiaspora+ $nullNational + $nullEtrangers}}</h5>
                            <p class="mb-0 text-muted">Nombre de votants</p>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3" style="height:3px;">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 61%;" aria-valuenow="61" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div><!--end col-->

     <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="search-type-arrow"></div>
                <div class="d-flex flex-row">
                    <div class="col-3 align-self-center">
                        <div class="round ">
                            <i class="mdi mdi-cart"></i>
                        </div>
                    </div>
                    <div class="col-9 align-self-center text-right">
                        <div class="m-l-10 ">
                            <h5 class="mt-0">{{$nullNational+$nullEtrangers}}
                            </h5>
                            <p class="mb-0 text-muted">Nombre bulletin null</p>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3" style="height:3px;">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 61%;" aria-valuenow="61" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div><!--end col-->
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="col-3 align-self-center">
                        <div class="round">
                            <i class="mdi mdi-eye"></i>
                        </div>
                    </div>
                    <div class="col-9 align-self-center text-right">
                        <div class="m-l-10">
                            <h5 class="mt-0">{{ $votants+$nbVotantDiaspora }}</h5>
                            <p class="mb-0 text-muted">surfrage valablement exprime <span class="badge bg-soft-success"></span></p>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3" style="height:3px;">
                    <div class="progress-bar  bg-success" role="progressbar" style="width: 35%;" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div><!--end col-->
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="search-type-arrow"></div>
                <div class="d-flex flex-row">
                    <div class="col-3 align-self-center">
                        <div class="round ">
                            <i class="mdi mdi-cart"></i>
                        </div>
                    </div>
                    <div class="col-9 align-self-center text-right">
                        <div class="m-l-10 ">
                            <h5 class="mt-0">@if($votants >0)
                                {{round ( (($votants+$nbVotantDiaspora)/($electeurs+$electeursDiaspora))*100,2) }}% @else 0%
                                @endif
                            </h5>
                            <p class="mb-0 text-muted">Taux de Participation</p>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3" style="height:3px;">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 61%;" aria-valuenow="61" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div><!--end col-->
</div> --}}
<h4>Taux de Participation</h4>
<div class="row">
    @foreach($tauxDeParticipations as $tauxDeParticipation)
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="col-3 align-self-center">
                        <div class="round">
                            <i class="mdi mdi-eye"></i>
                        </div>
                    </div>
                    <div class="col-9 align-self-center text-right">
                        <div class="m-l-10">
                            <h5 class="mt-0">{{round(((int)$tauxDeParticipation->nb/$nbElecteursTemoin)*100,2) }}%</h5>
                            <p class="mb-0 text-muted"><a href="{{ route('participation.heure', ['heure'=>$tauxDeParticipation->designation]) }}">  à {{$tauxDeParticipation->designation}} </a><span class="badge bg-soft-success"></span></p>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3" style="height:3px;">
                    <div class="progress-bar  bg-success" role="progressbar" style="width: 35%;" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div><!--end col-->
    @endforeach

</div>
 <h4>Resultat Bureau Temoin</h4>

<div class="row">
    @foreach ($rtsTemoins as $rtsTemoin)
    <div class="col-md-3" >


        <div class="card" >
            <div class="row">
                <div class="col-2">
                    <img class="rounded-circle" src="{{ asset('photo/'.$rtsTemoin->photo) }}" alt="Card image cap" style="height: 100px;width: 100px;">
                </div>
                <div class="col-10">
                    <a href="{{ route('candidat.show', ['candidat'=>$rtsTemoin->id]) }}"  > <p style="font-weight: 700;font-size: small;text-align: center!important;margin-right: 5px;margin-top: 5px;">{{ $rtsTemoin->nom }}</p></a>
                    <h4>
                        <p class="card-text text-center text-danger">{{round(((int)$rtsTemoin->nb/$nbVotantTemoin)*100,2) }}%</p>
                    </h4>
                </div>
            </div>

        </div>
        &nbsp;&nbsp;
    </div>
 @endforeach
</div>
<h4>Resultat Bureau National</h4>

<div class="row">

    @foreach ($rtsParCandidats  as $k =>  $rtsParCandidat)
    @if($k==0)
    <div class="col-md-6 col-lg-4">
    </div>
    <div class="col-md-6 col-lg-6 col-xl-3 ">

        <div class="card">
            <div class="card-body">
             <a href="{{ route('candidat.show', ['candidat'=>$rtsParCandidat->id]) }}"> <h4 class="card-title font-20 mt-0">{{ $rtsParCandidat->nom }}</h4></a>

            </div>

            <img class="rounded-circle" src="{{ asset('photo/'.$rtsParCandidat->photo) }}" alt="Card image cap" style="height: 250px;">
            <div class="card-body">
               <h4> <p class="card-text text-center">{{round(((int)$rtsParCandidat->nb/($votants + $nbVotantDiaspora))*100,2) }}%</p>
                <br>{{$rtsParCandidat->nb}} voix</p></h4>

            </div>
        </div>

    </div>
    <div class="col-md-3 col-lg-4">
    </div>
    @else
        <div class="col-md-3" >


            <div class="card" >
                <div class="row">
                    <div class="col-2">
                        <img class="rounded-circle" src="{{ asset('photo/'.$rtsParCandidat->photo) }}" alt="Card image cap" style="height: 100px;width: 100px;">
                    </div>
                    <div class="col-10">
                        {{--   <p style="font-weight: 700;font-size: small;text-align: center!important;margin-right: 5px;margin-top: 5px;">{{ $rtsParCandidat->nom }}</p> --}}
                      <a href="{{ route('candidat.show', ['candidat'=>$rtsParCandidat->id]) }}"  > <p style="font-weight: 700;font-size: small;text-align: center!important;margin-right: 5px;margin-top: 5px;">{{ $rtsParCandidat->nom }}</p></a>
                        <h4>
                            <p class="card-text text-center text-danger">@if ((int)$rtsParCandidat->nb > 0 && $votants > 0)
                                {{round(((int)$rtsParCandidat->nb/($votants+$nbVotantDiaspora))*100,2) }}%
                            <br>{{$rtsParCandidat->nb}} voix</p>

                            @endif

                        </h4>
                    </div>
                </div>

            </div>
            &nbsp;&nbsp;
        </div>
        @endif
     @endforeach

   {{--
        <div class="col-md-6 col-lg-6 col-xl-3">

            <div class="card">
                <div class="card-body">
                 <a href="{{ route('candidat.show', ['candidat'=>$rtsParCandidat->id]) }}"> <h4 class="card-title font-20 mt-0">{{ $rtsParCandidat->nom }}</h4></a>

                </div>

                <img class="rounded-circle" src="{{ asset('photo/'.$rtsParCandidat->photo) }}" alt="Card image cap" style="height: 250px;">
                <div class="card-body">
                   <h4> <p class="card-text text-center">{{round(((int)$rtsParCandidat->nb/$votants)*100,2) }}%</p></h4>

                </div>
            </div>

        </div> end col
      --> --}}

</div>
  {{--  <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"></h3></div>
            <div class="card-body">
                <canvas id="myChart" width="300" height="200"></canvas>
            </div>
        </div>
    </div >
</div>

  <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"></h3></div>
            <div class="card-body">
      {{--    <table id="exemple1" class="table table-bordered table-responsive-md table-striped text-center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Candidat</th>
                    <th>Taux</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($rtsParCandidats as $rtsParCandidat)
                <tr>
                    <td><img src="{{ asset('photo/'.$rtsParCandidat->photo) }}" class="img img-rounded" style="height: 30px;"></td>
                    <td>{{ $rtsParCandidat->nom }}</td>
                    <td>{{round(($rtsParCandidat->nb/$votants)*100,2) }}%</td>
                </tr>
                @endforeach

            </tbody>
        </table>


            </div>
    </div>
    </div>  --}}
</div>



@else

    <div class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <div class="search-type-arrow"></div>
                    <div class="d-flex flex-row">
                        <div class="col-3 align-self-center">
                            <div class="round ">
                                <i class="mdi mdi-cart"></i>
                            </div>
                        </div>
                        <div class="col-9 align-self-center text-right">
                            <div class="m-l-10 ">
                                <h5 class="mt-0">{{ $nbCentreVote}}</h5>
                                <p class="mb-0 text-muted">Nombre de centre de vote </p>
                            </div>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height:3px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 61%;" aria-valuenow="61" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div><!--end card-body-->
            </div><!--end card-->
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <div class="search-type-arrow"></div>
                    <div class="d-flex flex-row">
                        <div class="col-3 align-self-center">
                            <div class="round ">
                                <i class="mdi mdi-cart"></i>
                            </div>
                        </div>
                        <div class="col-9 align-self-center text-right">
                            <div class="m-l-10 ">
                                <h5 class="mt-0">{{ $nbLieuVote}}</h5>
                                <p class="mb-0 text-muted">Nombre de bureau de vote </p>
                            </div>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height:3px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 61%;" aria-valuenow="61" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div><!--end card-body-->
            </div><!--end card-->
        </div>
    </div>


    <div class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <div class="search-type-arrow"></div>
                    <div class="d-flex flex-row">
                        <div class="col-3 align-self-center">
                            <div class="round ">
                                <i class="mdi mdi-cart"></i>
                            </div>
                        </div>
                        <div class="col-9 align-self-center text-right">
                            <div class="m-l-10 ">
                                <h5 class="mt-0">{{ $complet}}</h5>
                                <p class="mb-0 text-muted">Complete </p>
                            </div>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height:3px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 61%;" aria-valuenow="61" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div><!--end card-body-->
            </div><!--end card-->
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <div class="search-type-arrow"></div>
                    <div class="d-flex flex-row">
                        <div class="col-3 align-self-center">
                            <div class="round ">
                                <i class="mdi mdi-cart"></i>
                            </div>
                        </div>
                        <div class="col-9 align-self-center text-right">
                            <div class="m-l-10 ">
                                <h5 class="mt-0">{{ $incomplete}}</h5>
                                <p class="mb-0 text-muted">Incomplete </p>
                            </div>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height:3px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 61%;" aria-valuenow="61" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div><!--end card-body-->
            </div><!--end card-->
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <div class="search-type-arrow"></div>
                    <div class="d-flex flex-row">
                        <div class="col-3 align-self-center">
                            <div class="round ">
                                <i class="mdi mdi-cart"></i>
                            </div>
                        </div>
                        <div class="col-9 align-self-center text-right">
                            <div class="m-l-10 ">
                                <h5 class="mt-0">{{ $nonCommence}}</h5>
                                <p class="mb-0 text-muted">Non Commencé </p>
                            </div>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height:3px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 61%;" aria-valuenow="61" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div><!--end card-body-->
            </div><!--end card-->
        </div>
    </div>

@endif


@endsection


{{-- @section("script")
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.js"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.min.js"></script> }}

<script>
    var label = [];
    var donnee = [];
    var coloR = [];
    var dynamicColors = function() {
        var r = Math.floor(Math.random() * 255);
        var g = Math.floor(Math.random() * 255);
        var b = Math.floor(Math.random() * 255);
        var e = 1;
        return "rgba(" + r + "," + g + "," + b + ","+e + ")";
    };
    $(document).ready(function() {
        @foreach($rtsTemoins as $rtsParCandidat)
        label.push('{{$rtsParCandidat->nom}}');
        donnee.push({{$rtsParCandidat->nb}});
        coloR.push(dynamicColors());
                @endforeach
                 var ctx = document.getElementById('myChart');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: label,
                datasets: [{
                    label: 'Nombre de voix obtenues',
                    data: donnee,
                    backgroundColor: coloR,
                    borderColor: coloR,

                    borderWidth: 1
                }]
            },

            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        data = {
            datasets: [{
                data: donnee,
                backgroundColor: coloR,
                borderColor: coloR
            }],

            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: label
        };
        var ctx1 = document.getElementById('myPieChart');
        var myPieChart = new Chart(ctx1, {
            type: 'pie',
            data: data

        });
    });
</script>
@endsection
 --}}
