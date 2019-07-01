<div class="modal fade" id="animalLocations">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold">Histórico de Movimientos</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div class="modal-body">
        @if ($animal->animalLocations->isNotEmpty())
            <table class="table table-striped table-hover">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">N°</th>
                  <th scope="col">Área</th>
                  <th scope="col">Ubicación UNO</th>
                  <th scope="col">Ubicación DOS</th>
                  <th scope="col">Fecha</th>
                </tr>
              </thead>
              <tbody>
              @foreach ($animal->animalLocations as $animalLocation)
                <tr>
                    <th scope="row">{{ $i=$i+1 }}</th>
                    <td>{{ $animalLocation->area->name }}</td>
                    <td>{{ $animalLocation->lct1->name }}</td>
                    <td>{{ $animalLocation->lct2->name }}</td>
                    <td>{{ $animalLocation->created_at->format('d/m/Y') }}</td>
                </tr>
              @endforeach
              </tbody>
            </table>
        @else
            <h5 class="text-center">No existen Movimientos registrados.</h5>
        @endif
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

