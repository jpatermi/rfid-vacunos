@extends('layouts.app')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
			<div class="card">
				<div class="card-header">
					<div class="nav justify-content-center">
						<h4>Agregar Usuario</h4>
					</div>
				</div>
				<div class="card-body">
					@if($errors->any())
						<div class="alert alert-danger">
							<h6 class="font-weight-bold">Por favor corregir los errores abajo señalados:</h6>
						</div>
					@endif

					<form method = "POST" action="{{ route('users.store') }}">
						@csrf
		                <div class="form-group row">
		                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nombre y Apellido') }}</label>

		                    <div class="col-md-6">
		                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

		                        @if ($errors->has('name'))
		                            <span class="invalid-feedback" role="alert">
		                                <strong>{{ $errors->first('name') }}</strong>
		                            </span>
		                        @endif
		                    </div>
		                </div>

		                <div class="form-group row">
		                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Dirección de Correo') }}</label>

		                    <div class="col-md-6">
		                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

		                        @if ($errors->has('email'))
		                            <span class="invalid-feedback" role="alert">
		                                <strong>{{ $errors->first('email') }}</strong>
		                            </span>
		                        @endif
		                    </div>
		                </div>

		                <div class="form-group row">
		                    <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Nombre de Usuario') }}</label>

		                    <div class="col-md-6">
		                        <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required>

		                        @if ($errors->has('username'))
		                            <span class="invalid-feedback" role="alert">
		                                <strong>{{ $errors->first('username') }}</strong>
		                            </span>
		                        @endif
		                    </div>
		                </div>

		                <div class="form-group row">
		                    <label for="farm_id" class="col-md-4 col-form-label text-md-right">{{ __('Granja o Hacienda') }}</label>

		                    <div class="col-md-6">
						    	<select class="form-control" id="farm_id" name="farm_id">
						    		@foreach($farms as $farm)
						      			<option value="{{ $farm->id }}">{{ $farm->name }}</option>
						      		@endforeach
						    	</select>
								@if($errors->has('farm_id'))
									<p class="text-danger"><strong>{{ $errors->first('farm_id') }}</strong></p>
								@endif
							</div>
		                </div>

		                <div class="form-group row">
		                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Clave') }}</label>

		                    <div class="col-md-6">
		                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

		                        @if ($errors->has('password'))
		                            <span class="invalid-feedback" role="alert">
		                                <strong>{{ $errors->first('password') }}</strong>
		                            </span>
		                        @endif
		                    </div>
		                </div>

		                <div class="form-group row">
		                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirmar Clave') }}</label>

		                    <div class="col-md-6">
		                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
		                    </div>
		                </div>

		                <hr>

		                <div class="form-group">
		                	<div class="col-md-6 offset-md-4">
				                <h3>Lista de Roles</h3>
								<ul class="list-unstyled">
									@foreach($roles as $role)
									<li>
										<label>
											<input type="checkbox" name="roles[]" value="{{ $role->slug }}"> {{ $role->name }}
											<em>({{ $role->description }})</em>
										</label>
									</li>
									@endforeach
								</ul>
		                        @if ($errors->has('roles'))
		                            <span class="text-danger">
		                                <strong>{{ $errors->first('roles') }}</strong>
		                            </span>
		                        @endif
							</div>
		                </div>

		                <div class="form-group row mb-0">
		                    <div class="col-md-6 offset-md-4">
		                        <button type="submit" class="btn btn-primary">
		                            {{ __('Agregar Usuario') }}
		                        </button>
		                    </div>
		                </div>
						{{--<div class="form-row nav justify-content-center">
							<button type="submit" class="btn btn-primary">Agregar Usuario</button>
						</div>--}}
					</form>
				</div>
				<div class="card-footer">
					<a href="{{ route('users.index') }}" class="btn-link btn">Regresar al listado de Usuarios</a>
				</div>
			</div>
		</div>
	</div>
@endsection