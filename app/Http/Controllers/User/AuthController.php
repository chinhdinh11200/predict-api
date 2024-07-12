<?php

namespace App\Http\Controllers\User;

use App\Exceptions\InputException;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Traits\HasRateLimiter;
use App\Http\Requests\User\Auth\ChangePasswordRequest;
use App\Http\Requests\User\Auth\ForgotPasswordRequest;
use App\Http\Requests\User\Auth\ForgotPasswordVerifyRequest;
use App\Http\Requests\User\Auth\LoginRequest;
use App\Http\Requests\User\Auth\RegisterRequest;
use App\Http\Requests\User\Auth\UpdateProfileRequest;
use App\Http\Requests\User\UploadImageRequest;
use App\Http\Resources\User\Auth\MeResource;
use App\Services\Common\FileService;
use App\Services\User\AuthService;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends BaseController
{
    use HasRateLimiter;

    public const MAX_ATTEMPTS_LOGIN = 5;
    public const DECAY_SECONDS = 60;

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware($this->authMiddleware())->except(['login', 'register', 'registerVerify', 'sendForgotPasswordRequest', 'verifyForgotPasswordRequest']);
        $this->middleware($this->guestMiddleware())->only(['login', 'register', 'registerVerify', 'sendForgotPasswordRequest', 'verifyForgotPasswordRequest']);
    }

    /**
     * Register
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $inputs = $request->only([
            'username',
            'email',
            'password',
            'refcode',
        ]);
        $data = AuthService::getInstance()->register($inputs);

        return $this->sendSuccessResponse($data, trans('auth.register_success'));
    }

    /**
     * Register
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function registerVerify(Request $request): JsonResponse
    {
        try {
            $data = AuthService::getInstance()->registerVerify($request->only(['email', 'token']));

            return $this->sendSuccessResponse($data, trans('auth.verify_success'));
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception->getMessage(), null, false);
        }
    }

    /**
     * Login
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws InputException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $ip = $request->ip();
        $inputs = $request->only(['email', 'password']);
        $key = Str::lower($inputs['email'] . '|admin_login|' . $ip);
        if ($this->tooManyAttempts($key, self::MAX_ATTEMPTS_LOGIN)) {
            return $this->sendLockoutResponse($key);
        }//end if

        $loginData = AuthService::getInstance()->login($inputs);
        if ($loginData) {
            $this->clearLoginAttempts($key);

            return $this->sendSuccessResponse($loginData);
        }//end if

        $this->incrementAttempts($key, self::DECAY_SECONDS);
        if ($this->retriesLeft($key, self::MAX_ATTEMPTS_LOGIN) == 0) {
            throw new InputException(trans('auth.throttle', ['seconds' => self::DECAY_SECONDS]));
        }//end if

        return $this->sendFailedLoginResponse();
    }

    /**
     * Send Failed Login Response
     *
     * @return JsonResponse
     */
    protected function sendFailedLoginResponse(): JsonResponse
    {
        return ResponseHelper::sendResponse(ResponseHelper::STATUS_CODE_UNAUTHORIZED, trans('auth.failed'), null);
    }

    /**
     * Current login user
     *
     * @return JsonResponse
     */
    public function currentLoginUser(): JsonResponse
    {
        $currentUser = auth()->guard()->user();

        return $this->sendSuccessResponse(new MeResource($currentUser));
    }

    /**
     * Update profile
     *
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     * @throws InputException
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $inputs = $request->only([
            'fullname',
        ]);
        $currentUser = auth()->guard()->user();

        $data = AuthService::getInstance()->withUser($currentUser)->update($inputs);

        return $this->sendSuccessResponse($data, trans('response.update_successfully'));
    }

    /**
     * Change password
     *
     * @param ChangePasswordRequest $request
     * @return JsonResponse
     * @throws InputException
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $currentUser = auth()->guard()->user();
        $inputs = $request->only(['current_password', 'password']);
        $data = AuthService::getInstance()->withUser($currentUser)->changePassword($inputs);

        return $this->sendSuccessResponse($data, trans('auth.forgot_password.success'));
    }

    /**
     * Logout
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $currentUser = auth()->guard()->user();
        $currentUser->currentAccessToken()->delete();

        return $this->sendSuccessResponse(null, trans('auth.logout_success'));
    }

    /**
     * Send forgot password request
     *
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     * @throws InputException
     */
    public function sendForgotPasswordRequest(ForgotPasswordRequest $request): JsonResponse
    {
        $inputs = $request->only(['email']);
        $data = AuthService::getInstance()->sendForgotPasswordRequest($inputs);

        return $this->sendSuccessResponse($data, trans('auth.forgot_password.send_email'));
    }

    /**
     * Verify forgot password request
     *
     * @param ForgotPasswordVerifyRequest $request
     * @return JsonResponse
     */
    public function verifyForgotPasswordRequest(ForgotPasswordVerifyRequest $request): JsonResponse
    {
        try {
            $inputs = $request->only(['email', 'token', 'password']);
            $data = AuthService::getInstance()->verifyForgotPasswordRequest($inputs);

            return $this->sendSuccessResponse($data, trans('auth.forgot_password.verify_email'));
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception->getMessage(), null, false);
        }
    }

    /**
     * Reset virtual balance to 1000.
     *
     * @return JsonResponse
     * @throws InputException
     */
    public function resetVirtualBalance(): JsonResponse
    {
        $currentUser = auth()->guard()->user();
        $data = AuthService::getInstance()->withUser($currentUser)->resetVirtualBalance();

        return $this->sendSuccessResponse($data, trans('auth.virtual_balance.success'));
    }

    /**
     * Get my balance
     *
     * @return JsonResponse
     */
    public function getMyBalance(): JsonResponse
    {
        $currentUser = auth()->guard()->user();
        $data = AuthService::getInstance()->withUser($currentUser)->getMyBalance();

        return $this->sendSuccessResponse($data);
    }

    /**
     * Upload image
     *
     * @param UploadImageRequest $request
     * @return JsonResponse
     */
    public function uploadAvatar(UploadImageRequest $request): JsonResponse
    {
        $currentUser = auth()->guard()->user();
        $image = $request->file('image');
        $type = $request->get('type');
        $data = AuthService::getInstance()->withUser($currentUser)->uploadAvatar($image, $type);

        return $this->sendSuccessResponse($data, trans('response.upload_image'));
    }

    /**
     * Get agency status, total commission
     * 
     * @param 
     * @return
     */
    public function vipMember()
    {
        $user = auth()->guard()->user();
        $data = UserService::getInstance()->withUser($user)->vipMember();

        return $this->sendSuccessResponse($data);
    }
}
