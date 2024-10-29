{{-- \resources\views\permissions\create.blade.php --}}
@extends('welcome')

@section('title', '| Modifier Département')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="btn-group float-right">
                        <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">ACCUEIL</a></li>
                        

                        </ol>
                    </div>
                     DGE
                   
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

        {!! Form::model($user, ['method'=>'PATCH','route'=>['user.update', $user->id]]) !!}
            @csrf
             <div class="card ">
                        <div class="card-header text-center">FORMULAIRE DE MODIFICATION Département</div>
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
                                            <label>Nom</label>
                                        <input type="text" name="name" class="form-control" value="{{$user->name}}"   required>
                                        </div>
    
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>email </label>
                                            <input type="email" name="email"  value=" {{$user->email }}" class="form-control"required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Arrondissement</label>
                                        <select class="form-control" name="arrondissement_id" >
                                            @foreach ($arrondissements as $arrondissement)
                                            <option {{old('arrondissement_id', $user->arrondissement_id) == $arrondissement->id ? 'selected' : ''}}
                                                value="{{$arrondissement->id}}">{{$arrondissement->nom}}</option>
                                                @endforeach
    
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Role</label>
                                        <select class="form-control" name="role" required="">
                                            <option value="">Selectionner</option>
                                            <option value="admin" {{$user->role=="admin" ? 'selected' : ''}}>Admin</option>
                                            <option value="sous_prefet"  {{$user->role=="sous_prefet" ? 'selected' : ''}}>Sous Prefet</option> 
                                            <option value="prefet"  {{$user->role=="prefet" ? 'selected' : ''}}>Prefet</option> 
                                            <option value="gouverneur"  {{$user->role=="gouverneur" ? 'selected' : ''}}>Gouverneur</option> 
                                            <option value="superviseur" {{$user->role=="superviseur" ? 'selected' : ''}}>Superviseur</option> 
                                        </select>
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