<?php

namespace App\Http\Controllers;

use App\Models\HistoryLocationModel;
use App\Traits\ResponseFormattingTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoryLocationController extends Controller
{
    use ResponseFormattingTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $historyLocations = HistoryLocationModel::all();
        $total = $historyLocations->count();
        $response = $this->_formatBaseResponseWithTotal(200, $historyLocations, $total, 'Lấy dữ liệu thành công');
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
        $historyLocation = HistoryLocationModel::create($request->all());
        $response = $this->_formatBaseResponse(201, $historyLocation, 'Tạo mới lịch sử di chuyển thành công');
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
        $historyLocation = HistoryLocationModel::find($id);
        $response = $this->_formatBaseResponse(200, $historyLocation, 'Lấy dữ liệu thành công');
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
        $historyLocation = HistoryLocationModel::findOrFail($id);
        $historyLocation->update($request->all());
        $response = $this->_formatBaseResponse(200, $historyLocation, 'Cập nhật dữ liệu thành công');
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
            $historyLocation = HistoryLocationModel::findOrFail($id);
            $historyLocation->delete();
        } catch (Exception $e) {
            $response = $this->_formatBaseResponse(400, $e, "Xoá dữ liệu thất bại");
            return response()->json($response);
        }
        $response = $this->_formatBaseResponse(204, $historyLocation, 'Xoá dữ liệu thành công');
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getByUser($id)
    {
        $historyLocations = DB::table('history_locations AS hl')
            ->select('hl.id',
                'hl.lat',
                'hl.long',
                'hl.location_name',
                'hl.datetime',
                'us.id AS user_id',
                'us.name',
                'sc.device_info',
                'sc.imei')
            ->join('users as us', 'us.id', '=', 'hl.user_id')
            ->join('share_code as sc', 'sc.user_id', '=', 'hl.user_id')
            ->orderBy('hl.updated_at', 'DESC')
            ->get();

        $total = $historyLocations->count();
        $response = $this->_formatBaseResponseWithTotal(200, $historyLocations, $total, 'Lấy dữ liệu thành công');

        return response()->json($response);
    }
}
