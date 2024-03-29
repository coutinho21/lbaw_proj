<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function show(string $id)
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
            if ($user->blocked) {
                return redirect()->route('home');
            }
        }
        $user = User::findOrFail($id);
        $this->authorize('show', $user);

        return view('pages.users.show', [
            'user' => $user
        ]);
    }

    public function edit(string $id)
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
            if ($user->blocked) {
                return redirect()->route('home');
            }
        }
        $user = User::findOrFail($id);

        $this->authorize('edit', $user);

        return view('pages.users.edit', [
            'user' => $user
        ]);
    }


    public function update(Request $request, string $id)
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
            if ($user->blocked) {
                return redirect()->route('home');
            }
        }
        $user = User::findOrFail($id);

        $this->authorize('update', $user);

        $request->validate([
            'email' => 'required|email|max:250|unique:users,email,' . $id,
            'username' => 'required|string|max:250|unique:users,username,' . $id,
            'name' => 'required|string|max:250|unique:users,name,' . $id,
            'description' => 'string|max:2000',
            'password' => 'nullable|min:8|confirmed',
        ], [
            'email.unique' => 'This email is already in use.',
            'username.unique' => 'This username is already in use.',
            'name.unique' => 'This name is already in use.',

        ]);

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->email = $request->email;
        $user->username = $request->username;
        $user->name = $request->name;
        $user->description = $request->description;

        $user->save();

        return redirect()->route('user.show', ['id' => $user->id])
            ->withSuccess('You have successfully edited your profile!');
    }

    public function delete(string $id)
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
            if ($user->blocked) {
                return redirect()->route('home');
            }
        }
        $user = User::findOrFail($id);

        $this->authorize('delete', $user);

        $user->delete();

        return response()->json(['message' => 'Delete successful'], 200);
    }

    public function manageEvent(Request $request, string $id_event)
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
            if ($user->blocked) {
                return redirect()->route('home');
            }
        }
        $user = User::find(Auth::user()->id);
        $event = Event::findOrFail($id_event);

        if ($request->events == 'created') {
            if ($request->actionName == 'pin') {
                $pinAction = filter_var($request->input('pinAction'), FILTER_VALIDATE_BOOLEAN);
                $event->update([
                    'highlight_owner' => $pinAction,
                    'hide_owner' => false
                ]);
            } else if ($request->actionName == 'hide') {
                $hideAction = filter_var($request->input('hideAction'), FILTER_VALIDATE_BOOLEAN);
                $event->update([
                    'highlight_owner' => false,
                    'hide_owner' => $hideAction,
                ]);
            }
        } else if ($request->events == 'joined') {
            if ($request->actionName == 'pin') {
                $pinAction = filter_var($request->input('pinAction'), FILTER_VALIDATE_BOOLEAN);
                $user->events()->updateExistingPivot($id_event, [
                    'highlighted' => $pinAction,
                    'hidden' => false
                ]);
            } else if ($request->actionName == 'hide') {
                $hideAction = filter_var($request->input('hideAction'), FILTER_VALIDATE_BOOLEAN);
                $user->events()->updateExistingPivot($id_event, [
                    'highlighted' => false,
                    'hidden' => $hideAction,
                ]);
            }
        }

        return response()->json(['message' => 'Update successful'], 200);
    }

    public function toggleBan(string $id)
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
            if ($user->blocked) {
                return redirect()->route('home');
            }
        }
        $user = User::find($id);
        $this->authorize('toggleBan', $user);
        $user->blocked = !$user->blocked;
        $user->save();

        return response()->json(['message' => 'Ban toggled'], 200);
    }

    public function requestAdmin(string $id)
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
            if ($user->blocked) {
                return redirect()->route('home');
            }
        }
        $user = User::find($id);
        $this->authorize('requestAdmin', $user);
        $user->adminCandidate = true;
        $user->save();
        return response()->json(['message' => 'Admin Permissions Requested'], 200);
    }

    public function cancelRequestAdmin(string $id)
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
            if ($user->blocked) {
                return redirect()->route('home');
            }
        }
        $user = User::find($id);
        $user->adminCandidate = false;
        $user->save();
        return response()->json(['message' => 'Admin Permissions Request Canceled'], 200);
    }

    public function adminCandidates()
    {

        $user = User::find(Auth::user()->id);
        $this->authorize('adminCandidates', $user);

        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
            if ($user->blocked) {
                return redirect()->route('home');
            }   
        }
        $users = User::where('adminCandidate', true)->get();
        return view('pages.admin.candidates', ['users' => $users]);
    }

    public function acceptAdmin(string $id)
    {
        $user = User::find($id);
        $this->authorize('respondAdminRequest', $user);
        $user->adminCandidate = false;
        $user->admin = true;
        $user->events()->detach();
        $user->pollOptions()->detach();
        $user->save();

        DB::table('event')->where('id_owner', $user->id)->update(['id_owner' => DB::raw('NULL')]);
        DB::table('comment')->where('id_user', $user->id)->update(['id_user' => DB::raw('NULL')]);
        DB::table('poll')->where('id_user', $user->id)->update(['id_user' => DB::raw('NULL')]);
        DB::table('likes_dislikes')->where('id_user', $user->id)->update(['id_user' => DB::raw('NULL')]);

        $notificationsIds = DB::table('event_notification')->where('id_user', $user->id)->pluck('id');
        if($notificationsIds->count() > 0){
            DB::table('invite')->whereIn('id_eventnotification', $notificationsIds)->delete();
            DB::table('request_to_join')->where('id_eventnotification', $notificationsIds)->delete();
            DB::table('event_update')->where('id_eventnotification', $notificationsIds)->delete();
            DB::table('event_notification')->where('id_user', $user->id)->delete();
        }

        $invitesIds = DB::table('invite')->where('id_user', $user->id)->pluck('id_eventnotification');
        if($invitesIds->count() > 0){
            DB::table('invite')->where('id_user', $user->id)->delete();
            DB::table('event_notification')->whereIn('id', $invitesIds)->delete();
        }

        $requestsToJoin = DB::table('request_to_join')->where('id_user', $user->id)->pluck('id_eventnotification');
        if($requestsToJoin->count() > 0){
            DB::table('request_to_join')->where('id_user', $user->id)->delete();
            DB::table('event_notification')->whereIn('id', $requestsToJoin)->delete();
        }

        return response()->json(['message' => 'Request admin permissions has been accepted'], 200);
    }

    public function refuseAdmin(string $id)
    {
        $user = User::find($id);
        $this->authorize('respondAdminRequest', $user );
        $user->adminCandidate = false;
        $user->save();

        return response()->json(['message' => 'Request admin permissions has been refused'], 200);
    }
}
