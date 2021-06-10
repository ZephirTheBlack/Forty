<nav class="navbar navbar-expand-lg navbar-light navbar-forty">
    <div class="container">
        <a class="navbar-brand" href="{{route('home')}}"><img src="{{asset('img/forty3.png')}}" class="img-responsive " alt="Responsive image" style="width: 80px;"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="{{route('home')}}">Inicio <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('who')}}">Quienes Somos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('contact')}}">Contacto</a>
                </li>

            </ul>

            <ul class="navbar-nav ml-auto">
                @guest
                    <li class="nav-item list-inline-item" ><a href="{{route('register')}}" type="button" class="nav-link btn btn-info btn-rounded text-white " style="margin-bottom: 0.1em;"><strong>Registrarse</strong></a></li>
                    <li class="nav-item list-inline-item" ><a href="{{route('login')}}"type="button"  class="nav-link btn btn-info btn-rounded text-white "><strong> Login</strong></a></li>
                @else
                    <div class="container-fluid">
                        <div class="row ">
                            <div class="mr-1"><li class="nav-item"><a href="{{route('friends.index')}}" class="nav-link"><i class="fas fa-user-friends text-info "></i></a></li></div>
                            <div class="mr-1"><li class="nav-item"><a href="{{route('accept-friendships.index')}}" class="nav-link"><i class="fas fa-address-card text-info"></i></a></li></div>
                            <div class="mr-1"><notification-list><i class="fas fa-bell"></i></notification-list></div>
                        </div>
                    </div>



                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-info " href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><strong>
                                {{Auth::user()->name}}
                            </strong>

                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{route('users.show', Auth::user())}}">Tu Perfil</a>
                            <a class="dropdown-item" href="{{route('users.edit', Auth::user())}}">Opciones</a>
                            <div class="dropdown-divider"></div>
                            <a onclick="document.getElementById('logout').submit()" class="dropdown-item" href="#">Cerrar sesión</a>
                        </div>
                    </li>
                    <form action="{{route('logout')}}" id="logout" method="post">@csrf</form>
                @endguest
            </ul>
        </div>
    </div>
</nav>
