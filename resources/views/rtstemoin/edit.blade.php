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
                        <li class="breadcrumb-item active"><a href="{{ route('rtstemoin.index') }}" role="button" class="btn btn-primary">RETOUR</a></li>

                        </ol>

                    </div>
                    <h4 class="page-title">Starter</h4>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        {!! Form::model($rtstemoin, ['method'=>'PATCH','route'=>['rtstemoin.update', $rtstemoin->id],'enctype'=>'multipart/form-data']) !!}
            @csrf
             <div class="card ">
                        <div class="card-header text-center">FORMULAIRE DE MODIFICATION D'une rtstemoin</div>
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
                                            <option value="{{$region->id}}" {{ $region->id==$rtstemoin->lieuvote->centrevote->commune->departement->region_id ? 'selected' : ''}}>{{$region->nom}}</option>
                                                @endforeach
    
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label>Département</label>
                                        <select class="form-control" id="departement_id" name="departement_id" required @readonly(true)>
                                            <option value="">Selectionner</option>
                                            @foreach ($departements as $departement)
                                            <option value="{{$departement->id}}" {{ $departement->id==$rtstemoin->lieuvote->centrevote->commune->departement_id ? 'selected' : ''}}>{{$departement->nom}}</option>
                                                @endforeach
                                        </select>
                                    </div>
    
                            
                                            <div class="col">
                                                <label>Commune</label>
                                                <select class="form-control" id="commune_id" name="commune_id" required @readonly(true)>
                                                    <option value="">Selectionner</option>
                                                    @foreach ($communes as $commune)
                                                    <option value="{{$commune->id}}" {{ $commune->id==$rtstemoin->lieuvote->centrevote->commune_id ? 'selected' : ''}}>{{$commune->nom}}</option>
                                                        @endforeach
                                                </select>

                                    </div>
                                        <div class="col">
                                            <label>Lieu de vote</label>
                                            <select class="form-control" name="centrevote_id" id="centrevote_id" required="" @readonly(true)>
                                                <option value="">Selectionner</option>
                                                @foreach ($centrevotes as $centrevote)
                                                <option value="{{$centrevote->id}}"  {{ $centrevote->id==$rtstemoin->lieuvote->centrevote->id ? 'selected' : ''}}>{{$centrevote->nom}}</option>
                                                    @endforeach
    
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label>Bureau  de vote  </label>
                                            <select class="form-control" name="lieuvote_id" id="lieuvote_id" required="" @readonly(true)>
                                                <option value="">Selectionner</option>
                                                <option value="{{$lieuvote->id}}" {{ $lieuvote->id==$rtstemoin->lieuvote_id ? 'selected' : ''}}>{{$lieuvote->nom}} </option>
    
                                            </select>
                                        </div>
                               
                              
                               

                                </div>

                                <div class="row">
                                    @foreach ($rtstemoins as $rts )
    
                                    <input type="hidden" name="candidat_id[]" value="{{ $rts->candidat->id }}">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label> {{ $rts->candidat->coalition }} <img src="{{ asset('photo/'.$rts->candidat->photo) }}" class="img img-rounded" style="height: 30px;"></label>
                                            <input type="number" name="nbvote[]" data-parsley-min="0" data-parsley-type="number"  value="{{ $rts->nbvote }}" class="form-control"  required>
                                        </div>
                                    </div>
                                    @endforeach
                                   
    
                                    </div>

                                <div>
                                    <input type="hidden" id="nb_electeur" name="nb_electeur">

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
            url:url_app+'/resultats/departement/by/region/'+region_id,
        //   url:url_app+'http://vmi435145.contaboserver.net:9000/departement/by/region/'+region_id,
          // url:url_app+'http://127.0.0.1/gestionmateriel/public/departement/by/region/'+region_id,
          //    url:url_app+'http://127.0.0.1:8000/departement/by/region/'+region_id,
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
                url:url_app+'/resultats/commune/by/departement/'+departement_id,
              //  url:url_app+'http://vmi435145.contaboserver.net:9000/commune/by/departement/'+departement_id,
             //   url:url_app+'http://127.0.0.1/gestionmateriel/public/commune/by/departement/'+departement_id,
             //    url:url_app+'http://127.0.0.1:8000/commune/by/departement/'+departement_id,
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
                    url:url_app+'/resultats/centrevote-temoin/by/commune/'+commune_id,
                //   url:url_app+'http://vmi435145.contaboserver.net:9000/commune/by/commune/'+commune_id,
                 //  url:url_app+'http://127.0.0.1/gestionmateriel/public/commune/by/commune/'+commune_id,
                //  url:url_app+'http://127.0.0.1:8000/commune/by/commune/'+commune_id,
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
