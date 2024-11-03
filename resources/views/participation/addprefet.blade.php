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
                        <li class="breadcrumb-item active"><a href="{{ route('participation.index') }}">LISTE D'ENREGISTREMENT DES participation</a></li>

                        </ol>
                    </div>
                    <h4 class="page-title">Starter</h4>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div id="electeur">

        </div>
        <form action="{{ route('participation.store') }}" method="POST" enctype="multipart/form-data">
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
                                   
                                  
                                    <input type="hidden" name="departement_id" value="{{Auth::user()->departement_id}}">
                                    <div class="col-3">
                                        <label>Arrondissement</label>
                                        <select class="form-control" id="arrondissement_id" name="arrondissement_id" required>
                                            <option value="">Selectionnez</option>
                                            @foreach ($arrondissements as $arrondissement)
                                            <option value="{{$arrondissement->id}}" {{ $arrondissement_id==$arrondissement->id ? 'selected' : '' }}>{{$arrondissement->nom}}</option>
                                                @endforeach 
                                        </select>
                                    </div>
                                      <div class="col-3">
                                        <label>Commune</label>
                                        <select class="form-control" id="commune_id" name="commune_id" required>
                                         
                                            @foreach ($communes as $commune)
                                            <option value="{{$commune->id}}" {{ $commune_id==$commune->id ? 'selected' : '' }}>{{$commune->nom}}</option>
                                                @endforeach 
                                        </select>
                                    </div>
                                        <div class="col-3">
                                            <label>centrevote</label>
                                            <select class="form-control" name="centrevote_id" id="centrevote_id" required="">
                                                @foreach ($centreVotes as $centreVote)
                                                    <option value="{{$centreVote->id}}" {{ $centrevote_id==$centreVote->id ? 'selected' : '' }}>{{$centreVote->nom}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <label>Bureau  de vote</label>
                                            <select class="form-control" name="lieuvote_id" id="lieuvote_id" required="">

                                                @foreach ($lieuVotes as $lieuVote)
                                                <option value="{{$lieuVote->id}}" {{ $lieuvote_id==$lieuVote->id ? 'selected' : '' }}>{{$lieuVote->nom}}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                <div class="col-3">
                                    <label>Heure</label>
                                    <select class="form-control" id="heure_id" name="heure_id" required="">
                                        <option value="">Selectionner</option>
                                        @foreach ($heures as $heure)
                                        <option value="{{$heure->id}}">{{$heure->designation}}</option>
                                            @endforeach

                                    </select>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label> Nombre de votes</label>
                                        <input type="number" name="resultat"  value="{{ old('resultat') }}" class="form-control"  required>
                                    </div>
                                </div>

                                </div>

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
   
   
    $("#arrondissement_id").change(function () {
        var arrondissement_id =  $("#arrondissement_id").children("option:selected").val();
        $(".commune").val("");
        $("#commune_id").empty();
        $("#centrevote_id").empty();
        $("#lieuvote_id").empty();
            var commune = "<option value=''>Veuillez selectionner</option>";
            $.ajax({
                type:'GET',
                url:'/commune/by/arrondissement/'+arrondissement_id,
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
                    url:'/centrevote/by/commune/'+commune_id,

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
                        url:'/lieuvote/temoin/participation/by/centrevote/'+centrevote_id,
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


</script>
@endsection
