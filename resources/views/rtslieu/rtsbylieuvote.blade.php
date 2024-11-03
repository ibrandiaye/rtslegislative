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
        <div id="electeur">

        </div>
            @csrf
             <div class="card ">
                        <div class="card-header text-center"> Resultat Lieu de vote  : {{$centrevote->nom}}, Bureau N° {{$lieuvote->nom}}</div>
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
                                @if ($message = Session::get('success'))
                                <div class="alert alert-success">
                                    <p>{{ $message }}</p>
                                </div>
                            @endif
                                <div class="row">

                                    <div class="col-12">
                                        <table class="table table-bordered table-responsive-md table-striped text-center">
                                            <thead>
                                                <th>Liste</th>
                                                <th>Resultat</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($rtslieus as $candidat )
                                                <tr>
                                                    <td>
                                                        <label> {{ $candidat->coalition }} <img src="{{ asset('photo/'.$candidat->photo) }}" class="img img-rounded" style="height: 30px;"></label>
                                                    </td>
                                                    <td><input type="number" readonly name="nbvote[]" data-parsley-min="0" data-parsley-type="number"  value="{{$candidat->nbvote}}" class="form-control"  required>
                                                      
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div>
                                  
                                </div>
                            </div>

                            </div>

       


@endsection


