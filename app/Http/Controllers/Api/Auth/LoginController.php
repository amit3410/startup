<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Inv\Repositories\Contracts\UserInterface as InvUserRepoInterface;

class LoginController extends Controller
{
     /**
     * User repository
     *
     * @var object
     */
    protected $userRepo;
   /**
     * @var bool
     */
    public $loginAfterSignUp = true;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(InvUserRepoInterface $user) {
        $this->userRepo = $user;
    }
    
    public function login(Request $request)
    {

        $data = $request->all();
        $username = $data['username'];
        $password = $data['password'];
      
        $input = $this->userRepo->apiLogin($username, $password);
        $token = null;
        $input = array('email'=>'amit.suman@prolitus.com','password' => '123456');

        if (!$token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }
}
