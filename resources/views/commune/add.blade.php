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
                        <li class="breadcrumb-item active"><a href="{{ route('commune.index') }}">LISTE D'ENREGISTREMENT DES Commune</a></li>

                        </ol>
                    </div>
                    <h4 class="page-title">Starter</h4>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <form action="{{ route('commune.store') }}" method="POST" enctype="multipart/form-data">
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
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Nom Commune</label>
                                        <input type="text" name="nom"  value="{{ old('nom') }}" class="form-control"  required>
                                    </div>
                                </div>
                                    <div class="col-lg-6">
                                        <label>Nom Département</label>
                                        <select class="form-control" name="departement_id" required="">
                                            @foreach ($departements as $departement)
                                            <option value="{{$departement->id}}">{{$departement->nom}}</option>
                                                @endforeach

                                        </select>
                                    </div>

                                <div>
                                    <center>
                                                                               <button type="submit" class="btn btn-success btn btn-lg "  onclick="this.disabled=true; this.form.submit();"> ENREGISTRER</button>

                                    </center>
                                </div>
                            </div>

                            </div>

            </form>



@endsection


