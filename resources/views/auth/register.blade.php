@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                @include('partials.validation-errors')
                <div class="card border-0 bg-light px-4 py-2">

                    <form action="{{route('register')}}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                    <label for="">Nombre de usuario:</label>
                                    <input class="form-control border-0" type="text" name="name" id="" placeholder="Tu nombre de usuario..." value="{{old('name')}}">
                            </div>

                            <div class="form-group">
                                <label for="">Nombre:</label>
                                <input class="form-control border-0" type="text" name="first_name" id="" placeholder="Tu nombre..." value="{{old('first_name')}}">
                            </div>

                            <div class="form-group">
                                <label for="">Apellido:</label>
                                <input class="form-control border-0" type="text" name="last_name" id="" placeholder="Tu apellido..." value="{{old('last_name')}}">
                            </div>

                            <div class="form-group">
                                <label for="">Email:</label>
                                <input class="form-control border-0" type="email" name="email" id="" placeholder="Tu email..." value="{{old('email')}}">
                            </div>

                            <div class="form-group">
                                <label for="">Contraseña:</label>
                                <input class="form-control border-0" type="password" name="password" id="" placeholder="Tu contraseña...">
                            </div>

                            <div class="form-group">
                                <label for="">Repite la contraseña:</label>
                                <input class="form-control border-0" type="password" name="password_confirmation" id="" placeholder="Repite tu contraseña...">
                            </div>

                            <button class="btn btn-info btn-block" dusk="register-btn">Registrate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
