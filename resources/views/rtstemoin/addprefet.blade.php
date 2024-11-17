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
                        <li class="breadcrumb-item active"><a href="{{ route('rtstemoin.index') }}">LISTE D'ENREGISTREMENT DES rtstemoin</a></li>

                        </ol>
                    </div>
                    <h4 class="page-title">Starter</h4>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div id="electeur">

        </div>
        <form action="{{ route('rtstemoin.store') }}" method="POST" enctype="multipart/form-data">
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
                                           <input type="hidden" name="departement_id" value="{{$departement_id}}" required>
                                            <div class="col-12">
                                                <label>Arrondissement</label>
                                                <select class="form-control" id="arrondissement_id" name="arrondissement_id" required>
                                                    <option value="">Selectionner</option>
                                                    @foreach ($arrondissements as $arrondissement)
                                                    <option value="{{$arrondissement->id}}" {{ $arrondissement_id==$arrondissement->id ? 'selected' : '' }}>{{$arrondissement->nom}}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                              <div class="col-12">
                                                <label>Commune</label>
                                                <select class="form-control" id="commune_id" name="commune_id" required>
                                                    @foreach ($communes as $commune)
                                                    <option value="{{$commune->id}}" {{ $commune_id==$commune->id ? 'selected' : '' }}>{{$commune->nom}}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                                <div class="col-12">
                                                    <label>Lieu de vote</label>
                                                    <select class="form-control" name="centrevote_id" id="centrevote_id" required="">
                                                        @foreach ($centreVotes as $centreVote)
                                                        <option value="{{$centreVote->id}}" {{ $centrevote_id==$centreVote->id ? 'selected' : '' }}>{{$centreVote->nom}}</option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label>Bureau  de vote</label>
                                                    <select class="form-control" name="lieuvote_id" id="lieuvote_id" required="">
                                                        @foreach ($lieuVotes as $lieuVote)
                                                        <option value="{{$lieuVote->id}}" {{ $lieuvote_id==$lieuVote->id ? 'selected' : '' }}>{{$lieuVote->nom}}</option>
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
      url_app = '{{ config('app.url') }}';
    $("#region_id").change(function () {
    var region_id =  $("#region_id").children("option:selected").val();
    $(".region").val(region_id);
    $(".departement").val("");
    $(".commune").val("");
    $("#departement_id").empty();
    $("#commune_id").empty();
    $("#arrondissement_id").empty();
    $("#centrevote_id").empty();
    $("#lieuvote_id").empty();
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

    $("#departement_id").change(function () {
        var departement_id =  $("#departement_id").children("option:selected").val();
        $(".departement").val(departement_id);
        $(".commune").val("");
        $("#commune_id").empty();
        $("#arrondissement_id").empty();
        $("#centrevote_id").empty();
        $("#lieuvote_id").empty();
            var arrondissement = "<option value=''>Veuillez selectionner</option>";
            $.ajax({
                type:'GET',
                url:url_app+'/arrondissement/by/departement/'+departement_id,
                data:'_token = <?php echo csrf_token() ?>',
                success:function(data) {

                    $.each(data,function(index,row){
                        //alert(row.nomd);
                        arrondissement +="<option value="+row.id+">"+row.nom+"</option>";

                    });
                    $("#arrondissement_id").empty();
                    $("#arrondissement_id").append(arrondissement);
                }
            });
        });
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
                    url:url_app+'/centrevote/by/temoin/commune/'+commune_id,
               
                    data:'_token = <?php echo csrf_token() ?>',
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
                        url:url_app+'/lieuvote/temoin/by/centrevote/'+centrevote_id,
                        data:'_token = <?php echo csrf_token() ?>',
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
                        data:'_token = <?php echo csrf_token() ?>',
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
