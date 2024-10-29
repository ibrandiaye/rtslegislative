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
                        <li class="breadcrumb-item active"><a href="{{ route('rtscentre.index') }}">LISTE D'ENREGISTREMENT Par Pays</a></li>

                        </ol>
                    </div>
                    <h4 class="page-title">Renseignement Diaspora Par Pays</h4>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
       
        <form action="{{ route('rtspays.store') }}" method="POST" >
            @csrf
            <div id="electeur">
                
            </div>
            <div class="row">

            <div class="col-md-12">
             <div class="card ">
                        <div class="card-header text-center"></div>
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
                                        <select class="form-control" id="pays_id" name="pays_id" required>
    
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
                                        <input type="number" name="nbvote[]"  data-parsley-min="0" data-parsley-type="number" value="0" class="form-control"  required>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                              {{--    <div class="col-lg-6">
                                    <div class="form-group">
                                        <label> Nombre de vote valide</label>
                                        <input type="number" name="nbvv"  value="{{ old('nbvv') }}" class="form-control"  required>
                                    </div>
                                </div>  --}}
                                <br>
                                <input type="hidden" id="nb_electeur" name="nb_electeur">

                                    <div>
                                        <center>
                                            <button type="submit" class="btn btn-success btn btn-lg "> ENREGISTRER</button>
                                        </center>
                                    </div>
                                </div>

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
                    //alert(row);
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
                        url:'/sum/by/pays/'+pays_id,
                        vdata:'_token = <?php echo csrf_token() ?>',
                        success:function(data) {
                           // alert(data)
                            $('#electeur').empty()
                           $('#electeur').append("<h4> Nombre Electeurs : "+data+"</h4>") 
                           $('#nb_electeur').val(data)             
          
                        }
                    });
        });
      

          


</script>
@endsection