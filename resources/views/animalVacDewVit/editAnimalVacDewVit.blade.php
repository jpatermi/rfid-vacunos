<div class="modal fade" id="edit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Aplicación para el Animal:
          <small class="text-danger">{{ $animal->animal_rfid }}</small>
        </h3>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @if($errors->any())
          <div class="alert alert-danger">
            <h6>Por favor corregir los errores abajo señalados:</h6>
          </div>
        @endif

        <form method = "POST" action="{{ route($model . '.update', $application['id_ani_vac_desp_vit']) }}" id="FormEdit">
          {{ method_field('PUT') }}
          @csrf
          <div class="form-group">
              <label class="font-weight-bold" for="vaccinationEdit">{{ $label }}:</label>
              <select class="form-control" id="vaccinationEdit">
                @foreach($vaccinations as $vaccination)
                    <option value="{{ $vaccination->id }}">{{ $vaccination->name }}</option>
                  @endforeach
              </select>
            @if($errors->has('vaccination_id'))
              <p class="text-danger"><strong>{{ $errors->first('vaccination_id') }}</strong></p>
            @endif
          </div>
          <div class="form-group">
            <label class="font-weight-bold" for="dose">Dosis (cc):</label>
            <input type="text" class="form-control" name="dose" id="doseEdit" placeholder="00.00" value="{{ old('dose') }}">
            @if($errors->has('dose'))
              <p class="text-danger"><strong>{{ $errors->first('dose') }}</strong></p>
            @endif
          </div>
          <div class="form-group">
              <label class="font-weight-bold" for="dateface">Fecha de aplicación:</label>
              <div class="input-group date">
                  <input type="text" class="form-control datepicker" name="dateface" id="dateFaceEdit" placeholder="dd/mm/aaaa" autocomplete="off" value="{{ old('dateface') }}">
                    <img class="input-group-addon" src="{{ asset('img/ico/baseline-calendar_today-24px.svg') }}" alt="Calendario">
              </div>
              @if($errors->has('application_date'))
                <p class="text-danger"><strong>{{ $errors->first('application_date') }}</strong></p>
              @endif
          </div>
          <!-- Textos ocultos que  vendrán en la sesión para el user_id y el farm_id -->
          <input type="text" name="id" id="idAnimalVaccination" hidden value="{{ old('id') }}">
          <input type="text" name="animal_id" id="animalIdEdit" hidden value="{{ old('animal_id', $animal->id) }}">
          <input type="text" name="application_date" id="applicationDateEdit" hidden>
          <!-- -->
          <button type="submit" class="btn btn-primary">Actualizar {{ $label }}</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@section('script_fecha_edit')
    <script>
        $("#dateFaceEdit").change(function(event) {
          $( "#applicationDateEdit" ).attr("value", convertDateFormatVZLA_to_USA(`${dateFaceEdit.value}`));
        });

        function convertDateFormatVZLA_to_USA(string) {
          var info = string.split('/');
          return info[2] + '-' + info[1] + '-' + info[0];
        };
    </script>
@endsection
