@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <h4 class="text-info text-center text-"> ¿Quienes somos?</h4>
                <p class="font-italic">A veces sentimos que lo que hacemos es solo una gota en un inmenso mar. Tenemos muchos proyectos para ayudar a nuestros animales sin hogar, no contamos con ningún tipo de ayuda gubernamental, nuestros ingresos provienen única y exclusivamente de donaciones.</p>
                <p class="font-italic">La creación de esta pagina web ha sido en colaboracion con un grupo de voluntarios con distintas trayectorias animalistas, así como distinta procedencia pero que actúan juntos en la isla de Gran Canaria con el fin de ayudar al mayor número de animales posible.</p>
                <p class="font-italic font-weight-bold">Solo hay unas simples reglas que cumplir,la tematica de los mensajes deben ser en post de mejorar la vida de los animales de albergues,callejeros u otros que esten en situacion complicada o de abandono.</p>
                    <img src="{{asset('img/pets.jpg')}}" class="img-fluid " alt="Responsive image" >
            </div>
        </div>
    </div>
@endsection
