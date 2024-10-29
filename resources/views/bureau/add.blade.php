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
                        <li class="breadcrumb-item active"><a href="{{ route('bureau.index') }}">LISTE </a></li>

                        </ol>
                    </div>
                    <h4 class="page-title">Starter</h4>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <form action="{{ route('bureau.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
             <div class="card ">
                        <div class="card-header text-center">FORMULAIRE D'ENREGISTREMENT D'UNE Commune</div>
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
                                        <label>Commune</label>
                                        <select class="form-control" id="commune_id" name="commune_id" required>
                                            <option value="">Selectionner</option>
                                            @foreach ($communes as $commune)
                                            <option value="{{$commune->id}}">{{$commune->nom}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                        <div class="col-4">
                                            <label>centrevote</label>
                                            <select class="form-control" name="centrevote_id" id="centrevote_id" required="">
                                              {{--    @foreach ($centrevotes as $centrevote)
                                                <option value="{{$centrevote->id}}">{{$centrevote->nom}}</option>
                                                    @endforeach
      --}}
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label>Lieu de Vote</label>
                                            <select class="form-control" name="lieuvote_id" id="lieuvote_id" required="">
                                             {{--     @foreach ($lieuvotes as $lieuvote)
                                                <option value="{{$lieuvote->id}}">{{$lieuvote->nom}}</option>
                                                    @endforeach  --}}
    
                                            </select>
                                        </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Prenom </label>
                                            <input type="text" name="prenom"  value="{{ old('prenom') }}" class="form-control"  required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Nom </label>
                                            <input type="text" name="nom"  value="{{ old('nom') }}" class="form-control"  required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Numéro Tel </label>
                                            <input type="number" name="tel"  value="{{ old('tel') }}" class="form-control"  required>
                                        </div>
                                    </div>
                                   
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Fonction </label>
                                            <select class="form-control" id="fonction" name="fonction" required>
                                                <option value="">Selectionner</option>
                                                <option value="president" {{old('fonction')=='president' ? 'selected' : ''}}>Président</option>
                                                <option value="asceseur" {{old('fonction')=='asceseur' ? 'selected' : ''}}>Asceseur</option>
                                                <option value="secretaire" {{old('fonction')=='secretaire' ? 'selected' : ''}}>Secretaire </option>
                                                  
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Profession </label>
                                            <input type="text" name="profession"  value="{{ old('profession') }}" class="form-control"  >
                                        </div>
                                    </div>
                                    
                                </div>
                                <input type="hidden" id="nb_electeur" name="nb_electeur">
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
        console.log(url_app);
         $("#commune_id").change(function () {
            var commune_id =  $("#commune_id").children("option:selected").val();
                var centrevote = "<option value=''>Veuillez selectionner</option>";
                $.ajax({
                    type:'GET',
                    url:  url_app+'/centrevote/by/commune/'+commune_id,
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
                        url:url_app+'/lieuvote/by/centrevote/'+centrevote_id,
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

    </script>
@endsection