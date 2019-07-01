<div class="modal fade" id="show">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold">Listado de Características Físicas</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method = "POST" action="{{ route('physicalCharacteristics.store') }}">
          <div class="form-row align-items-center nav justify-content-center">
            <div class="form-group col-sm-9">
              @csrf
              <label class="font-weight-bold" for="characteristic">Característica</label>
              <input class="form-control" type="text" id="characteristic" name="characteristic" placeholder="Característica">
              @if($errors->has('characteristic'))
                <p class="text-danger"><strong>{{ $errors->first('characteristic') }}</strong></p>
              @endif
            </div>
            <div class="form-group col-sm-2">
              <label for="animal_id"></label>
              <input class="form-control" type="text" name="animal_id" id="animal_id" hidden value="{{ old('animal_id', $animal->id) }}">
              <button type="submit" class="btn btn-primary">Agregar</button>
            </div>
          </div>
        </form>
        @if ($animal->physicalCharacteristics->isNotEmpty())
            <table class="table table-striped table-hover">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">N°</th>
                  <th scope="col">Característica</th>
                  <th scope="col">Acción</th>
                </tr>
              </thead>
              <tbody>
              @foreach ($animal->physicalCharacteristics as $physicalCharacteristic)
                <tr>
                    <th scope="row">{{ $i=$i+1 }}</th>
                    <td>{{ $physicalCharacteristic->characteristic }}</td>
                    <td>
                        <form action="{{ route('physicalCharacteristics.destroy', $physicalCharacteristic) }}" method="POST">
                            {{ method_field('DELETE') }}
                            @csrf
                            <button type="submit" class="btn btn-link text-primary">
                                <img class="" src="{{ asset('img/ico/baseline-delete-24px.svg') }}" alt="Eliminar">
                            </button>
                        </form>
                    </td>
                </tr>
              @endforeach
              </tbody>
            </table>
        @else
            <h5 class="text-center">No existen Características registradas.</h5>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

