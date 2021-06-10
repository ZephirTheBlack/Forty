@extends('layouts.app')

@section('content')
    <div class="container">
        <h5 class="text-info text-center font-weight-bold">Solicitudes de amistad</h5>
    @foreach($friendshipRequest as $friendshipRequest)
        <accept-friendship-btn
            :sender="{{$friendshipRequest->sender}}"
            friendship-status="{{$friendshipRequest->status}}"
        ></accept-friendship-btn>
    @endforeach
    </div>
@endsection
