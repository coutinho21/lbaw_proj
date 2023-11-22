<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    public function show(User $auth, User $user)
    {
      return $auth->id == $user->id || $auth->admin;
    }

    public function update(User $auth, User $user)
    {
        return $auth->id == $user->id || $auth->admin;
    }
}
