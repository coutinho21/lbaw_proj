<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EventPolicy
{

    
    public function update(User $user, Event $event): bool
    {
        if(Auth::check()){
            if($event->id_owner === $user->id) return true;
        }
        return false;
    }

    public function edit(User $user, Event $event): bool
    {
        if(Auth::check()){
            if($event->id_owner === $user->id) return true;
        }
        return false;
    }

    public function participants(User $user, Event $event): bool
    {
        if(Auth::check()){
            if($event->id_owner === $user->id) return true;
        }
        return false;
    }

    public function removeparticipant(User $user, Event $event): bool
    {
        if(Auth::check()){
            if($event->id_owner === $user->id) return true;
        }
        return false;
    }


    public function delete(User $user, Event $event): bool
    {
        if($event->id_owner == $user->id) return true;
        return false;
    }



    public function join(User $user, Event $event): bool
    {
        if(Auth::check()){
            if($event->id_owner !== $user->id) return true;
        }
        return false;
    }
}
