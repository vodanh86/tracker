<?php

namespace App\Http\Controllers;

use App\Models\ShareCodeModel;
use App\Traits\ResponseFormattingTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShareCodeController extends Controller
{
    use ResponseFormattingTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $zones = ShareCodeModel::all();
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
        $code = Str::uuid();

        $data = $request->all();
        $data['code'] = $code;

        $shareCodes = ShareCodeModel::create($data);
//        $shareCodes = ShareCodeModel::create($request->all());
        $response = $this->_formatBaseResponse(201, $shareCodes, 'Tạo mới mã code thành công');
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
        $shareCode = ShareCodeModel::find($id);
        $response = $this->_formatBaseResponse(200, $shareCode, 'Lấy dữ liệu thành công');
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
        $shareCode = ShareCodeModel::findOrFail($id);
        $shareCode->update($request->all());
        $response = $this->_formatBaseResponse(200, $shareCode, 'Cập nhật dữ liệu thành công');
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
            $shareCode = ShareCodeModel::findOrFail($id);
            $shareCode->delete();
        } catch (Exception $e) {
            $response = $this->_formatBaseResponse(400, $e, "Xoá dữ liệu thất bại");
            return response()->json($response);
        }
        $response = $this->_formatBaseResponse(204, $shareCode, 'Xoá dữ liệu thành công');
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByUser($id)
    {
        $shareCode = DB::table('share_code as sc')
            ->select('sc.id',
                'sc.code',
                'sc.device_info',
                'sc.imei',
                'sc.status',
                'us1.id as user_id',
                'us1.name')
            ->join('users as us1', 'us1.id', '=', 'sc.user_id')
            ->where('sc.user_id', '=', $id)
            ->orderBy('sc.updated_at', 'DESC')
            ->get();

        $total = $shareCode->count();
        $response = $this->_formatBaseResponseWithTotal(200, $shareCode, $total, 'Lấy dữ liệu thành công');

        return response()->json($response);
    }

}
