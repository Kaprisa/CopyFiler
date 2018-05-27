<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function addCourse(Request $request)
    {
        Auth::guard('api')
            ->user()
            ->courses()
            ->attach($request->id);

        return null;
    }

    public function removeCourse($id)
    {
        Auth::guard('api')
            ->user()
            ->courses()
            ->detach($id);

        return null;
    }

    public function get()
    {
        return response()->json(Auth::guard('api')->user(), 200);
    }

    public function getAll() {
        $users = User::all();
        return response()->json($users, 200);
    }

    public function avatar(Request $request)
    {
        if (!$request->hasFile('avatar')) return 'avatar.png';
        $user = Auth::guard('api')->user();
        if ($user->avatar !== 'avatar.png') Storage::disk('uploads')->delete('users/'.$request->avatar);
        $user->avatar = str_replace('users/', '', Storage::disk('uploads')->put('users', $request->avatar));
        $user->save();
        return $user->avatar;
    }

    public function profile(Request $request)
    {
        $user = Auth::guard('api')->user();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->save();
        return null;
    }

    public function test(Request $request) {
        $user = Auth::guard('api')->user();
        if (!$user->lessons->contains($request->lesson)) $user->lessons()->attach($request->lesson);
        $user->lessons()->updateExistingPivot($request->lesson, [
            'percent' => $request->percent
        ]);
        return $user->lessons;
    }
}
