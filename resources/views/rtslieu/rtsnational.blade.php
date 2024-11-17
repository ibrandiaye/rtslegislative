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
<div class="row">
<div class="col-8">
    <div class="card ">
        <div class="card-header  text-center">RESULTAT Nationnal / {{$depouillement[0]}} dépouillé sur {{$depouillement[1] + $depouillement[0]}} : @if($depouillement[1]+$depouillement[0] > 0) {{round($depouillement[0]/($depouillement[1]+$depouillement[0]),2) * 100}}% @endif</div>
            <div class="card-body">

                <div class="row">
                    <div class="col-5">
                        <a href="{{ route('impression.rts.national', ['type'=>1]) }}" class="btn btn-success" >Imprimer</a>

                    </div>
                    @if (count($rts) > 0)
                    <div class="col-5 text-right">
                        <img class="rounded-circle" src="{{ asset('photo/'.$rts[0]->photo) }}" alt="Card image cap" style="height: 100px;">
                       <h6> {{$rts[0]->coalition}}</h6>
                    </div>
  
                          @endif
                   
                    
                    <div class="col-12">
                      @php
                          $nb = 0;
                          $taux = 0;
                          $proportionnel = 0;
                          $majoritaire = 0;
                          $total = 0;
                          $restant = 0;
                      @endphp
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
                                @php
                                $nb =  $sieges['nb'] + $nb;
                                $taux =  round(($sieges['nb'] *100)/($totalVotants  ),1) + $taux;
                                $proportionnel =  $sieges['proportionnel']  + $proportionnel;
                                $majoritaire = $majoritaire +  $sieges['majoritaire'] ;
                                $total = $sieges['total']   + $total;
                                $restant = $sieges['restant'] + $restant;
                            @endphp
                                @endif
                                @endforeach
                                <tr>
                                    <td>Total </td>
                                    <td>@php echo $nb; @endphp </td>
                                    <td>@php echo $taux; @endphp </td>
                                    <td>@php echo $proportionnel; @endphp </td>
                                    <td>@php echo $majoritaire; @endphp </td>
                                    <td>@php echo $total; @endphp </td>
                                    <td>@php echo $restant; @endphp </td>
                                </tr>

                            </tbody>
                        </table>


                    </div>
                   
                </div>



            </div>

    </div>
</div>
<div class="col-4">
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Informations Générales</h5>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Inscrits : {{$inscrit}}</h6><br>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Votant : {{$totalVotants + $bulletinnull}}</h6><br>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Nuls : {{$bulletinnull}}</h6><br>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Exprimés : {{$totalVotants }}</h6><br>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Taux de participation : @if($inscrit>0){{ round(($totalVotants*100)/$inscrit,2)}}% @endif</h6><br>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Qutotient  : {{ $quotiant }}</h6><br>
        </div>
    </div>
    <div>
        <canvas id="myChart"></canvas>
      </div>
</div>
</div>
@endsection
@section('script')
 <script type="module" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.3.2/chart.js" integrity="sha512-CAv0l04Voko2LIdaPmkvGjH3jLsH+pmTXKFoyh5TIimAME93KjejeP9j7wSeSRXqXForv73KUZGJMn8/P98Ifg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function(){

const ctx = document.getElementById('myChart');

var myChart =new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: [
             'Depouiller',
            'Non Depouiller'
        ],
  datasets: [{
    label: 'Etat depouillement',
    data: [{{$depouillement[0]}},{{$depouillement[1]}}],
    backgroundColor: [
     
      'rgb(54, 162, 235)',
      'rgb(255, 99, 132)',
    ],
    hoverOffset: 4
  }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

});
  </script>
@endsection