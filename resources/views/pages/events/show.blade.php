@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $event->name }}</h1>
        @if(Auth::check())
        <p>Event Creator: <a href="{{ route('user.show', ['id' => $event->owner->id]) }}"> {{ $event->owner->name }}</a></p>
        @else
        <p>Event Creator: <a href="{{ route('login') }}"> {{ $event->owner->name }}</a></p>
        @endif
        <p>Event date: {{ $event->eventdate }}</p>
        <p>Capacity: {{$event->participants->count()}}/{{$event->capacity}}</p>
        @if ($event->price == 0)
            <p>Free Event</p>
        @else
        <p>Price: {{ $event->price }} €</p>
        @endif
        <p>Description: {{ $event->description }}</p>
        <p>Location: {{ $event->location->address }}</p>
        @if ($event->opentojoin && Auth::check() && Auth::user()->id != $event->id_owner && !Auth::user()->events->contains($event))
            <form action="{{ route('event.join', ['id' => $event->id]) }}" method="POST">
                @csrf
                <button class="button" type="submit">
                    Join Event
                </button>
            </form>
        @elseif(!$event->opentojoin && Auth::check() && Auth::user()->id != $event->id_owner && !Auth::user()->events->contains($event))
            <form action="" method="POST">
                @csrf
                <button class="button" type="submit">
                    Request to join
                </button>
            </form>
        @endif
        @if (Auth::check() && Auth::user()->id == $event->id_owner)
            <a class="button" href="{{ route('event.edit', ['id' => $event->id]) }}">
                Edit Event
            </a>
        @endif
        
        <div class="comments">
            <h3>Comments</h3>
            <ul class="comment-list">
                @each('partials.comment', $event->comments, 'comment')
            </ul>
        </div>
    </div>
@endsection
