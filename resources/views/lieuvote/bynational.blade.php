@extends('welcome')
@section('title', '| lieuvote')


@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="btn-group float-right">


                                <ol class="breadcrumb hide-phone p-0 m-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}" >ACCUEIL</a></li>
                                <li class="breadcrumb-item active"><a href="{{ route('lieuvote.create') }}" >Liste des lieuvotes</a></li>
                                </ol>
                            </div>
                            <h4 class="page-title">Starter</h4>
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
    <div class="col-12">

        <form method="POST" action="{{ route('search.national') }}">
            @csrf
            <div class="row">
                <div class="col-3">
                    <label>Région</label>
                    <select class="form-control" id="region_id" name="region_id" required="">
                        <option value="">Selectionner</option>
                        @foreach ($regions as $region)
                        <option value="{{$region->id}}" {{ $region_id==$region->id ? 'selected' : '' }}>{{$region->nom}}</option>
                            @endforeach

                    </select>
                </div>
                <div class="col-3">
                    <label>Département</label>
                    <select class="form-control" id="departement_id" name="departement_id" >
                        <option value="">Selectionner</option>

                        @foreach ($departements as $departement)
                            <option value="{{$departement->id}}" {{ $departement_id==$departement->id ? 'selected' : '' }}>{{$departement->nom}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <label>Arrondissement</label>
                    <select class="form-control" id="arrondissement_id" name="arrondissement_id">
                        <option value="">Veuillez selectionner </option>
                        @foreach ($arrondissements as $arrondissement)
                        <option value="{{$arrondissement->id}}" {{ $arrondissement_id==$arrondissement->id ? 'selected' : '' }}>{{$arrondissement->nom}}</option>
                            @endforeach
                    </select>
                </div>
                      <div class="col-2">
                        <label>Commune</label>
                        <select class="form-control" id="commune_id" name="commune_id" >
                            <option value="">Veuillez selectionner </option>

                            @foreach ($communes as $commune)
                            <option value="{{$commune->id}}" {{ $commune_id==$commune->id ? 'selected' : '' }}>{{$commune->nom}}</option>
                                @endforeach
                        </select>
                    </div>
                        <div class="col-3">
                            <label>centrevote</label>
                            <select class="form-control" name="centrevote_id" id="centrevote_id" >
                                <option value="">Veuillez selectionner </option>

                                @foreach ($centreVotes as $centreVote)
                                <option value="{{$centreVote->id}}" {{ $centrevote_id==$centreVote->id ? 'selected' : '' }}>{{$centreVote->nom}}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="col-2">
                            <label>Bureau  de vote</label>
                            <select class="form-control" name="lieuvote_id" id="lieuvote_id" >
                                <option value="">Veuillez selectionner </option>

                                @foreach ($lieuVotes as $lieuVote)
                                <option value="{{$lieuVote->id}}" {{ $lieuvote_id==$lieuVote->id ? 'selected' : '' }}>{{$lieuVote->nom}}</option>
                                    @endforeach
                            </select>
                        </div>

                        <div class="col-2">
                            <label>Etat</label>
                            <select class="form-control" name="etat" id="etat" >
                                <option value="">Veuillez selectionner </option>
                                <option value="1" {{$etat==1 ? 'selected' : ''}}>Renseigné </option>
                                <option value="0" {{$etat==0 ? 'selected' : ''}}>Non Renseigné </option>
                                
                            </select>
                        </div>
                        <div class="col-2">
                            <label>Type Bureau</label>
                            <select class="form-control" name="temoin" id="temoin" >
                                <option value="">Veuillez selectionner </option>
                                <option value="1" {{$temoin==1 ? 'selected' : ''}}>temoin </option>
                                <option value="0" {{$temoin==0 ? 'selected' : ''}}>autres </option>
                                
                            </select>
                        </div>

                <div class="col-lg-2">
                    <input type="submit" value="Valider" class="btn btn-primary" style="margin-top: 30px;">
                </div>
            </div>

        </form>
    </div>


<div class="col-12">
    <div class="card ">
        <div class="card-header  text-center">LISTE D'ENREGISTREMENT DES Bureaux de votes</div>
            <div class="card-body">
                
                <table  id="datatable-buttons" class="table table-bordered table-responsive-md table-striped text-center datatable-buttons">
                    <thead>
                        <tr>
                            {{-- <th>#</th>
                            <th>Numéro Bureau de Vote</th>
                            <th>Centre de vote</th>
                            <th>Commune</th>
                            <th>Temoin</th>
                            <th>Actions</th> --}}
                            <th>Commune</th>
                            <th>Centre de vote</th>
                            <th>Bureau</th>
                            <th>Electeurs</th>
                            <th>etat</th>
                            <th>Type Bureau</th>
                            <th>Actions</th> 
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($lieuvotess as $lieuvote)
                            <tr>
                                <td>{{ $lieuvote->commune }}</td>
                                <td>{{ $lieuvote->centre }}</td>
                                <td>{{ $lieuvote->bureau }}</td>
                                <td>{{ $lieuvote->nb }}</td>
                                <td> @if($lieuvote->etat==1) <span class='badge badge-success'>deja renseigne</span> @else <span class='badge badge-danger'>pas encore renseigne</span> @endif </td>
                                
                                <td> @if($lieuvote->temoin==1) <span class='badge badge-success'>Bureau Temoin</span> @else <span class='badge badge-warning'>Non Temoin</span> @endif </td>
                                
                                <td>
                                <a href="{{ route('editer.rtslieu', ['centrevoteId'=>$lieuvote->centrevote_id,'lieuvoteId'=>$lieuvote->lieuvote_id,'communeId'=>$lieuvote->commune_id]) }}" role="button" title="Modifier les résultats" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                <a href="{{ route('show.rtslieu.by.lieu',$lieuvote->lieuvote_id)}}" role="button" class="btn btn-info" title="Voir les résultats"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('mettre.en.temoin',$lieuvote->lieuvote_id)}}" onclick="if(!confirm('Vous vous le mettre en bureau temoin ?')) { return false; }" role="button" class="btn btn-warning" title="mettre en bureau temoin"><i class="fas fa-database"></i></a>

                               </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>



            </div>

    </div>
</div>



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
                    url:url_app+'/centrevote/by/commune/'+commune_id,

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
                        url:url_app+'/lieuvote/temoin/participation/by/centrevote/'+centrevote_id,
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
              

</script>
@endsection
