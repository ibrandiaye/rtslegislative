{{-- \resources\views\permissions\create.blade.php --}}
@extends('welcome')

@section('title', '| Enregister Département')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="btn-group float-right">


                        <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" >ACCUEIL</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('rtsdepartement.index') }}">LISTE D'ENREGISTREMENT DES rtsdepartement</a></li>

                        </ol>
                    </div>
                    <h4 class="page-title">Starter</h4>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <form action="{{ route('rtsdepartement.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="electeur">
                
            </div>
            <div class="row">

            <div class="col-md-12">
             <div class="card ">
                        <div class="card-header text-center"></div>
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-4">
                                        <div class="row">
                                            <div class="col-12">
                                                <label>Région</label>
                                                <select class="form-control" id="region_id" name="region_id" required="">
                                                    <option value="">Selectionner</option>
                                                    @foreach ($regions as $region)
                                                    <option value="{{$region->id}}" {{ $region_id==$region->id ? 'selected' : '' }}>{{$region->nom}}</option>
                                                        @endforeach

                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label>Département</label>
                                                <select class="form-control" id="departement_id" name="departement_id" required>
                                                    @foreach ($departements as $departement)
                                                    <option value="{{$departement->id}}" {{ $departement_id==$departement->id ? 'selected' : '' }}>{{$departement->nom}}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                           
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label>Votant </label>
                                                        <input type="number" name="votant" id="votant"  value="{{ old('votant') }}" class="form-control"  required>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label>Nuls </label>
                                                        <input type="number" name="bulnull" id="bulnull"  value="{{ old('bulnull') }}" class="form-control"  required>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label>Hors Bureau </label>
                                                        <input type="number" name="hs"  value="{{ old('hs') }}" class="form-control"  >
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label>Suffrage valablement Exprimé </label>
                                                        <input type="number" name="suffval" id="suffval"  value="{{ old('suffval') }}" class="form-control"  required>
                                                    </div>
                                                </div>

                                        </div>

                                    </div>
                                    <div class="col-8">
                                        <table class="table table-bordered table-responsive-md table-striped text-center">
                                            <thead>
                                                <th>Liste</th>
                                                <th>Resultat</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($candidats as $candidat )
                                                <tr>
                                                    <td>
                                                        <label> {{ $candidat->coalition }} <img src="{{ asset('photo/'.$candidat->photo) }}" class="img img-rounded" style="height: 30px;"></label>
                                                    </td>
                                                    <td><input type="number" name="nbvote[]" data-parsley-min="0" data-parsley-type="number"  value="0" class="form-control"  required>
                                                        <input type="hidden" name="candidat_id[]" value="{{ $candidat->id }}">
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            
                                <div>
                                    <input type="hidden" id="nb_electeur" name="nb_electeur">

                                    <br>
                                    <center>
                                        <button type="submit" class="btn btn-success btn btn-lg "> ENREGISTRER</button>
                                    </center>
                                </div>
                            </div>

                            </div>
                                   
                                </div>

                            </div>




            </form>



@endsection


@section('script')
<script>
    $("#region_id").change(function () {
    var region_id =  $("#region_id").children("option:selected").val();
    $(".region").val(region_id);
    $(".departement").val("");
    $(".commune").val("");
        var departement = "<option value=''>Veuillez selectionner</option>";
        $.ajax({
            type:'GET',
            url:'/departement/by/region/'+region_id,
        //   url:'http://vmi435145.contaboserver.net:9000/departement/by/region/'+region_id,
          // url:'http://127.0.0.1/gestionmateriel/public/departement/by/region/'+region_id,
          //    url:'http://127.0.0.1:8000/departement/by/region/'+region_id,
            data:'_token = <?php echo csrf_token() ?>',
            success:function(data) {

                $.each(data,function(index,row){
                    //alert(row.nomd);
                    departement +="<option value="+row.id+">"+row.nom+"</option>";

                });
                $("#departement_id").empty();
                $("#commune_id").empty();
                $("#departement_id").append(departement);
            }
        });
    });
    $("#departement_id").change(function () {
        var departement_id =  $("#departement_id").children("option:selected").val();
        $(".departement").val(departement_id);
            var commune = "<option value=''>Veuillez selectionner</option>";
            $.ajax({
                        type:'GET',
                        url:'/somme/electeur/by/departement/'+departement_id,
        
                        vdata:'_token = <?php echo csrf_token() ?>',
                        success:function(data) {
                            $('#electeur').empty()
                           $('#electeur').append("<h4> Nombre Electeurs : "+data+"</h4>") ;
                           $('#nb_electeur').val(data)             
                
                        }
                    });
        });
        $("#votant").keyup(function(){
                   votant = $("#votant").val();
                   bulnull = $("#bulnull").val();
                   $("#suffval").val(votant - bulnull);
                });
                $("#bulnull").keyup(function(){
                    votant = $("#votant").val();
                   bulnull = $("#bulnull").val();
                   $("#suffval").val(votant - bulnull);
                });


</script>
@endsection