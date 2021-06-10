@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">

                <h4 class="text-info text-center text-">¿Quieres ponerte en contacto con nosotros?</h4>

                <p class="font-italic">
                    Puedes ponerte en contacto con nosotros enviándonos un mensaje mediante nuestro e-mail info@proyecto.org o en el numero de telefono 928-12-65-21.
                    Llamadas de 10 a 16,fuera de ese horario también atendemos por Whatsapp.
                    ¡Estaremos encantados de atenderte!
                </p>

                <center><img src="{{asset('img/contacto.png')}}" class="img-fluid mb-3" alt="Responsive image"  style="width: 250px;"></center>


                <p class="font-italic">Síguenos en nuestras redes sociales para estar al tanto de todo y ver a los animales que llegan a nuestros brazos (las tienes en la parte inferior), tanto para conocerlos un poquito como para conocer sus progresos.
                    Quién sabe, quizás te enamores de alguno.</p>

            </div>
        </div>
    </div>
@endsection
