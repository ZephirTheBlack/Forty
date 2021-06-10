@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                @include('partials.validation-errors')
                <div class="card border-0 bg-light px-4 py-2">

                    <form action="{{route('users.update', $user)}}" method="post" enctype="multipart/form-data">
                        <h4 class="text-info text-center text-"> Actualiza tu perfil {{$user->name}}</h4>
                        @csrf
                        @method('PATCH')
                        <div class="card-body">
                            <div class="form-group">
                                <label for="">Avatar:</label><br>
                                <center><img src="{{Storage::url($user->avatar)}}" alt="" width="150px"></center>
                                <input class="form-control border-0" type="file" name="avatar" id="">
                            </div>

                            <div class="form-group">
                                <label for="">Nombre:</label>
                                <input class="form-control border-0" type="text" name="first_name" id="" placeholder="Tu nombre..." value="{{$user->first_name}}">
                            </div>

                            <div class="form-group">
                                <label for="">Apellido:</label>
                                <input class="form-control border-0" type="text" name="last_name" id="" placeholder="Tu apellido..." value="{{$user->last_name}}">
                            </div>

                            <div class="form-group">
                                <label for="">Email:</label>
                                <input class="form-control border-0" type="email" name="email" id="" placeholder="Tu email..." value="{{$user->email}}">
                            </div>


                            <div class="dropdown-divider"></div>

                            <h4 class="text-info text-center text-"> Si lo deseas,cambia tu contraseña </h4>
                            <div class="form-group">
                                <label for="">Contraseña:</label>
                                <input class="form-control border-0" type="password" name="password" id="" placeholder="Tu contraseña...">
                            </div>

                            <div class="form-group">
                                <label for="">Repite la contraseña:</label>
                                <input class="form-control border-0" type="password" name="password_confirmation" id="" placeholder="Repite tu contraseña...">
                            </div>
                            <button class="btn btn-info btn-block" dusk="register-btn">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
