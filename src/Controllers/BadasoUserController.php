<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Helpers\CheckBase64;
use Uasoft\Badaso\Models\User;
use Uasoft\Badaso\Traits\FileHandler;

class BadasoUserController extends Controller
{
    use FileHandler;

    public function browse(Request $request)
    {
        try {
            $data = User::all();

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function read(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:users,id',
            ]);

            $data = User::find($request->id);

            return ApiResponse::success($data);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function edit(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'id' => [
                    'required',
                    'exists:users,id',
                ],
                'email' => "required|email|unique:users,email,{$request->id}",
                'name' => 'required',
                'avatar' => [
                    function ($attribute, $value, $fail) {
                        $check = new CheckBase64($value);
                        if (!$check->isValid()) {
                            $fail($check->getMessage());
                        }
                    },
                ],
            ]);

            $user = User::find($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $this->handleDeleteFile($user->avatar);
            $uploaded = null;
            if ($request->avatar && $request->avatar != '') {
                $extension = explode('/', explode(';', $request->avatar)[0])[1];
                $files = [];
                $files[] = [
                    'base64' => $request->avatar,
                    'name' => Str::slug($request->name).'.'.$extension,
                ];
                $uploaded = $this->handleUploadFiles($files);
                if (count($uploaded) > 0) {
                    $uploaded = $uploaded[0];
                }
            }
            $user->avatar = $uploaded;
            $user->additional_info = $request->addition_info;
            $user->password = Hash::make($request->password);
            $user->save();

            DB::commit();

            return ApiResponse::success($user);
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function add(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'email' => 'required|email|unique:users',
                'name' => 'required',
                'avatar' => [
                    function ($attribute, $value, $fail) {
                        $check = new CheckBase64($value);
                        if ($value != '' && !$check->isValid()) {
                            $fail($check->getMessage());
                        }
                    },
                ],
            ]);

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $uploaded = null;
            if ($request->avatar && $request->avatar != '') {
                $extension = explode('/', explode(';', $request->avatar)[0])[1];
                $files = [];
                $files[] = [
                    'base64' => $request->avatar,
                    'name' => Str::slug($request->name).'.'.$extension,
                ];
                $uploaded = $this->handleUploadFiles($files);
                if (count($uploaded) > 0) {
                    $uploaded = $uploaded[0];
                }
            }
            $user->avatar = $uploaded;
            $user->additional_info = $request->addition_info;
            $user->password = Hash::make($request->password);
            $user->save();

            DB::commit();

            return ApiResponse::success($user);
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'id' => [
                    'required',
                    'exists:users',
                ],
            ]);

            User::find($request->id)->delete();

            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function deleteMultiple(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'ids' => [
                    'required',
                ],
            ]);

            $id_list = explode(',', $request->ids);

            foreach ($id_list as $key => $id) {
                User::find($id)->delete();
            }

            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }
}
