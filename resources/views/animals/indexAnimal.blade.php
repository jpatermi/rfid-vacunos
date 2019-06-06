@extends('template')

@section('content')
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-end p-2">
            <h2>Listado de Animales</h2>
            <a class="btn btn-primary mb-2" href="{{ route('animals.create') }}">Agregar Animal</a>
        </div>
      </div>
      <div class="card-body">
        @if ($animals->isNotEmpty())
            <table class="table table-striped table-hover">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">RFID</th>
                  <th scope="col">Género</th>
                  <th scope="col">Raza</th>
                  <th scope="col">Acciones</th>
                </tr>
              </thead>
              <tbody>
              @foreach ($animals as $animal)
              <tr>
                  <th scope="row">{{ $animal->animal_rfid }}</th>
                  @if($animal->gender == 'M')
                      <td>Macho</td>
                  @else
                      <td>Hembra</td>
                  @endif
                  <td>{{ $animal->breed->name }}</td>
                  <td>
                      <form action="{{ route('animals.destroy', $animal) }}" method="POST">
                          {{ method_field('DELETE') }}
                          @csrf
                          <a href="{{ route('animals.show', $animal->animal_rfid) }}" class="btn btn-link text-primary">
                              <span class="material-icons">visibility</span>
                          </a>
                          <a href="{{ route('animals.edit', $animal) }}" class="btn btn-link text-primary">
                              <span class="material-icons">edit</span>
                          </a>
                          <a href="{{ route('animalvaccination.show', $animal) }}" class="btn btn-link text-primary">
                              <span class="material-icons">bug_report</span>
                          </a>
                          <a href="{{ route('animaldewormer.show', $animal) }}" class="btn btn-link text-primary">
                              <span class="material-icons">gavel</span>
                          </a>
                          <a href="{{ route('animalvitamin.show', $animal) }}" class="btn btn-link text-primary">
                              <span class="material-icons">card_travel</span>
                          </a>
                          <a href="{{ route('disease.GetAnimalDiseases', $animal) }}" class="btn btn-link text-primary">
                              <span class="material-icons">alarm</span>
                          </a>
                          <button type="submit" class="btn btn-link text-primary">
                              <span class="material-icons">delete</span>
                          </button>
                      </form>
                  </td>
              </tr>
              @endforeach
              </tbody>
            </table>
        @else
            <p>No existen Razas registradas.</p>
        @endif
      </div>
      <div class="card-footer">
      </div>
    </div>
@endsection