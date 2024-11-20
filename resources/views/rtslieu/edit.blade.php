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
        <form action="{{ route('update.perso.rtslieu') }}" method="POST" enctype="multipart/form-data">
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
                                @if ($message = Session::get('success'))
                                <div class="alert alert-success">
                                    <p>{{ $message }}</p>
                                </div>
                            @endif
                                <div class="row">

                                    <div class="col-4">
                                        <div class="row">
                                           <input type="hidden" name="departement_id" value="{{Auth::user()->departement_id}}">
                                           <div class="col-12">
                                            <label>Arrondissement</label>
                                            <select class="form-control" id="arrondissement_id" name="arrondissement_id" required @readonly(true)>

                                                <option value="{{$arrondissement->id}}" >{{$arrondissement->nom}}</option>

                                            </select>
                                        </div>
                                          <div class="col-12">
                                            <label>Commune</label>
                                            <select class="form-control" id="commune_id" name="commune_id" required @readonly(true)>
                                              <option value="{{$commune->id}}" >{{$commune->nom}}</option>

                                            </select>
                                        </div>
                                            <div class="col-12">
                                                <label>Lieu de vote</label>
                                                <select class="form-control" name="centrevote_id" id="centrevote_id" required="" @readonly(true)>

                                                    <option value="{{$centreVote->id}}" >{{$centreVote->nom}}</option>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label>Bureau  de vote</label>
                                                <select class="form-control" name="lieuvote_id" id="lieuvote_id" required="" @readonly(true)>

                                                    <option value="{{$lieuVote->id}}" >{{$lieuVote->nom}}</option>

                                                </select>
                                            </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label>Votant </label>
                                                        <input type="number" data-parsley-min="0" name="votant" id="votant"  value="{{ $lieuVote->votant }}" class="form-control"  required >
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label>Nuls </label>
                                                        <input type="number"  name="bulnull"  data-parsley-min="0" id="bulnull"  value="{{ $lieuVote->bulnull }}" class="form-control"  required >
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label>Hors Bureau </label>
                                                        <input type="number" name="hs"  value="{{ $lieuVote->hs }}" class="form-control"  required >
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label>Suffrage valablement Exprimé </label>
                                                        <input type="number" name="suffval" id="suffval"  value="{{ $lieuVote->votant - $lieuVote->bulnull}}" class="form-control"  required @readonly(true)>
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
                                                @foreach ($rtslieus as $candidat )
                                                <tr>
                                                    <td>
                                                        <label> {{ $candidat->coalition }} <img src="{{ asset('photo/'.$candidat->photo) }}" class="img img-rounded" style="height: 30px;"></label>
                                                    </td>
                                                    <td><input type="text" name="nbvote[]" data-parsley-min="0" data-parsley-type="number"  value="{{$candidat->nbvote}}" class="form-control"  required>
                                                        <input type="hidden" name="candidat_id[]" value="{{ $candidat->id }}">
                                                        <td><input type="hidden" name="nbvoteold[]"   value="{{$candidat->nbvote}}" class="form-control"  >

                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div>
                                    <input type="hidden" id="nb_electeur" value="{{$lieuVote->nb}}" name="nb_electeur">

                                    <br>
                                    <center>
                                                                               <button type="submit" class="btn btn-success btn btn-lg "  onclick="this.disabled=true; this.form.submit();"> ENREGISTRER</button>

                                    </center>
                                </div>
                            </div>

                            </div>

            </form>



@endsection

@section('script')
<script>
  url_app = '{{ config('app.url') }}';
    $("#arrondissement_id").change(function () {
        var arrondissement_id =  $("#arrondissement_id").children("option:selected").val();
        $(".commune").val("");
        $("#commune_id").empty();
        $("#centrevote_id").empty();
        $("#lieuvote_id").empty();
            var commune = "<option value=''>Veuillez selectionner</option>";
            $.ajax({
                type:'GET',
                url:url_app+'/commune/by/arrondissement/'+arrondissement_id,
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
                $("#centrevote_id").empty();
                $("#lieuvote_id").empty();
                $.ajax({
                    type:'GET',
                    url:url_app+'/centrevote/by/commune/'+commune_id,
                //   url:url_app+'http://vmi435145.contaboserver.net:9000/commune/by/commune/'+commune_id,
                 //  url:url_app+'http://127.0.0.1/gestionmateriel/public/commune/by/commune/'+commune_id,
                //  url:url_app+'http://127.0.0.1:8000/commune/by/commune/'+commune_id,
                    vdata:'_token = <?php echo csrf_token() ?>',
                    success:function(data) {

                        $.each(data,function(index,row){
                            //alert(row.nomd);
                            centrevote +="<option value="+row.id+">"+row.nom+"</option>";

                        });

                        $("#centrevote_id").append(centrevote);
                    }
                });
            });

            $("#centrevote_id").change(function () {
                var centrevote_id =  $("#centrevote_id").children("option:selected").val();
                    var lieuvote = "<option value=''>Veuillez selectionner</option>";
                    $("#lieuvote_id").empty();
                    $.ajax({
                        type:'GET',
                        url:url_app+'/lieuvote/by/centrevote/'+centrevote_id,
                        vdata:'_token = <?php echo csrf_token() ?>',
                        success:function(data) {

                            $.each(data,function(index,row){
                              //  alert(row.id);
                                lieuvote +="<option value="+row.id+">"+row.nom+"</option>";

                            });

                            $("#lieuvote_id").append(lieuvote);
                        }
                    });
                });
                $("#lieuvote_id").change(function () {
                var lieuvote_id =  $("#lieuvote_id").children("option:selected").val();
                    $.ajax({
                        type:'GET',
                        url:url_app+'/electeur/by/lieuvote/'+lieuvote_id,
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
