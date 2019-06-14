<!-- Styles -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
                <div class="text-center">
                    <h2>Animales por Razas</h2>
                </div>
                @if ($breeds)
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Raza</th>
                            <th scope="col">Cantidad</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($breeds as $breed)
                            <tr>
                                <td>{{ $breed->name }}</td>
                                <td>{{ $breed->animals_count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-secondary font-weight-bold text-light">
                            <tr>
                                <td>Total:</td>
                                <td>{{ $breeds->sum('animals_count') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <p>No existen Animales registrados.</p>
                @endif
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
