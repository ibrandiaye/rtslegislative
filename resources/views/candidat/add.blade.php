{{-- \resources\views\permissions\create.blade.php --}}
@extends('welcome')

@section('title', '| Enregister Candidat')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="btn-group float-right">
                <ol class="breadcrumb hide-phone p-0 m-0">
                    <li class="breadcrumb-item"><a href="#">ACCUEIL</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('candidat.index') }}" >LISTE D'ENREGISTREMENT DES Candidat</a></li>
                </ol>
            </div>
            <h4 class="page-title">Starter</h4>
        </div>
    </div>
    <div class="clearfix"></div>
</div>


        <form action="{{ route('candidat.store') }}" method="POST" enctype="multipart/form-data">
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
                                        <label>Nom Candidat</label>
                                        <input type="text" name="nom"  value="{{ old('nom') }}" class="form-control"  required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nom Caolition</label>
                                        <input type="text" name="coalition"  value="{{ old('caolition') }}" class="form-control"  required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Photo</label>
                                        <input type="file" name="image"   class="form-control"  required>
                                    </div>
                                </div>
                                <div>

                                                                               <button type="submit" class="btn btn-success btn btn-lg "  onclick="this.disabled=true; this.form.submit();"> ENREGISTRER</button>


                                </div>
                            </div>

                            </div>

            </form>
@endsection


