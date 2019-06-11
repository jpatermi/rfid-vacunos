<!doctype html>
<html>
	<head>
		<title>Photo Animal</title>
		<style>
			.photo {
			    margin: 0px auto;
			    height: 300px;
			    max-width: 500px;
			    @if ($animal_rfid != 'no_existe')
				    background-image: url("{{ asset('/storage/photo/' . $animal_rfid . '.jpg') }}");
				    background-position: center;
				    background-size: cover;
				@else
					background-color: gray;
			    @endif
			}
			.texto {
			    position: relative;
			    top: 130px;
			    text-align: center;
			}
		</style>
	</head>
	<body>
		<div class="photo">
		    @if ($animal_rfid == 'no_existe')
			    <h2 class="texto">Sin Foto</h2>
			    <h4>{{ asset('/storage/photo/' . $animal_rfid . '.jpg') }}</h4>
			{{--@else
				<img src="{{ asset('/storage/photo/' . $animal_rfid . '.jpg') }}" alt="Foto Animal" width="500px" height="300px">--}}
		    @endif
		</div>
	</body>
</html>