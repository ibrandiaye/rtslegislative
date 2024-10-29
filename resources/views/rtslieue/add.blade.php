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
        <form action="{{ route('rtslieue.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="electeur">

            </div>
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
                                    <div class="col">
                                        <label>Juridiction</label>
                                        <select class="form-control" id="juridiction_id" name="juridiction_id" required="">
                                            <option value="">Selectionner</option>
                                            @foreach ($juridictions as $juridiction)
                                            <option value="{{$juridiction->id}}">{{$juridiction->nom}}</option>
                                                @endforeach
    
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label>Pays</label>
                                        <select class="form-control" id="pays_id" name="pays_id">
                                        <select class="form-control" id="pays_id" name="pays_id" required>
    
                                        </select>
                                    </div>
    
                                      <div class="col">
                                        <label>Localite</label>
                                        <select class="form-control" id="localite_id" name="localite_id" >
                                        <select class="form-control" id="localite_id" name="localite_id" required>
    
                                        </select>
                                    </div>
                                        <div class="col">
                                            <label>centrevotee</label>
                                            <select class="form-control" name="centrevotee_id" id="centrevotee_id" required="">
                                              {{--    @foreach ($centrevotees as $centrevotee)
                                                <option value="{{$centrevotee->id}}">{{$centrevotee->nom}}</option>
                                                    @endforeach
      --}}
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label>Lieu de Vote</label>
                                            <select class="form-control" name="lieuvotee_id" id="lieuvotee_id" required="">
                                             {{--     @foreach ($lieuvotees as $lieuvotee)
                                                <option value="{{$lieuvotee->id}}">{{$lieuvotee->nom}}</option>
                                                    @endforeach  --}}
    
                                            </select>
                                        </div>
                                </div>
                                <br>    
                                <div class="row">
                                @foreach ($candidats as $candidat )

                                <input type="hidden" name="candidat_id[]" value="{{ $candidat->id }}">
                               
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label> {{ $candidat->nom }} <img src="{{ asset('photo/'.$candidat->photo) }}" class="img img-rounded" style="height: 30px;"></label>
                                        <input type="number" name="nbvote[]" data-parsley-min="0" data-parsley-type="number"  value="0" class="form-control"  required>
                                    </div>
                                </div>
                                @endforeach
                                {{--  <div class="col-lg-6">
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
                                    </div>  --}}

                                </div>
                                <input type="hidden" id="nb_electeur" name="nb_electeur">
                                <div>
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
   $("#juridiction_id").change(function () {
    var juridiction_id =  $("#juridiction_id").children("option:selected").val();
    $(".juridiction").val(juridiction_id);
    $(".pays").val("");
    $(".localite").val("");
        var pays = "<option value=''>Veuillez selectionner</option>";
        $.ajax({
            type:'GET',
            url:'/pays/by/juridiction/'+juridiction_id,
       
            data:'_token = <?php echo csrf_token() ?>',
            success:function(data) {
                //alert(data);
                $.each(data.pays,function(index,row){
                   // alert(row);
                    pays +="<option value="+row.id+">"+row.nom+"</option>";

                });
                $("#pays_id").empty();
                $("#localite_id").empty();
                $("#pays_id").append(pays);
            }
        });
    });
    $("#pays_id").change(function () {
        var pays_id =  $("#pays_id").children("option:selected").val();
        $(".pays").val(pays_id);
        $(".localite").val("");
            var localite = "<option value=''>Veuillez selectionner</option>";
            $.ajax({
                type:'GET',
                url:'/localite/by/pays/'+pays_id,
                data:'_token = <?php echo csrf_token() ?>',
                success:function(data) {

                    $.each(data.localites,function(index,row){
                        //alert(row.nomd);
                        localite +="<option value="+row.id+">"+row.nom+"</option>";

                    });
                    $("#localite_id").empty();
                    $("#localite_id").append(localite);
                }
            });
        });
        $("#localite_id").change(function () {
            var localite_id =  $("#localite_id").children("option:selected").val();
                var centrevotee = "<option value=''>Veuillez selectionner</option>";
                $.ajax({
                    type:'GET',
                    url:'/centrevotee/by/localite/'+localite_id,
                    vdata:'_token = <?php echo csrf_token() ?>',
                    success:function(data) {

                        $.each(data.centrevotes,function(index,row){
                            //alert(row.nomd);
                            centrevotee +="<option value="+row.id+">"+row.nom+"</option>";

                        });
                        $("#centrevotee_id").empty();
                        $("#centrevotee_id").append(centrevotee);
                    }
                });
            });

          
            $("#centrevotee_id").change(function () {
                var centrevotee_id =  $("#centrevotee_id").children("option:selected").val();
                    var lieuvotee = "<option value=''>Veuillez selectionner</option>";
                    $.ajax({
                        type:'GET',
                        url:'/lieuvotee/by/centrevotee/'+centrevotee_id,
                    //   url:'http://vmi435145.contaboserver.net:9000/localite/by/localite/'+localite_id,
                     //  url:'http://127.0.0.1/gestionmateriel/public/localite/by/localite/'+localite_id,
                    //  url:'http://127.0.0.1:8000/localite/by/localite/'+localite_id,
                        vdata:'_token = <?php echo csrf_token() ?>',
                        success:function(data) {

                            $.each(data.lieuvotes,function(index,row){
                              //  alert(row.id);
                                lieuvotee +="<option value="+row.id+">"+row.nom+"</option>";

                            });
                            $("#lieuvotee_id").empty();
                            $("#lieuvotee_id").append(lieuvotee);
                        }
                    });
                });

                $("#lieuvotee_id").change(function () {
                var lieuvotee_id =  $("#lieuvotee_id").children("option:selected").val();
                    $.ajax({
                        type:'GET',
                        url:'/electeur/by/lieuvotee/'+lieuvotee_id,
                        vdata:'_token = <?php echo csrf_token() ?>',
                        success:function(data) {
                         //   alert(data)
                           
                            $('#electeur').empty()
                           $('#electeur').append("<h4> Nombre Electeurs : "+data.nb+"</h4>");
                           $('#nb_electeur').val(data.nb)             
                        }
                    });
                });

               

</script>
@endsection
