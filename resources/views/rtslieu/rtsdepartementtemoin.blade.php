@extends('welcome')
@section('title', '| rtslieu')


@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="btn-group float-right">


                                <ol class="breadcrumb hide-phone p-0 m-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}" >ACCUEIL</a></li>
                                <li class="breadcrumb-item active"><a href="{{ route('rtslieu.create') }}" >Résultat Département </a></li>
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
                                <h5 class="mt-0">{{round(((int)$tauxDeParticipation->nb/$inscrit)*100,2) }}%</h5>
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
    <div class="row">
<div class="col-8">
    <div class="card ">
        <div class="card-header  text-center">RESULTAT DEPARTEMENT : @if(!empty($departement)) {{$departement->nom}} @endif /  {{$depouillement[0]}} dépouillé sur {{$depouillement[1]}}</div>
            <div class="card-body">
                @if (Auth::user()->role=="admin")
                <form method="POST" action="{{ route('rts.by.departement.temoin') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-2">
                            <label>Région</label>
                            <select class="form-control" id="region_id" name="region_id" required="">
                                <option value="">Selectionner</option>
                                @foreach ($regions as $region)
                                <option value="{{$region->id}}"  {{$region_id==$region->id ? 'selected' : ''}}>{{$region->nom}}</option>
                                    @endforeach

                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label>Département</label>
                            <select class="form-control" id="departement_id" name="departement_id" >
                                <option value=""> Veuillez choisir</option>
                                @foreach ($departements as $item)
                                <option value="{{$item->id}}" {{$departement_id==$item->id ? 'selected' : ''}}>{{$item->nom}}</option>
                            @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2">
                            <input type="submit" value="Valider" class="btn btn-primary" style="margin-top: 30px;">
                        </div>
                    </div>

                </form>
                <br>
                @endif
                <div class="row">
                    <div class="col-5">
                        @if($departement)
                            <a href="{{ route('impression.rts.departement', ['departement'=>$departement->id,'type'=>2]) }}" class="btn btn-success" >Imprimer</a>
                        @endif
                    </div>
                    @if (count($rts) > 0)
                    <div class="col-5 text-right">
                        <img class="rounded-circle" src="{{ asset('photo/'.$rts[0]->photo) }}" alt="Card image cap" style="height: 100px;">
                       <h6> {{$rts[0]->candidat}}</h6>
                    </div>
  
                          @endif
                          @php
                          $nb = 0;
                          $taux = 0;
                         
                      @endphp
                    
                    <div class="col-12">
                        <table /*id="datatable-buttons"*/ class="table table-bordered table-responsive-md table-striped text-center">
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
                                @php
                                    $nb =   $rt->nb + $nb;
                                    $taux = round(($rt->nb *100)/$votant,2,1) + $taux;
                              
                                 @endphp
                                @endforeach
                                <tr>
                                    <td>Total </td>
                                    <td>@php echo $nb; @endphp </td>
                                    <td>@php echo intval($taux); @endphp </td>
                                   
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
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Votant : {{$votant}}</h6><br>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Nuls : {{$bullnull}}</h6><br>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Exprimés : {{$votant - $bullnull}}</h6><br>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Taux de participation : @if($inscrit>0){{ round(($votant*100)/$inscrit,2)}}% @endif</h6><br>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Siège  : @if(!empty($departement)){{$departement->nbcandidat}} @endif</h6><br>
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
 url_app = '{{ config('app.url') }}';
 $("#region_id").change(function () {
    var region_id =  $("#region_id").children("option:selected").val();
   // $(".region").val(region_id);
   // $(".departement").val("");
    $("#departement_id").empty();

        var departement = "<option value=''>Veuillez selectionner</option>";
        $.ajax({
            type:'GET',
            url:url_app+'/departement/by/region/'+region_id,
            data:'_token = <?php echo csrf_token() ?>',
            success:function(data) {

                $.each(data,function(index,row){
                    //alert(row.nomd);
                    departement +="<option value="+row.id+">"+row.nom+"</option>";

                });

                $("#departement_id").append(departement);
            }
        });
    });


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
