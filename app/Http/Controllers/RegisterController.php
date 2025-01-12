<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\Log;
use App\Models\Register;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $registers = Register::when($request->has('device_id'), function ($query) use ($request) {
            $query->where('device_id', $request['device_id']);
        })->get();
        $registers->map(function ($register) {
            $register->Translate();
        });
        return response()->json([
            'status' => 'success',
            'message' => 'Registers fetched successfully',
            'data' => $registers,
        ], 200);
    }

    public function store(RegisterRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $register = new Register();
            $register->title = $request['title'];
            $register->device_id = $request['device_id'];
            $register->unit = $request->has('unit') ? $request['unit'] : null;
            $register->type = $request->has('type') ? $request['type'] : null;
            $register->value = $request->has('value') ? $request['value'] : null;
            $register->save();
            DB::commit();
            $register->Translate();
            return response()->json([
                'status' => 'success',
                'data' => $register,
                'message' => __('register.created')
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function show(Register $register): JsonResponse
    {
        $register->Translate();
        return response()->json([
            'status' => 'success',
            'data' => $register,
        ], 200);
    }

    public function update(Register $register, RegisterRequest $request): JsonResponse
    {
        if ($request->has('value') && $request['value'] == $register->value) {
            return response()->json([
                'status' => 'error',
                'data' => 'input value is the same as the current value',
                'message' => 'Error while updating register',
            ], 422);
        }
        DB::beginTransaction();
        try {
            $register->title = $request['title'];
            $register->device_id = $request['device_id'];
            $register->unit = $request->has('unit') ? $request['unit'] : $register->unit;
            $register->type = $request->has('type') ? $request['type'] : $register->type;
            $register->value = $request->has('value') ? $request['value'] : $request->value;
            $register->save();
            DB::commit();
            Http::post("https://bms.behinstart.ir/registers/{$register->id}/fire", [
                'token' => Hash::make('Register-' . $register->id . '-Fire'),
            ]);
            // $register->Translate();
            return response()->json([
                'status' => 'success',
                'data' => $register,
                'message' => __('register.updated'),
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function destroy(Register $register): JsonResponse
    {
        DB::beginTransaction();
        try {
            $register->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => __('register.deleted'),
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function logs(Register $register, Request $request)
    {
        $from = $request->has('from') && !is_null($request['from']) ? $request['from'] : null;
        $to = $request->has('to') && !is_null($request['to']) ? $request['to'] : null;
//        $range = !is_null($from) && !is_null($to) ? [$from, $to] : null;
        try {
            $logs = Log::where('loggable_type', Register::class)->where('loggable_id', $register->id)
//                ->when(!is_null($range), function (Builder $query) use ($range) {
//                    $query->whereBetween('created_at', $range);
//                })
                ->when(!is_null($from), function ($query) use ($from) {
                    $query->whereDate('created_at', '>=', $from);
                })->when(!is_null($to), function ($query) use ($to) {
                    $query->whereDate('created_at', '<=', $to);
                })
                ->orderBy('created_at', 'DESC')->paginate(10);
            return response()->json([
                'status' => 'success',
                'data' => $logs,
                'message' => 'Logs fetched successfully',
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'data' => $exception->getMessage(),
                'message' => 'Error while fetching logs',
            ], 500);
        }
    }
}
