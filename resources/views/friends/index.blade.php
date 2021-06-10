@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @forelse($friends as $friend)
                <div class="col-md-3">
                    @include('partials.user', ['user' => $friend])
                </div>
            @empty
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 mx-auto">
                        <div class="font-italic ">Siento ser yo quien te lo diga,pero no tienes amigos todavía.</div>
                            <img src="{{asset('img/kisspng-desktop-wallpaper-sorry-smiley-sorry-5ad70bb44f0a31.8298399215240426763238.png')}}" alt="Sorry" class="img-fluid">
                    </div>
                </div>

            @endforelse
        </div>
    </div>
@endsection
