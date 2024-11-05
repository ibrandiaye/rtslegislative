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
        <div class="card-header  text-center">RESULTAT DEPARTEMENT : @if(!empty($departement)) {{$departement->nom}} @endif</div>
            <div class="card-body">
                @if (Auth::user()->role=="admin")
                <form method="POST" action="{{ route('rts.by.departement') }}">
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
                    <div class="col-12">
                        @if($departement)
                        <a href="{{ route('impression.rts.departement', ['departement'=>$departement->id,'type'=>1]) }}" class="btn btn-success" >Imprimer</a>
                        @endif
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
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Inscrits : {{$inscrit}}</h6><br>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Votant : {{$votant +$bullnull }}</h6><br>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Nuls : {{$bullnull}}</h6><br>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Exprimés : {{$votant }}</h6><br>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Taux de participation : @if($inscrit>0){{ round(($votant*100)/$inscrit,2)}}% @endif</h6><br>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Siège  : @if(!empty($departement)){{$departement->nbcandidat}} @endif</h6>
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
