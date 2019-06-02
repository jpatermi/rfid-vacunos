<div class="modal fade" id="create">
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

        <form method = "POST" action="{{ route($model . '.store') }}">
          @csrf
          <div class="form-group">
              <label class="font-weight-bold" for="vacdewvit_idCreate">{{ $label }} :</label>
              <select class="form-control" id="vacdewvit_idCreate">
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
            <input type="text" class="form-control" name="dose" id="dose" placeholder="00.00" value="{{ old('dose') }}">
            @if($errors->has('dose'))
              <p class="text-danger"><strong>{{ $errors->first('dose') }}</strong></p>
            @endif
          </div>
          <div class="form-group">
              <label class="font-weight-bold" for="dateface">Fecha de aplicación:</label>
              <div class="input-group date">
                  <input type="text" class="form-control datepicker" name="dateface" id="dateface" placeholder="dd/mm/aaaa" autocomplete="off" value="{{ old('dateface') }}">
                    <img class="input-group-addon" src="{{ asset('img/ico/baseline-calendar_today-24px.svg') }}" alt="Calendario">
              </div>
              @if($errors->has('application_date'))
                <p class="text-danger"><strong>{{ $errors->first('application_date') }}</strong></p>
              @endif
          </div>
          <!-- Textos ocultos que  vendrán en la sesión para el user_id y el farm_id -->
          <input type="text" name="animal_id" id="animal_id" hidden value="{{ old('animal_id', $animal->id) }}">
          <input type="text" name="application_date" id="application_date" hidden value="{{ old('application_date') }}">
          <!-- -->
          <button type="submit" class="btn btn-primary">Agregar {{ $label }}</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@section('script_area_lct1_lct2')
    <script>
        $("#dateface").change(function(event) {
          $( "#application_date" ).attr("value", convertDateFormatVZLA_to_USA(`${dateface.value}`));
        });

        function convertDateFormatVZLA_to_USA(string) {
          var info = string.split('/');
          return info[2] + '-' + info[1] + '-' + info[0];
        };
    </script>
@endsection
