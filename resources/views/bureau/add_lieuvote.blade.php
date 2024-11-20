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
                               {{--  @if ($message = Session::get('success'))
                                <div class="alert alert-success">
                                    <p>{{ $message }}</p>
                                </div>
                            @endif --}}
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                                                            <div class="row">


                                    <input  type="hidden" name="commune_id" value="{{ $commune_id }}">
                                    <input  type="hidden" name="lieuvote_id" value="{{ $lieuvote_id }}">
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
                                                <option value="president">Président</option>
                                                <option value="asceseur" >Asceseur</option>
                                                <option value="secretaire" >Secretaire </option>

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
                                                                               <button type="submit" class="btn btn-success btn btn-lg "  onclick="this.disabled=true; this.form.submit();"> ENREGISTRER</button>

                                    </center>
                                </div>
                            </div>

                            </div>

            </form>



@endsection

