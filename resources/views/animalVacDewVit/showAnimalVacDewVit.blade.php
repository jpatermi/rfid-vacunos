@extends('layouts.app')

@section('content')
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-end p-2">
            <h3>{{ $title }} al Animal:
              <small class="text-danger">{{ $animal->animal_rfid }}</small>
            </h3>
            <div>
              <a class="btn btn-primary mb-2" href="#" data-toggle="modal" data-target="#create">Agregar Aplicación</a>
              <a href="{{ route('animals.show', $animal->animal_rfid) }}" class="btn-link btn pb-3">Regresar al Detalle</a>
            </div>
        </div>
      </div>
      <div class="card-body">
        @if ($applications)
            <table class="table table-striped table-hover" id="datos">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">Nombre</th>
                  <th scope="col">Dosis</th>
                  <th scope="col">Aplicación</th>
                  <th scope="col">Acciones</th>
                </tr>
              </thead>
              <tbody>
              @foreach ($applications as $application)
              <tr>
                  <th>{{ $application['name_vac_desp_vit'] }}</th>
                  <td>{{ $application['dose'] }}</td>
                  <td id="fecha">{{ $application['application_date'] }}</td>
                  <td>
                      <form action="{{ route($model . '.destroy', $application['id_ani_vac_desp_vit']) }}" method="POST">
                          {{ method_field('DELETE') }}
                          @csrf
                          {{--<a href="{{ route($model . '.edit', $application) }}" class="btn btn-link text-primary">
                              <span class="material-icons"  data-toggle="tooltip" data-placement="top" title="Editar Aplicación">edit</span>
                          </a>--}}
                          <a href="{{ $application['id_ani_vac_desp_vit'] }}" class="btn btn-link text-primary" data-toggle="modal" data-target="#edit" name="{{ old('id', $application['id_ani_vac_desp_vit']) }}" onclick="LlenaCampos({{ $application['id_ani_vac_desp_vit'] }}, {{ $animal->id }}, {{ $application['id_vac_desp_vit'] }}, {{ $application['dose'] }}, `{{ $application['application_date'] }}`)">
                              <span data-toggle="tooltip" data-placement="top" title="Editar Aplicación" ><img class="" src="{{ asset('img/ico/baseline-edit-24px.svg') }}" alt="Editar"></span>
                          </a>
                          <button type="submit" class="btn btn-link text-primary" onclick="return confirm('¿Esta seguro de eliminar la aplicación: {{ $application['name_vac_desp_vit'] }}?')">
                              <span data-toggle="tooltip" data-placement="top" title="Eliminar Aplicación"><img class="" src="{{ asset('img/ico/baseline-delete-24px.svg') }}" alt="Eliminar"></span>
                          </button>
                      </form>
                  </td>
              </tr>
              @endforeach
              </tbody>
            </table>
            @include('animalVacDewVit.editAnimalVacDewVit')
        @else
            <p>No existen {{ $title }}.</p>
        @endif
      </div>
      <div class="card-footer">
        <a href="{{ route('animals.show', $animal->animal_rfid) }}" class="btn-link btn">Regresar al Detalle</a>
      </div>
    </div>
    <input type="text" name="nomCampoVacDewVit" id="nomCampoVacDewVit" hidden value="{{ $nomCampoVacDewVit }}">
@include('animalVacDewVit.createAnimalVacDewVit')
@endsection
@section('scripts')
  <script>
/*    $.when( $.ready ).then(function() {
        var tableReg = document.getElementById('datos');
        // Recorremos todas las filas con contenido de la tabla
        for (var i = 1; i < tableReg.rows.length; i++)
        {
        //console.log(convertDateFormatUSA_to_VZLA(tableReg.rows[i].cells[2].innerHTML));
        tableReg.rows[i].cells[2].innerHTML = convertDateFormatUSA_to_VZLA(tableReg.rows[i].cells[2].innerHTML);
        }

    });

    function convertDateFormatUSA_to_VZLA(string) {
      var info = string.split('-');
      return info[2].substr(0,2) + '/' + info[1] + '/' + info[0];
    };
*/
    $.when( $.ready ).then(function() {
      var nomCampoVacDewVit = document.getElementById('nomCampoVacDewVit').value;
      var vacdewvit_idCreate = document.getElementById('vacdewvit_idCreate');
      var vaccinationEdit = document.getElementById('vaccinationEdit');

      vacdewvit_idCreate.name = nomCampoVacDewVit;
      vaccinationEdit.name = nomCampoVacDewVit;
    });

    function LlenaCampos(id, animal_id, vaccination_id, dose, application_date) {
      var FormEdit = document.getElementById('FormEdit');
      var x = FormEdit.action;
      var y = x.split('/');
      var w = y[0];

      y[y.length-1] = id;
      for (var i = 1; i < y.length; i++)
      {
        w = w + '/' + y[i];
      }

      FormEdit.action = w;

      var idAnimalVaccination = document.getElementById('idAnimalVaccination');
      idAnimalVaccination.value = id;

      var doseEdit = document.getElementById('doseEdit');
      doseEdit.value = dose;

      var dateFaceEdit = document.getElementById('dateFaceEdit');
      dateFaceEdit.value = application_date;
      //var applicationDateEdit = document.getElementById('applicationDateEdit');
      //applicationDateEdit.value = application_date;
      $( "#dateFaceEdit" ).trigger( "change" );
      document.querySelector('#vaccinationEdit').value = vaccination_id;
      //console.log(id, animal_id, vaccination_id, dose, convertDateFormatUSA_to_VZLA(application_date), w);
    };
    //### Se usa en el Create ###
    $("#dateface").change(function(event) {
      $( "#application_date" ).attr("value", convertDateFormatVZLA_to_USA(`${dateface.value}`));
    });

    function convertDateFormatVZLA_to_USA(string) {
      var info = string.split('/');
      return info[2] + '-' + info[1] + '-' + info[0];
    };
    //### Se usa en el Edit ###
    $("#dateFaceEdit").change(function(event) {
      $( "#applicationDateEdit" ).attr("value", convertDateFormatVZLA_to_USA(`${dateFaceEdit.value}`));
    });

    function convertDateFormatVZLA_to_USA(string) {
      var info = string.split('/');
      return info[2] + '-' + info[1] + '-' + info[0];
    };
  </script>
@endsection