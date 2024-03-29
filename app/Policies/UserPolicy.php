<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    public function show()
    {
        return Auth::check();
    }

    public function edit(User $auth, User $user)
    {
        return ($auth->id == $user->id && !$user->blocked) || $auth->admin;
    }

    public function update(User $auth, User $user)
    {
        return ($auth->id == $user->id && !$user->blocked) || $auth->admin;
    }


    public function deleteProfilePicture(User $auth, User $user)
    {
        if (!Auth::check()) {
            return false;
        } else if (($auth->id == $user->id && !$user->blocked) || $auth->admin) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteEventPicture(User $auth, User $user)
    {   
        if (!Auth::check()) {
            return false;
        } else if (($auth->id == $user->id && !$user->blocked) || $auth->admin) {
            return true;
        } else {
            return false;
        }
    }

    public function delete(User $auth, User $user)
    {
        return $auth->id == $user->id || $auth->admin;
    }

    public function toggleBan(User $auth, User $user)
    {
        return ($auth->admin && !$user->admin);
    }

    public function requestAdmin(User $auth, User $user)
    {
        return ($auth->id == $user->id && !$user->adminCandidate && !$user->blocked);
    }

    public function adminCandidates(User $auth, User $user)
    {
        return $user->admin;
    }

    public function respondAdminRequest(User $auth, User $user)
    {
        return $auth->admin && !$user->blocked;
    }
}
