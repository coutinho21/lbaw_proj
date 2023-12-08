@extends('layouts.app')

@section('content')
    <div class="container profile">
        <div class="profile-header-container">
            <div class="profile-header">
                <img src="{{ $user->getProfileImage() }}">
            </div>
            <div class='profile-header'>
                <h1>{{ $user->username }}</h1>
                <p>{{ $user->description }}</p>
            </div>
        </div>
        <div class="notifications">
            <h2>Invites</h2>
            <div class="invites">
                @if($notifications[0]->count() == 0)
                    <h4>No invites</h4>
                @endif
                @foreach($notifications[0] as $invite)
                    <a class="pending_invite" href="{{ url($invite->link) . '?id_invite=' . $invite->id}}">
                        <h4>- {{$invite->text}}</h4>
                    </a>
                @endforeach
            </div>
            {{-- <h2>Event Updates</h2>
            <div class="event-updates">
                @if($notifications[1]->count() == 0)
                    <h4>No Event Updates</h4>
                @endif
                @foreach($notifcations[1] as $eventUpdate)
                <a class="pending_event_update" href="{{ url($eventUpdate->link) . '?id_eventUpdate=' . $eventUpdate->id}}">
                    <h4>- {{$eventUpdate->text}}</h4>
                </a>
                @endforeach
            </div>
            <h2>Requests To Join Your Events</h2>
            <div class="requests-to-join">
                @if($notifications[2]->count() == 0)
                    <h4>No Requests To Join</h4>
                @endif
                @foreach($notifcations[2] as $requestToJoin)
                <a class="pending_request_to_join" href="{{ url($requestToJoin->link) . '?id_requestToJoin=' . $requestToJoin->id}}">
                    <h4>- {{$requestToJoin->text}}</h4>
                </a>
                @endforeach
            </div> --}}
        </div>
        @if (Auth::check() && (Auth::user()->id === $user->id || Auth::user()->admin))
            <div class="account-owner admin">
                <a class="button" href="{{ route('event.create') }}">Create Event</a>
                <a class="button" href="{{ url('/user/' . $user->id .'/edit')}}">Edit Profile</a>
                <div class="fake button delete-account" id="{{$user->id}}">
                    Delete Account
                </div>
            </div>
        @endif
        @if (AutH::check() && Auth::user()->id === $user->id)
            <div class="account-owner">
                <a class="button" href="{{ url('/logout') }}"> Logout </a>
            </div>
        @endif

        <div class="profile-events-title-div">
            <h2 class="joined-events-title active">Joined Events</h2>
            <h2 class="created-events-title">Created Events</h2>
        </div>
        <div class="joined-events-container" style="display: flex">
            @each('partials.joined_event_card', $user->events, 'event')
        </div>
        <div class="created-events-container" style="display: none">
            @each('partials.created_event_card', $user->ownedEvents, 'event')
        </div>
    </div>
@endsection
