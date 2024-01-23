<?php

namespace App\Http\Controllers;

use App\Models\ZoneModel;
use App\Traits\ResponseFormattingTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZoneController extends Controller
{
    use ResponseFormattingTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $zones = ZoneModel::all();
        $total = $zones->count();
        $response = $this->_formatBaseResponseWithTotal(200, $zones, $total, 'Lấy dữ liệu thành công');
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
        $zone = ZoneModel::create($request->all());
        $response = $this->_formatBaseResponse(201, $zone, 'Tạo mới zone thành công');
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
        $zone = ZoneModel::find($id);
        $response = $this->_formatBaseResponse(200, $zone, 'Lấy dữ liệu thành công');
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
        $zone = ZoneModel::findOrFail($id);
        $zone->update($request->all());
        $response = $this->_formatBaseResponse(200, $zone, 'Cập nhật dữ liệu thành công');
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
            $zone = ZoneModel::findOrFail($id);
            $zone->delete();
        } catch (Exception $e) {
            $response = $this->_formatBaseResponse(400, $e, "Xoá dữ liệu thất bại");
            return response()->json($response);
        }
        $response = $this->_formatBaseResponse(204, $zone, 'Xoá dữ liệu thành công');
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllZones()
    {
        $zone = DB::table('zones AS zo')
            ->select('zo.id',
                'zo.lat',
                'zo.long',
                'zo.status',
                'zo.alert',
                'zo.name',
                'us1.name as `userName`',
                'us2.name as `friendName`')
            ->join('users as us1', 'us.id', '=', 'zo.user_id')
            ->join('users as us2', 'us2.id', '=', 'zo.friend_id')
            ->orderBy('zo.updated_at', 'DESC')
            ->get();

        $total = $zone->count();
        $response = $this->_formatBaseResponseWithTotal(200, $zone, $total, 'Lấy dữ liệu thành công');

        return response()->json($response);
    }
}
