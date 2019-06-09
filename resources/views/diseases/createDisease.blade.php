@extends('layouts.app')

@section('content')
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-end p-2">
          <h3>Enfermedad para el Animal:
            <small class="text-danger">{{ $animal->animal_rfid }}</small>
          </h3>
        </div>
      </div>
      <div class="card-body">
        @if($errors->any())
          <div class="alert alert-danger">
            <h6>Por favor corregir los errores abajo señalados:</h6>
          </div>
        @endif

        <form method = "POST" action="{{ route('diseases.store') }}">
          @csrf
          <div class="form-row nav justify-content-center">
            <div class="form-group col-md-4">
                <label class="font-weight-bold" for="veterinarian_id">Veterinario:</label>
                <select class="form-control" name="veterinarian_id">
                    @foreach($veterinarians as $veterinarian)
                        <option value="{{ $veterinarian->id }}">{{ $veterinarian->name }}</option>
                    @endforeach
                </select>
                @if($errors->has('veterinarian_id'))
                    <p class="text-danger"><strong>{{ $errors->first('veterinarian_id') }}</strong></p>
                @endif
            </div>
            <div class="form-group col-md-4">
                <label class="font-weight-bold" for="dateface">Fecha de revisión:</label>
                <div class="input-group date">
                    <input type="text" class="form-control datepicker" name="dateface" id="dateface" placeholder="dd/mm/aaaa" autocomplete="off" value="{{ old('dateface') }}">
                      <img class="input-group-addon" src="{{ asset('img/ico/baseline-calendar_today-24px.svg') }}" alt="Calendario">
                </div>
                @if($errors->has('review_date'))
                  <p class="text-danger"><strong>{{ $errors->first('review_date') }}</strong></p>
                @endif
            </div>
          </div>
          <div class="form-row nav justify-content-center">
            <div class="form-group col-md-4">
                <label class="font-weight-bold" for="diagnostic_id">Diagnóstico:</label>
                <select class="form-control" name="diagnostic_id">
                    @foreach($diagnostics as $diagnostic)
                        <option value="{{ $diagnostic->id }}">{{ $diagnostic->name }}</option>
                    @endforeach
                </select>
                @if($errors->has('diagnostic_id'))
                    <p class="text-danger"><strong>{{ $errors->first('diagnostic_id') }}</strong></p>
                @endif
            </div>
            <div class="form-group col-md-4">
                <label class="font-weight-bold" for="cause_id">Causa:</label>
                <select class="form-control" name="cause_id">
                    @foreach($causes as $cause)
                        <option value="{{ $cause->id }}">{{ $cause->name }}</option>
                    @endforeach
                </select>
                @if($errors->has('cause_id'))
                    <p class="text-danger"><strong>{{ $errors->first('cause_id') }}</strong></p>
                @endif
            </div>
          </div>
          <div class="form-row nav justify-content-center">
            <div class="form-group col-md-4">
                <label class="font-weight-bold" for="responsible_id">Responsable:</label>
                <select class="form-control" name="responsible_id">
                    @foreach($responsibles as $responsible)
                        <option value="{{ $responsible->id }}">{{ $responsible->name }}</option>
                    @endforeach
                </select>
                @if($errors->has('responsible_id'))
                    <p class="text-danger"><strong>{{ $errors->first('responsible_id') }}</strong></p>
                @endif
            </div>
          </div>
          <hr>
          <div class="form-row nav justify-content-center">
            <div class="form-group col-md-4">
              <label class="font-weight-bold" for="ptratamiento">Tratamiento:</label>
              <select class="form-control" name="ptratamiento" id="ptratamiento">
                  @foreach($treatments as $treatment)
                      <option value="{{ $treatment->id }}">{{ $treatment->name }}</option>
                  @endforeach
              </select>
            </div>
            <div class="form-group col-md-4">
              <label class="font-weight-bold" for="pindicacion">Indicación:</label>
              <input type="text" class="form-control" name="pindicacion" id="pindicacion" autocomplete="off" value="{{ old('pindicacion') }}">
            </div>
            <div class="form-group justify-content-center">
              <br>
              <!--<button class="btn btn-primary mt-2 ml-1" id="btn_add">Agregar</button>-->
              <input type="button" class="btn btn-primary mt-2 ml-1" id="btn_add" value="Agregar">
            </div>
          </div>
          <table class="table table-striped table-hover" id="table_tratamiento">
            <thead class="thead-dark">
              <th>Tratamiento</th>
              <th>Indicación</th>
              <th>Acción</th>
            </thead>
            <tbody>

            </tbody>
          </table>
          <!-- Textos ocultos que  vendrán en la sesión para el animal_id y la review_date -->
          <input type="text" name="animal_id" id="animal_id" hidden value="{{ old('animal_id', $animal->id) }}">
          <input type="text" name="review_date" id="review_date" hidden value="{{ old('review_date') }}">
          <!-- -->
          <div class="form-row nav justify-content-center">
            <button type="submit" class="btn btn-primary">Agregar Enfermedad</button>
          </div>
        </form>
      </div>
      <div class="card-footer">
        <a href="{{ route('disease.GetAnimalDiseases', $animal->id) }}" class="btn-link btn">Regresar al listado de Enfermedades</a>
      </div>
    </div>
@endsection
@section('scripts')
    <script>
        $("#dateface").change(function(event) {
          $( "#review_date" ).attr("value", convertDateFormatVZLA_to_USA(`${dateface.value}`));
        });

        function convertDateFormatVZLA_to_USA(string) {
          var info = string.split('/');
          return info[2] + '-' + info[1] + '-' + info[0];
        };
        //### JS para manejar la tabla de Tratamientos ###
        var cont = 0;

        $(document).ready(function() {
          $("#btn_add").click(function(event) {
            agregar();
          });
        });

        function agregar() {
          idTratamiento = $("#ptratamiento").val();
          tratamiento = $("#ptratamiento option:selected").text();
          indicacion = $("#pindicacion").val();

          if (indicacion != "") {
            var fila = '<tr id="fila' + cont +'"><td><input type="hidden" name="treatment_id[]" value="'+idTratamiento+'">'+tratamiento+'</td><td><input type="hidden" name="indication[]" value="'+indicacion+'">'+indicacion+'</td><td><button type="button" class="btn btn-danger" onclick="eliminar('+cont+')">X</button></td></tr>';
            cont++;
            limpiar();
            $("#table_tratamiento").append(fila);
          }
          else {
            alert("La indicación es obligatoria");
          }
        }

        function limpiar () {
          $("#pindicacion").val("");
        }

        function eliminar(index) {
          $("#fila" + index).remove();
        }

    </script>
@endsection
