<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function userPosts(Request $request){
        $id = $request->query("authId");
        $user = User::query()->find($id);
        return $user->posts()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    /*
     * Ajax Search
     */
    public function search(Request $request){
        $query = $request->get('query');
        $id = $request->query("authId");
        $user = User::query()->find($id);
        return $user->posts()
            ->with('user')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                    ->orWhere('body', 'like', '%' . $query . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
