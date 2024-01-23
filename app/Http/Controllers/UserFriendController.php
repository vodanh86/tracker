<?php

namespace App\Http\Controllers;

use App\Models\UserFriendModel;
use App\Traits\ResponseFormattingTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserFriendController extends Controller
{
    use ResponseFormattingTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $userFriends = UserFriendModel::all();
        $total = $userFriends->count();
        $response = $this->_formatBaseResponseWithTotal(200, $userFriends, $total, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $userFriend = UserFriendModel::create($request->all());
        $response = $this->_formatBaseResponse(201, $userFriend, 'Tạo mới người thân thành công');
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $userFriend = UserFriendModel::find($id);
        $response = $this->_formatBaseResponse(200, $userFriend, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $userFriend = UserFriendModel::findOrFail($id);
        $userFriend->update($request->all());
        $response = $this->_formatBaseResponse(200, $userFriend, 'Cập nhật dữ liệu thành công');
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $userFriend = UserFriendModel::findOrFail($id);
            $userFriend->delete();
        } catch (Exception $e) {
            $response = $this->_formatBaseResponse(400, $e, "Xoá dữ liệu thất bại");
            return response()->json($response);
        }
        $response = $this->_formatBaseResponse(204, $userFriend, 'Xoá dữ liệu thành công');
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getListFriends($id)
    {
        $zone = DB::table('user_friends as uf')
            ->select('uf.id',
                'uf.nickname',
                'us1.id AS user_id',
                'us1.name',
                'us2.name AS friendName',
                'us2.phone_number')
            ->join('users as us1', 'us1.id', '=', 'uf.user_id')
            ->join('users as us2', 'us2.id', '=', 'uf.friend_id')
            ->join('share_code as sc', 'sc.user_id', '=', 'us1.id')
            ->where('uf.user_id', '=', $id)
            ->orderBy('uf.updated_at', 'DESC')
            ->get();

        $total = $zone->count();
        $response = $this->_formatBaseResponseWithTotal(200, $zone, $total, 'Lấy dữ liệu thành công');

        return response()->json($response);
    }
}
