{{-- \resources\views\permissions\create.blade.php --}}
@extends('welcome')

@section('title', '| Enregister Collecteur')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="btn-group float-right">
                <ol class="breadcrumb hide-phone p-0 m-0">
                    <li class="breadcrumb-item"><a href="#">ACCUEIL</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('collecteur.index') }}" >LISTE D'ENREGISTREMENT DES collecteur</a></li>
                </ol>
            </div>
            <h4 class="page-title">Starter</h4>
        </div>
    </div>
    <div class="clearfix"></div>
</div>


        <form action="{{ route('collecteur.store') }}" method="POST">
            @csrf
            <div class="card">
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nom</label>
                                        <input type="text" name="nom"  value="{{ old('nom') }}" class="form-control"  required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Numéro Téléphone</label>
                                        <input type="text" name="tel"  value="{{ old('tel') }}" class="form-control"  required>
                                    </div>
                                </div>
                                <div>

                                                                               <button type="submit" class="btn btn-success btn btn-lg "  onclick="this.disabled=true; this.form.submit();"> ENREGISTRER</button>


                                </div>
                            </div>

                            </div>

            </form>
@endsection


