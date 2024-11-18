

                    <div>
                        @php
                        $nb = 0;
                        $taux = 0;

                    @endphp
                        <table /*id="datatable-buttons"*/ class="table table-bordered table-responsive-md table-striped text-center">
                            <thead>
                                <tr>

                                    <th> Ont OBTENU</th>
                                    <th>Voix</th>
                                    <th>% des voix</th>

                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($rts as $rt)
                                <tr>
                                    <td>{{ $rt->candidat }}</td>
                                    <td>{{ $rt->nb }}</td>
                                    <td>@if($votant>0){{ round(($rt->nb *100)/$votant,1)}} @endif</td>

                                </tr>
                                @php
                                $nb =   $rt->nb+ $nb;
                                $taux =  round(($rt->nb *100)/$votant,1) + $taux;

                            @endphp
                                @endforeach
                                <tr>
                                    <td>Total </td>
                                    <td>@php echo $nb; @endphp </td>
                                    <td>@php echo intval($taux); @endphp </td>

                                </tr>

                            </tbody>
                        </table>

</div>
<div class="col-4">
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Informations Générales</h5>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Inscrits : {{$inscrit}}</h6><br>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Votant : {{$votant +$bullnull }}</h6><br>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Nuls : {{$bullnull}}</h6><br>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Exprimés : {{$votant }}</h6><br>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Taux de participation : @if($inscrit>0){{ round(($votant*100)/$inscrit,2)}}% @endif</h6><br>
            <h6 class="badge badge-success" style="font-size: 17px ! important;">Siège  : @if(!empty($departement)){{$departement->nbcandidat}} @endif</h6>
        </div>
    </div>

</div>


