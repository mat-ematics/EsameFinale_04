<?php

namespace App\Http\Controllers\api\v1;

use App\Helpers\AppHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User\User;
use App\Services\Authentication\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthController extends Controller
{

    protected AuthService $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    /**
     * Crea e salva in database un nuovo utente
     */
    public function register(RegisterRequest $request)
    {
        //Validzione dei Dati in input
        $data = $request->validated();
        try {
            $user = $this->authService->registerUser($data);
            $tokenArr = $this->authService->loginUser($data['username'], $data['password'], $request->ip());

            return AppHelpers::jsonResponse($tokenArr['token'], 201);
        } catch (\Throwable $th) {
            return AppHelpers::jsonResponse($th->getMessage(), AppHelpers::safeHttpStatus($th));
        }
    }

    /**
     * Funzione di Login
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        try {
            $tokenArr = $this->authService->loginUser($data['username'], $data['password'], $request->ip());

            return AppHelpers::jsonResponse($tokenArr['token'], 200);
        } catch (\Throwable $th) {
            return AppHelpers::jsonResponse($th->getMessage(), AppHelpers::safeHttpStatus($th));
        }
    }

    public function logout()
    {
        $this->authService->endUserSession(Auth::user()->id);

        return AppHelpers::jsonResponse('Logged out successfully', 204);
    }

    public function forceLogout(string $userId)
    {
        if (Gate::allows('isAdmin')) {

            try {
                $user = User::getUser($userId, true);

                if (!$user) {
                    throw new HttpException(404, 'User Not Found');
                }

                if (!$user->session()->exists()) {
                    throw new HttpException(404, 'User Is not Logged In');
                }

                $this->authService->endUserSession($user->id);
                return AppHelpers::jsonResponse("User {$user->username} Session has been Terminated", 204);
            } catch (\Throwable $th) {
                return AppHelpers::jsonResponse($th->getMessage(), AppHelpers::safeHttpStatus($th));
            }
        }

        return  AppHelpers::jsonResponseForbidden();
    }
}