<!-- Styles -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <div class="row justify-content-center">
        <div class="col-md-12">
                <div class="text-center">
                    <h2>{{ $labelVacDewVit }} por Animales</h2>
                </div>
                @if ($totalGenerals)
                    <table class="table table-striped table-hover">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">RFID's</th>
                          <th scope="col">Aplicación</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($totalGenerals as $totalGeneral)
                        <tr>
                            <td class="pl-{{ $totalGeneral['nivel'] }} @if($totalGeneral['nivel'] == 1) font-weight-bold @endif">{{ $totalGeneral['vaccOrfid'] }}</td>
                            <td>{{ $totalGeneral['application_date'] }}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                @else
                    <p>No existen Animales registrados.</p>
                @endif
        </div>
    </div>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
