@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
              <div class="card-header">
                <div class="d-flex justify-content-between align-items-end p-2">
                    <h2>Listado de Usuarios</h2>
                    <div>
                      @can('users.create')
                        <a class="btn btn-primary mb-2" href="{{ route('users.create') }}">Agregar Usuario</a>
                      @endcan
                    </div>
                </div>
              </div>
              <div class="card-body">
                @if ($users->isNotEmpty())
                    <table class="table table-striped table-hover">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">Nombre</th>
                          <th scope="col">Nombre de Usuario</th>
                          <th scope="col">Granja o Hacienda</th>
                          <th scope="col">Roles</th>
                          <th scope="col">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach ($users as $user)
                      <tr>
                          <td>{{ $user->name }}</td>
                          <td>{{ $user->username }}</td>
                          <td>{{ $user->farm->name }}</td>
                          <td>@foreach($user->roles as $role) {{ $role->name . " / " }} @endforeach</td>
                          <td>
                              <form action="{{ route('users.destroy', $user) }}" method="POST">
                                  {{ method_field('DELETE') }}
                                  @csrf
                                  @can('users.edit')
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-link text-primary">
                                        <span data-toggle="tooltip" data-placement="top" title="Editar Usuario" ><img class="" src="{{ asset('img/ico/baseline-edit-24px.svg') }}" alt="Editar"></span>
                                    </a>
                                  @endcan
                                  @can('users.destroy')
                                    <button type="submit" class="btn btn-link text-primary" onclick="return confirm('¿Esta seguro de eliminar a: {{ $user->name }}?')">
                                        <span data-toggle="tooltip" data-placement="top" title="Eliminar Usuario"><img class="" src="{{ asset('img/ico/baseline-delete-24px.svg') }}" alt="Eliminar"></span>
                                    </button>
                                  @endcan
                              </form>
                          </td>
                      </tr>
                      @endforeach
                      </tbody>
                    </table>
                @else
                    <p>No existen Usuarios registradas.</p>
                @endif
              </div>
              <div class="card-footer">
              </div>
            </div>
        </div>
    </div>
@endsection