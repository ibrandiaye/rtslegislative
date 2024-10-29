@extends('welcome')
@section('title', '| centrevote')


@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="btn-group float-right">


                                <ol class="breadcrumb hide-phone p-0 m-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}" >ACCUEIL</a></li>
                                <li class="breadcrumb-item active"><a href="{{ route('centrevote.create') }}" >Liste des Lieu de vote</a></li>
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

    <div class="row">
        <div class="col">
            <h5>Nombre de Lieu de vote  : {{$nbCentreVote}}</h4>
           
           
        </div>
        <div class="col">
            <h5>Nombre de Bureau de Vote : {{$nbBureauVote}}</h4>
        </div>
        <div class="col">
            <h5>Nombre d'electeurs : {{$nbElecteur}}</h4>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h5>Complete  : {{$complet}}</h4>
           
           
        </div>
        <div class="col">
            <h5>Imcomplete : {{$incomplete}}</h4>
        </div>
        <div class="col">
            <h5>Nom Commencer : {{$nonCommence}}</h4>
        </div>
    </div>
<div class="col-12">
    <div class="card ">
        <div class="card-header  text-center">LISTE D'ENREGISTREMENT DES Lieu de vote</div>
            <div class="card-body">
              
                    <form method="POST" action="{{ route('searhArrondissement') }}">
                        @csrf
                        <div class="row">
                        <div class="col-lg-3">
                            <label>Commune</label>
                            <select class="form-control" id="commune_id" name="commune_id" required>
                                <option value="">Selectionnez</option>
                                @foreach ($communes as $item)
                                    <option value="{{$item->id}}" {{old("commune_id")==$item->id ? 'selected' : ''}}>{{$item->nom}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-lg-3">
                            <label>Lieu de vote</label>
                            <select class="form-control" name="centrevote_id" id="centrevote_id" >
                            
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <input type="submit" value="Valider" class="btn btn-primary" style="margin-top: 30px;">
                        </div>
                        
                    </div>
                    </form>
                   <br>
               
                <table id="datatable" class="table table-bordered table-responsive-md table-striped text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Commune</th>
                            <th>Nom Lieu de vote</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($centrevotes as $centrevote)
                        <tr>
                            <td>{{ $centrevote->id }}</td>
                            <td>{{ $centrevote->commune }}</td>
                            <td>{{ $centrevote->nom }}</td>
                         
                            <td>
                                <a href="{{ route('lieu.vote.by.centre', $centrevote->id) }}" role="button" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                             {{--    {!! Form::open(['method' => 'DELETE', 'route'=>['centrevote.destroy', $centrevote->id], 'style'=> 'display:inline', 'onclick'=>"if(!confirm('Êtes-vous sûr de vouloir supprimer cet enregistrement ?')) { return false; }"]) !!}
                                <button class="btn btn-danger"><i class="far fa-trash-alt"></i></button>
                                {!! Form::close() !!} --}}
                                <a href="{{ route('doc.centre', $centrevote->id) }}" role="button" class="btn btn-warning"><i class="fas fa-file"></i></a>

                              

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
   
        $("#commune_id").change(function () {
            var commune_id =  $("#commune_id").children("option:selected").val();
                var centrevote = "<option value=''>Veuillez selectionner</option>";
                $.ajax({
                    type:'GET',
                    url:url_app+'/centrevote/by/commune/'+commune_id,
               
                    data:'_token = <?php echo csrf_token() ?>',
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
                        data:'_token = <?php echo csrf_token() ?>',
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
           /*     $("#lieuvote_id").change(function () {
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
*/

</script>
@endsection
