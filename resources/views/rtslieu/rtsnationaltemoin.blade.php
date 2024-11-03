@extends('welcome')
@section('title', '| rtslieu')


@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="btn-group float-right">


                                <ol class="breadcrumb hide-phone p-0 m-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}" >ACCUEIL</a></li>
                                <li class="breadcrumb-item active"><a href="{{ route('rtslieu.create') }}" >RESULTAT Nationnal Bureau Temoin</a></li>
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
        <div class="card-header  text-center">RESULTAT Nationnal Bureau Temoin</div>
            <div class="card-body">

                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('impression.rts.national', ['type'=>2]) }}" class="btn btn-success" >Imprimer</a>
                        <br>
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
                  
                </div>



            </div>

    </div>
</div>
<div class="col-4">
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Informations Générales</h5>
            <h6  class="badge badge-success" style="font-size: 17px ! important;">Inscrits : {{$inscrit}}</h6><br>
            <h6  class="badge badge-success" style="font-size: 17px ! important;">Votant : {{$votant}}</h6><br>
            <h6  class="badge badge-success" style="font-size: 17px ! important;">Nuls : {{$bulletinnull}}</h6><br>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Exprimés : {{$votant - $bulletinnull}}</h6><br>
            <h6  class="badge badge-success" style="font-size: 17px ! important;">Taux de participation : @if($inscrit>0){{ round(($votant*100)/$inscrit,2)}}% @endif</h6><br>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Qutotient  : {{ $quotiant }}</h6>
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