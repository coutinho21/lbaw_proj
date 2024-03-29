@extends('layouts.app')

@section('content')
<div class="container">
    <h1 style="text-align: center">Change your Password</h1>
    <form class="general" method="POST" action="{{ route('password.recover', ['token' => request()->route('token')]) }}">
        @csrf

        @if(session('success'))
            <p class="success">
                {{ session('success') }}
            </p>
        @endif
        <input type="hidden" name="token" value="{{ $token }}" hidden>
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>

        <label for="confirm-password">Confirm Password</label>
        <input id="confirm-password" type="password" name="password_confirmation" required>
        @if ($errors->has('error'))
            <span class="error">
                {{ $errors->first('error') }}
            </span>
        @endif
        <button type="submit">Change Password</button>
    </form>
</div>
@endsection
