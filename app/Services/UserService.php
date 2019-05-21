<?php
/**
 * Created by PhpStorm.
 * User: snusnumr
 * Date: 16.05.19
 * Time: 22:53
 */

namespace App\Services;


use App\Http\Resources\User\UserCollection;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class UserService
{
    public $response;

    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    public function store($request)
    {
        try {
            $users = User::paginate(20);
            return response()->json([
                'users' => new UserCollection($users)
            ], 200);
        } catch (\Exception $error) {
            $this->response->showError($error->getMessage());
        }
    }

    public function create($request)
    {
        try {
            $request->validated();
            User::create($request->input());
            $this->response->showSuccess('Пользователь успешно добавлен');
        } catch(\Exception $error) {
            return $this->response($error->getMessage());
        }
    }

    public function info($id)
    {
        try {
            $user = User::findOrFail($id); /** Добавить ОRM на emails ** */
            return response()->json([
                'user_info' => new User($user)
            ], 200);
        } catch (\Exception $error) {
            return $this->response->showError($error->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'user' => new User($user)
            ], 200);
        } catch (\Exception $error) {
            return $this->response->showError($error->getMessage());
        }
    }

    public function update($request, $id)
    {
        try {
            $request->validated();
            $user = User::findOrFail($id);
            $user->fill($request->input());
            $user->save();
            $this->response->showSuccess('Пользователь успешно обновлён');
        } catch (\Exception $error) {
            return $this->response($error->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::fildOrFaiL($id);
            $user->delete();
            $this->response->showSuccess('Пользователь успешно удалён');
        } catch (\Exception $error) {
            return $this->response($error->getMessage());
        }
    }

    public function login($request)
    {
        $request->validate([
            'email' => 'required|email|min:5|max:255',
            'password' => 'required|min:8|max:60'
        ]);

        $credentials = $request->only('email', 'password');

        try {
            Auth::attempt($credentials, true);

            return response()->json([
                'user' => auth()->user(),
                'csrf_token' => csrf_token()
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }
}