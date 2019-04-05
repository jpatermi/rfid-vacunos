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
				    background-image: url("{{$url_photo}}/{{$animal_rfid}}.jpg");
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
		    @endif
		</div>
	</body>
</html>