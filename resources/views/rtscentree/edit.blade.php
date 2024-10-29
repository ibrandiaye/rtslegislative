{{-- \resources\views\permissions\create.blade.php --}}
@extends('welcome')

@section('title', '| Modifier Région')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="btn-group float-right">

                        <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" role="button" class="btn btn-primary">ACCUEIL</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('rtscentree.index') }}" role="button" class="btn btn-primary">RETOUR</a></li>

                        </ol>

                    </div>
                    <h4 class="page-title">Starter</h4>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        {!! Form::model($rtscentree, ['method'=>'PATCH','route'=>['rtscentree.update', $rtscentree->id],'enctype'=>'multipart/form-data']) !!}
            @csrf
             <div class="card ">
                        <div class="card-header text-center">FORMULAIRE DE MODIFICATION D'une rtscentree</div>
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

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Nombre de votes</label>
                                    <input type="text" name="nbvote" class="form-control" value="{{$rtscentree->nbvote}}"   required>
                                    </div>

                                </div>
                                {{--  <div class="col-lg-6">
                                    <div class="form-group">
                                        <label> Nombre de vote valide</label>
                                        <input type="number" name="nbvv"  value="{{ $rtscentree->nbvv }}" class="form-control"  required>
                                    </div>
                                </div>  --}}
                                <div class="col-lg-6">
                                    <label>Centre de vote</label>
                                    <select class="form-control" name="centrevotee_id" required="">
                                        @foreach ($centrevotees as $centrevotee)
                                        <option {{old('centrevotee_id', $rtscentree->centrevotee_id) == $centrevotee->id ? 'selected' : ''}}
                                            value="{{$centrevotee->id}}">{{$centrevotee->nom}}</option>
                                            @endforeach

                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label>Candidat</label>
                                    <select class="form-control" name="candidat_id" required="">
                                        @foreach ($candidats as $candidat)
                                        <option {{old('candidat_id', $rtscentree->candidat_id) == $candidat->id ? 'selected' : ''}}
                                            value="{{$candidat->id}}">{{$candidat->nom}}</option>
                                            @endforeach

                                    </select>
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
