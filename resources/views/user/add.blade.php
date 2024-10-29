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
                        <li class="breadcrumb-item active"><a href="{{ route('user.index') }}" >ARRONDISSEMENT D'ENREGISTREMENT DES users</a></li>

                        </ol>
                    </div>
                    DGE
                      
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

        <form action="{{ route('user.store') }}" method="POST">
            @csrf
             <div class="card">
                        <div class="card-header  text-center">FORMULAIRE D'ENREGISTREMENT D'UN Département</div>
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
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Nom </label>
                                            <input type="text" name="name"  value="{{ old('name') }}" class="form-control"required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>email </label>
                                            <input type="email" name="email"  value="{{ old('email') }}" class="form-control"required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Mot de Passe </label>
                                            <input id="password" type="password" name="password"  value="{{ old('password') }}" class="form-control"required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Confirmer Mot de passe </label>
                                            <input id="password-confirm" type="password"  name="password_confirmation" value="{{ old('name') }}" class="form-control"required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Région</label>
                                        <select class="form-control" id="region_id" name="region_id" required="">
                                            <option value="">Selectionner</option>
                                            @foreach ($regions as $region)
                                            <option value="{{$region->id}}">{{$region->nom}}</option>
                                                @endforeach
    
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Département</label>
                                        <select class="form-control" id="departement_id" name="departement_id" >
    
                                        </select>
                                    </div>
                                   
                                        <div class="col-lg-6">
                                            <label>Arrondissement</label>
                                            <select class="form-control" name="arrondissement_id" id="arrondissement_id">
                                                {{-- <option value="">Selectionner</option>
                                                @foreach ($arrondissements as $arrondissement)
                                                <option value="{{$arrondissement->id}}">{{$arrondissement->nom}}</option>
                                                    @endforeach --}}
    
                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <label>Role</label>
                                            <select class="form-control" name="role" required="">
                                                <option value="">Selectionner</option>
                                                <option value="admin">Admin</option>
                                                <option value="sous_prefet">Sous prefet</option> 
                                                <option value="prefet">prefet</option> 
                                                <option value="gouverneur">Gouverneur</option> 
                                                <option value="superviseur">Superviseur</option> 
                                            </select>
                                        </div>
                                </div>
                                

                                <div>
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
   // $(".region").val(region_id);
   // $(".departement").val("");
    $(".commune").val("");
    $("#departement_id").empty();
    $("#commune_id").empty();
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
      //  $(".departement").val(departement_id);
       // $(".commune").val("");
       $("#arrondissement_id").empty();
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
                   
                    $("#arrondissement_id").append(arrondissement);
                }
            });
        });
         
              


</script>
@endsection
