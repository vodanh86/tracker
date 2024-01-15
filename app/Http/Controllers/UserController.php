<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdminUserResourceCollection;
use App\Models\AdminUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(AdminUser $user)
    {
        return (new AdminUserResourceCollection($user))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdminUser $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }


    public function find(Request $request)
    {
        $branchId = $request->get('user_id');
        return User::where('id', $branchId)->get();
    }

    public function getById(Request $request)
    {
        $id = $request->get('q');
        return User::find($id);
    }

    public function getOtherUsers(Request $request)
    {
        $currentUser = $request->get('user_id');
        $otherUser = DB::table('users as us')
            ->select('us.id','us.name','us.email','us.email_verified_at','us.created_at','us.updated_at')
            ->where('us.id','!=',$currentUser)
            ->orderBy('us.updated_at', 'DESC')
            ->get();
        return response()->json($otherUser);
    }

    public function getRemainUsers(Request $request)
    {
        $id = $request->get('user_id');
        return  User::where('id', $id)->get();
    }
}
