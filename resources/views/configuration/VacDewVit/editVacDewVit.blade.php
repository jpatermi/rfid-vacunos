@extends('layouts.app')
@section('content')
	<div class="card">
		<div class="card-header">
			<div class="nav justify-content-center">
				<h4>Actualizar {{ $labelVacDewVit }}</h4>
			</div>
		</div>
		<div class="card-body">
			@if($errors->any())
				<div class="alert alert-danger">
					<h6>Por favor corregir los errores abajo señalados:</h6>
				</div>
			@endif

			<form method = "POST" action="{{ route($model . '.update', $varVacDewVit) }}">
				{{ method_field('PUT') }}
				@csrf
				<div class="form-row nav justify-content-center">
					<div class="form-group col-md-8">
						<label for="name">Nombre:</label>
						<input type="text" class="form-control" name="name" id="name" placeholder="Nombre {{ $labelVacDewVit }}" value="{{ old('name', $varVacDewVit->name) }}">
						@if($errors->has('name'))
							<p class="text-danger"><strong>{{ $errors->first('name') }}</strong></p>
						@endif
					</div>
					<div class="form-group col-md-8">
						<label for="characteristic">Característica:</label>
						<input type="text" class="form-control" name="characteristic" id="characteristic" placeholder="Característica {{ $labelVacDewVit }}" value="{{ old('characteristic', $varVacDewVit->characteristic) }}">
						@if($errors->has('characteristic'))
							<p class="text-danger"><strong>{{ $errors->first('characteristic') }}</strong></p>
						@endif
					</div>
					@if($labelVacDewVit != 'Examen')
	                <div class="form-group col-md-8">
	                    <label class="font-weight-bold" for="dateface">Fecha de vencimiento:</label>
	                    <div class="input-group date">
	                        <input type="text" class="form-control datepickerV" name="dateface" id="dateface" placeholder="dd/mm/aaaa" autocomplete="off" value="{{ old('dateface', $varVacDewVit->expiration_date->format('d/m/Y')) }}">
	                          <img class="input-group-addon" src="{{ asset('img/ico/baseline-calendar_today-24px.svg') }}" alt="Calendario">
	                    </div>
						@if($errors->has('expiration_date'))
							<p class="text-danger"><strong>{{ $errors->first('expiration_date') }}</strong></p>
						@endif
	                </div>
				    <div class="form-group col-md-8">
						<label class="font-weight-bold" for="lot">Lote:</label>
						<input type="text" class="form-control" name="lot" id="lot" placeholder="Lote {{ $labelVacDewVit }}" value="{{ old('lot', $varVacDewVit->lot) }}">
						@if($errors->has('lot'))
							<p class="text-danger"><strong>{{ $errors->first('lot') }}</strong></p>
						@endif
				    </div>
				    @endif
				</div>
				@if($labelVacDewVit != 'Examen')
				<!-- Textos ocultos que  vendrán en la sesión para el user_id y el farm_id -->
				<input type="text" name="expiration_date" id="expiration_date" hidden value="{{ old('expiration_date', $varVacDewVit->expiration_date) }}">
				<!-- -->
				@endif
				<div class="form-row nav justify-content-center">
					<button type="submit" class="btn btn-primary">Actualizar {{ $labelVacDewVit }}</button>
				</div>
			</form>
		</div>
		<div class="card-footer">
			<a href="{{ route($model . '.index') }}" class="btn-link btn">Regresar al listado de {{ $labelVacDewVit }}s</a>
		</div>
	</div>
@endsection
@section('scripts')
    <script>
        $("#dateface").change(function(event) {
          $( "#expiration_date" ).attr( "value", convertDateFormatVZLA_to_USA(`${dateface.value}`));
        });

        function convertDateFormatUSA_to_VZLA(string) {
          var info = string.split('-');
          return info[2] + '/' + info[1] + '/' + info[0];
        };

        function convertDateFormatVZLA_to_USA(string) {
          var info = string.split('/');
          return info[2] + '-' + info[1] + '-' + info[0];
        };

        $('.datepickerV').datepicker({
            format: "dd/mm/yyyy",
            language: "es",
            autoclose: true,
            todayHighlight: true,
            orientation: "bottom auto",
            startDate: "01/01/2000",
            forceParse: false,
            //endDate: "todate()",
            todayBtn: false
        });
    </script>
@endsection
