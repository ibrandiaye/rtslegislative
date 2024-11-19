


                        <table  >
                            <thead>
                                <tr>

                                    <th>Commune</th>
                                    <th>Inscrit</th>
                                    <th>Votant</th>
                                    <th>Null</th>
                                    <th>Exprimes</th>
                                    @foreach ($candidats as $item)
                                    <th>{{$item->coalition}}</th>
                                    @endforeach

                                </tr>
                            </thead>
                            <tbody>
                           @foreach ($resultat as  $key => $item)
                               <tr>
                                <td>{{$key}}</td>
                                <td>{{$item->inscrit}}</td>
                                <td>{{$item->votant}}</td>
                                <td>{{$item->bulnull}}</td>
                                <td>{{$item->valable}}</td>
                                @foreach ($item->rts as  $key1 => $rt)
                                <td>{{$rt}}</td>
                                @endforeach
                               </tr>
                           @endforeach

                            </tbody>
                        </table>



