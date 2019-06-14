
<!-- Styles -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <div class="row justify-content-center">
        <div class="col-md-12">
                <div class="text-center">
                    <h2>Animales por Grupos Etarios</h2>
                </div>
                @if ($ageGroups)
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Grupo</th>
                            <th scope="col">Cantidad</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($ageGroups as $ageGroup)
                            <tr>
                                <td>{{ $ageGroup->name }}</td>
                                <td>{{ $ageGroup->animals_count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-secondary font-weight-bold text-light">
                            <tr>
                                <td>Total:</td>
                                <td>{{ $ageGroups->sum('animals_count') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <p>No existen Animales registrados.</p>
                @endif
        </div>
    </div>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
