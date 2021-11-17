<?php

namespace Uasoft\Badaso\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Models\User;
use Uasoft\Badaso\Traits\FileHandler;

class BadasoUserController extends Controller
{
    use FileHandler;

    public function browse(Request $request)
    {
        try {
            $users = User::all();

            $data['users'] = $users;

            return ApiResponse::success(collect($data)->toArray());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function read(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:Uasoft\Badaso\Models\User,id',
            ]);

            $user = User::find($request->id);

            $user->email_verified = ! is_null($user->email_verified_at);

            $data['user'] = $user;

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
                'id'        => 'required|exists:Uasoft\Badaso\Models\User,id',
                'email'     => "required|email|unique:Uasoft\Badaso\Models\User,email,{$request->id}",
                'username'  => "required|string|max:255|alpha_num|unique:Uasoft\Badaso\Models\User,username,{$request->id}",
                'name'      => 'required',
                'avatar'    => 'nullable',
            ]);

            $user = User::find($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->avatar = $request->avatar;
            $user->additional_info = $request->additional_info;
            if ($request->password && $request->password != '') {
                $user->password = Hash::make($request->password);
            }
            if ($request->email_verified) {
                $user->email_verified_at = date('Y-m-d H:i:s');
            }

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
                'email'     => 'required|email|unique:Uasoft\Badaso\Models\User',
                'name'      => 'required|string|max:255',
                'username'  => 'required|string|max:255|alpha_num|unique:Uasoft\Badaso\Models\User,username',
                'avatar'    => 'nullable',
            ]);

            $user = new User();
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->avatar = $request->avatar;
            $user->additional_info = $request->additional_info;
            $user->password = Hash::make($request->password);
            if ($request->email_verified) {
                $user->email_verified_at = date('Y-m-d H:i:s');
            }
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
                    'exists:Uasoft\Badaso\Models\User',
                ],
            ]);

            $user = User::find($request->id);
            $this->handleDeleteFile($user->avatar);
            $user->delete();

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
                $user = User::find($id);
                $this->handleDeleteFile($user->avatar);
                $user->delete();
            }

            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }
}
