{{-- \resources\views\permissions\create.blade.php --}}
@extends('welcome')

@section('title', '| Modifier Région')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="btn-group float-right">

                        <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" role="button" class="btn btn-primary">ACCUEIL</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('participation.index') }}" role="button" class="btn btn-primary">RETOUR</a></li>

                        </ol>

                    </div>
                    <h4 class="page-title">Starter</h4>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        {!! Form::model($participation, ['method'=>'PATCH','route'=>['participation.update', $participation->id],'enctype'=>'multipart/form-data']) !!}
            @csrf
             <div class="card ">
                        <div class="card-header text-center">FORMULAIRE DE MODIFICATION D'une participation</div>
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
                                        <div class="col">
                                            <label>Région</label>
                                            <select class="form-control" id="region_id" name="region_id" required="" @readonly(true)>
                                                <option value="">Selectionner</option>
                                                @foreach ($regions as $region)
                                                <option value="{{$region->id}}" {{ $region->id==$participation->lieuvote->centrevote->commune->departement->region_id ? 'selected' : ''}}>{{$region->nom}}</option>
                                                    @endforeach
        
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label>Département</label>
                                            <select class="form-control" id="departement_id" name="departement_id" required  @readonly(true)>
                                                <option value="">Selectionner</option>
                                                @foreach ($departements as $departement)
                                                <option value="{{$departement->id}}" {{ $departement->id==$participation->lieuvote->centrevote->commune->departement_id ? 'selected' : ''}}>{{$departement->nom}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
        
                                
                                                <div class="col">
                                                    <label>Commune</label>
                                                    <select class="form-control" id="commune_id" name="commune_id" required  @readonly(true)>
                                                        <option value="">Selectionner</option>
                                                        @foreach ($communes as $commune)
                                                        <option value="{{$commune->id}}" {{ $commune->id==$participation->lieuvote->centrevote->commune_id ? 'selected' : ''}}>{{$commune->nom}}</option>
                                                            @endforeach
                                                    </select>

                                        </div>
                                           {{--  <div class="col">
                                                <label>centrevote</label>
                                                <select class="form-control" name="centrevote_id" id="centrevote_id" required="">
                                                    <option value="">Selectionner</option>
                                                    @foreach ($centrevotes as $centrevote)
                                                    <option value="{{$centrevote->id}}"  {{ $centrevote->id==$participation->lieuvote->centrevote->id ? 'selected' : ''}}>{{$centrevote->nom}}</option>
                                                        @endforeach
        
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label>Bureau  de vote  </label>
                                                <select class="form-control" name="lieuvote_id" id="lieuvote_id" required="">
                                                    <option value="">Selectionner</option>
                                                    <option value="{{$lieuvote->id}}" {{ $lieuvote->id==$participation->lieuvote_id ? 'selected' : ''}}>{{$lieuvote->nom}} </option>
        
                                                </select>
                                            </div> --}}
                                   
                                  
                                    <div class="col">
                                        <label>Heure</label>
                                        <select class="form-control" id="heure_id" name="heure_id" required=""  @readonly(true)>
                                            <option value="">Selectionner</option>
                                            @foreach ($heures as $heure)
                                            <option value="{{$heure->id}}" {{ $heure->id==$participation->heure_id ? 'selected' : ''}}>{{$heure->designation}}</option>
                                                @endforeach
    
                                        </select>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label> Nombre de votes</label>
                                            <input type="number" name="resultat"  value="{{$participation->resultat }}" class="form-control"  required>
                                        </div>
                                    </div>
    
                                    </div>
                                

                                <div>
                                    <center>
                                        <button type="submit" class="btn btn-success btn btn-lg "> MODIFIER</button>
                                    </center>
                                </div>


                            </div>
                        </div>
    {!! Form::close() !!}


@endsection
@section('script')
<script>
    url_app = '{{ config('app.url') }}';
    $("#region_id").change(function () {
    var region_id =  $("#region_id").children("option:selected").val();
    $(".region").val(region_id);
    $(".departement").val("");
    $(".commune").val("");
        var departement = "<option value=''>Veuillez selectionner</option>";
        $.ajax({
            type:'GET',
            url:url_app+'/departement/by/region/'+region_id,
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
   $("#departement_ids").change(function () {
        var departement_id =  $("#departement_id").children("option:selected").val();
        $(".departement").val(departement_id);
        $(".commune").val("");
            var commune = "<option value=''>Veuillez selectionner</option>";
          /*   $.ajax({
                type:'GET',
                url:'/resultats/commune/by/departement/'+departement_id,
              //  url:'http://vmi435145.contaboserver.net:9000/commune/by/departement/'+departement_id,
             //   url:'http://127.0.0.1/gestionmateriel/public/commune/by/departement/'+departement_id,
             //    url:'http://127.0.0.1:8000/commune/by/departement/'+departement_id,
                data:'_token = <?php echo csrf_token() ?>',
                success:function(data) {

                    $.each(data,function(index,row){
                        //alert(row.nomd);
                        commune +="<option value="+row.id+">"+row.nom+"</option>";

                    });
                    $("#commune_id").empty();
                    $("#commune_id").append(commune);
                }
            }); */

            $.ajax({
                        type:'GET',
                         url:url_app+'/resultats/lieuvote-temoin/by/departement/'+departement_id,
                        vdata:'_token = <?php echo csrf_token() ?>',
                        success:function(data) {
                         //   alert(data)
                           
                            $('#electeur').empty()
                           $('#electeur').append("<h4> Nombre Electeurs : "+data+"</h4>") 
                           $('#nb_electeur').val(data)             
            
                        }
                    });
        });
        $("#commune_id").change(function () {
            var commune_id =  $("#commune_id").children("option:selected").val();
                var centrevote = "<option value=''>Veuillez selectionner</option>";
                $.ajax({
                    type:'GET',
                    url:url_app+'/resultats/centrevote-temoin/by/commune/'+commune_id,
                //   url:'http://vmi435145.contaboserver.net:9000/commune/by/commune/'+commune_id,
                 //  url:'http://127.0.0.1/gestionmateriel/public/commune/by/commune/'+commune_id,
                //  url:'http://127.0.0.1:8000/commune/by/commune/'+commune_id,
                    vdata:'_token = <?php echo csrf_token() ?>',
                    success:function(data) {

                        $.each(data,function(index,row){
                            //alert(row.nomd);
                            centrevote +="<option value="+row.id+">"+row.nom+"</option>";

                        });
                        $("#centrevote_id").empty();
                        $("#centrevote_id").append(centrevote);
                    }
                });
            });

            $("#centrevote_id").change(function () {
                var centrevote_id =  $("#centrevote_id").children("option:selected").val();
                    var lieuvote = "<option value=''>Veuillez selectionner</option>";
                    $.ajax({
                        type:'GET',
                         url:url_app+'/resultats/lieuvote-temoin/by/centrevote/'+centrevote_id,
                        vdata:'_token = <?php echo csrf_token() ?>',
                        success:function(data) {

                            $.each(data,function(index,row){
                              //  alert(row.id);
                                lieuvote +="<option value="+row.id+">"+row.nom+"</option>";

                            });
                            $("#lieuvote_id").empty();
                            $("#lieuvote_id").append(lieuvote);
                        }
                    });
                });
               /*  $("#lieuvote_id").change(function () {
                var lieuvote_id =  $("#lieuvote_id").children("option:selected").val();
                    $.ajax({
                        type:'GET',
                        url:'/resultats/electeur/by/lieuvote/'+lieuvote_id,
                        vdata:'_token = <?php echo csrf_token() ?>',
                        success:function(data) {
                         //   alert(data)
                           
                            $('#electeur').empty()
                           $('#electeur').append("<h4> Nombre Electeurs : "+data.nb+"</h4>") 
                           $('#nb_electeur').val(data.nb)             
            
                        }
                    });
                }); */

                $("#lieuvote_id").change(function () {
                var lieuvote_id =  $("#lieuvote_id").children("option:selected").val();
                    $.ajax({
                        type:'GET',
                        url:url_app+'/resultats/electeur/by/lieuvote/'+lieuvote_id,
                        vdata:'_token = <?php echo csrf_token() ?>',
                        success:function(data) {
                         //   alert(data)
                           
                            $('#electeur').empty()
                           $('#electeur').append("<h4> Nombre Electeurs : "+data.nb+"</h4>") 
                           $('#nb_electeur').val(data.nb)             
            
                        }
                    });
                });



</script>
@endsection
