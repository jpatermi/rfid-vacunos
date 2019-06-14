<!-- Styles -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
                    <div class="text-center">
                    <h2>Animales por Ubicación</h2>
                </div>
                @if ($totalAreas)
                    <table class="table table-striped table-hover">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">Ubicación</th>
                          <th scope="col">Cantidad</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach ($totalAreas as $totalArea)
                      <tr>
                          <td class="pl-{{ $totalArea['nivel'] }} @if(($totalArea['nivel'] == 1 or $totalArea['nivel'] == 3)) font-weight-bold @endif">{{ $totalArea['areaLct1Lct2'] }}</td>
                          <td class="@if(($totalArea['nivel'] == 1 or $totalArea['nivel'] == 3)) font-weight-bold @endif">{{ $totalArea['cantidad'] }}</td>
                      </tr>
                      @endforeach
                      </tbody>
                      <tfoot class="bg-secondary font-weight-bold text-light">
                          <tr>
                              <td>Total de Animales:</td>
                              <td>{{ $total }}</td>
                          </tr>
                      </tfoot>
                    </table>
                @else
                    <p>No existen Animales registrados.</p>
                @endif
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
