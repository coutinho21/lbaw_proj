@extends('layouts.app')

@section('content')
    <div class="container profile">
        <div class="profile-header-container">
            <div class="profile-header-image">
                <img src="{{ $user->getProfileImage() }}" alt="User profile Picture">
            </div>
            <div class='profile-header'>
                <h1>{{ $user->username }}</h1>
                <p>{{ $user->description }}</p>
                @if($user->blocked)
                    <h3 class="banned-user">Banned User</h2>
                @endif
            </div>
        </div>
            <div class="account-owner admin">
                @if(Auth::check() && !Auth::user()->blocked && (Auth::user()->id === $user->id && !Auth::user()->admin))
                    <a class="button" href="{{ route('event.create') }}">Create Event</a>
                @endif
                @if (Auth::check() && (Auth::user()->id === $user->id || Auth::user()->admin))
                    <a class="button" href="{{ url('/user/' . $user->id .'/edit')}}">Edit Profile</a>
                    <div class="fake button delete-account" id="{{$user->id}}">
                        Delete Account
                    </div>
                    @if (!$user->admin && Auth::user()->admin)
                        <div class="fake button ban-user @if($user->blocked) banned @endif" id="{{$user->id}}">
                            @if($user->blocked)
                                Unban User
                            @else
                                Ban User
                            @endif
                        </div>
                    @endif
                    @if(Auth::user()->id === $user->id && $user->admin)
                        <a href="{{ route('admin.candidates') }}" class="button check-admin-candidates">
                            Check Admin Candidates
                        </a>
                    @endif
                </div>
                @endif
        @if (AutH::check() && Auth::user()->id === $user->id)
            <div class="account-owner">
                <a class="button" href="{{ url('/logout') }}"> Logout </a>
                @if(!$user->admin)
                    @if(!$user->adminCandidate)
                        <div class="fake button request-admin" id="{{$user->id}}">
                            Request Admin Permissions
                        </div>
                    @else
                        <div class="fake button request-admin sent" id="{{$user->id}}">
                            Request Admin Permissions Sent
                        </div>
                    @endif
                @endif
            </div>
        @endif
        @if(!Auth::user()->admin)
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
        @endif
    </div>
@endsection
