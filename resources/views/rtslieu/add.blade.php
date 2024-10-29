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
                        <li class="breadcrumb-item active"><a href="{{ route('rtslieu.index') }}">LISTE D'ENREGISTREMENT DES rtslieu</a></li>

                        </ol>
                    </div>
                    <h4 class="page-title">Starter</h4>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div id="electeur">

        </div>
        <form action="{{ route('rtslieu.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
             <div class="card ">
                        <div class="card-header text-center">FORMULAIRE D'ENREGISTREMENT DES RESULTATS</div>
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
                                                    <option value="{{$region->id}}">{{$region->nom}}</option>
                                                        @endforeach
            
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label>Département</label>
                                                <select class="form-control" id="departement_id" name="departement_id" required>
            
                                                </select>
                                            </div>
            
                                              <div class="col-12">
                                                <label>Commune</label>
                                                <select class="form-control" id="commune_id" name="commune_id" required>
            
                                                </select>
                                            </div>
                                                <div class="col-12">
                                                    <label>centrevote</label>
                                                    <select class="form-control" name="centrevote_id" id="centrevote_id" required="">
                                                  
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label>Lieu de Vote</label>
                                                    <select class="form-control" name="lieuvote_id" id="lieuvote_id" required="">
                                                    
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
                                                        <input type="number" name="hs"  value="{{ old('hs') }}" class="form-control"  required>
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
                               {{--  <div class="row">
                                    <div class="col">
                                        <label>Région</label>
                                        <select class="form-control" id="region_id" name="region_id" required="">
                                            <option value="">Selectionner</option>
                                            @foreach ($regions as $region)
                                            <option value="{{$region->id}}">{{$region->nom}}</option>
                                                @endforeach
    
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label>Département</label>
                                        <select class="form-control" id="departement_id" name="departement_id" required>
    
                                        </select>
                                    </div>
    
                                      <div class="col">
                                        <label>Commune</label>
                                        <select class="form-control" id="commune_id" name="commune_id" required>
    
                                        </select>
                                    </div>
                                        <div class="col">
                                            <label>centrevote</label>
                                            <select class="form-control" name="centrevote_id" id="centrevote_id" required="">
                                          
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label>Lieu de Vote</label>
                                            <select class="form-control" name="lieuvote_id" id="lieuvote_id" required="">
                                            
                                            </select>
                                        </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Votant </label>
                                            <input type="number" name="votant"  value="{{ old('votant') }}" class="form-control"  required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Nuls </label>
                                            <input type="number" name="bulnull"  value="{{ old('bulnull') }}" class="form-control"  required>
                                        </div>
                                    </div>  <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Hors Bureau </label>
                                            <input type="number" name="hs"  value="{{ old('hs') }}" class="form-control"  required>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
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
                                    </table> --}}
                               
                                {{--
                                
                                  @foreach ($candidats as $candidat )
                                            <tr>
                                                <td></td>
                                            </tr>

                                            <input type="hidden" name="candidat_id[]" value="{{ $candidat->id }}">
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label> {{ $candidat->coalition }} <img src="{{ asset('photo/'.$candidat->photo) }}" class="img img-rounded" style="height: 30px;"></label>
                                                    <input type="number" name="nbvote[]" data-parsley-min="0" data-parsley-type="number"  value="0" class="form-control"  required>
                                                </div>
                                            </div>
                                            @endforeach<div class="col-lg-6">
                                    <div class="form-group">
                                        <label> Nombre de votes</label>
                                        <input type="number" name="nbvote"  value="{{ old('nbvote') }}" class="form-control"  required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label> Nombre de vote valide</label>
                                        <input type="number" name="nbvv"  value="{{ old('nbvv') }}" class="form-control"  required>
                                    </div>
                                </div>  --}}
                                
                                   {{--   <div class="col-lg-6">
                                        <label>Candidat</label>
                                        <select class="form-control" name="candidat_id" required="">
                                            @foreach ($candidats as $candidat)
                                            <option {{old('candidat_id') == $candidat->id ? 'selected' : ''}}
                                                value="{{$candidat->id}}">{{$candidat->nom}}</option>
                                                @endforeach

                                        </select>
                                    </div>  

                                </div>--}}
                                <div>
                                    <input type="hidden" id="nb_electeur" name="nb_electeur">

                                    <br>
                                    <center>
                                        <button type="submit" class="btn btn-success btn btn-lg "> ENREGISTRER</button>
                                    </center>
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
        $(".commune").val("");
            var commune = "<option value=''>Veuillez selectionner</option>";
            $.ajax({
                type:'GET',
                url:'/commune/by/departement/'+departement_id,
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
            });
        });
        $("#commune_id").change(function () {
            var commune_id =  $("#commune_id").children("option:selected").val();
                var centrevote = "<option value=''>Veuillez selectionner</option>";
                $.ajax({
                    type:'GET',
                    url:'/centrevote/by/commune/'+commune_id,
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
                        url:'/lieuvote/by/centrevote/'+centrevote_id,
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
                $("#lieuvote_id").change(function () {
                var lieuvote_id =  $("#lieuvote_id").children("option:selected").val();
                    $.ajax({
                        type:'GET',
                        url:'/electeur/by/lieuvote/'+lieuvote_id,
                        vdata:'_token = <?php echo csrf_token() ?>',
                        success:function(data) {
                         //   alert(data)
                           
                            $('#electeur').empty()
                           $('#electeur').append("<h4> Nombre Electeurs : "+data.nb+"</h4>") 
                           $('#nb_electeur').val(data.nb)             
            
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
