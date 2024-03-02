<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * @OA\Post(
     *     path="/auth/in",
     *     tags={"Authorized"},
     *     summary="Get user token for access API",
     *     description="",
     *     operationId="login",
     *     @OA\Parameter(
     *         name="email",
     *         required=true,
     *         in="query",
     *         description="The user name for login",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *         description="The password for login in clear text",
     *     ),
     *     @OA\Response(response="200", description="show token")
     * )
     */
    public function login(Request $r){
        $this->validate($r, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $r->only(['email', 'password']);

        try {
            // validate user exists or not
            if(!User::exists($credentials)) throw new \Exception("User aren't found", 401);

            if (! $token = Auth::attempt($credentials)) throw new \Exception('Unauthorized', 401);

            $this->return = array_merge($this->return,[
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'user' => auth()->user()->only(['id', 'name', 'email', 'updated_at']),
                    'expires_in' => auth()->factory()->getTTL() * 60 * 24 * 7
                ]
            ]);
        } catch(\Exception $e){
            $this->return = array_merge($this->return,[
                'status' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
        }

        return response()->json($this->return, $this->return['code']);
    }


    /**
     * @OA\Post(
     *     path="/auth/reg",
     *     tags={"Authorized"},
     *     summary="Register user for access API",
     *     description="",
     *     operationId="register",
     *     @OA\Parameter(
     *         name="email",
     *         required=true,
     *         in="query",
     *         description="The email user for login",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *         description="The password for login in clear text",
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *         description="Fullname of user",
     *     ),
     *     @OA\Response(response="200", description="Registered and get id")
     * )
     */
    public function register(Request $r){
        $this->validate($r, [
            'email' => 'required|string',
            'password' => 'required|string|min:8',
            'name' => 'required|string',
        ]);

        $credentials = $r->only(['email', 'password']);

        try {
            // validate user exists or not
            if(User::exists($credentials)) throw new \Exception("User are exists", 422);

            $data = (new User)->register($r);
            $this->return = array_merge($this->return,[
                'data' => $data,
                'code' => 201
            ]);

        } catch(\Exception $e){
            $this->return = array_merge($this->return,[
                'status' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode()==0?422:$e->getCode()
            ]);
        }

        return response()->json($this->return, $this->return['code']);
    }

    /**
     * @OA\Post(
     *     path="/auth/del",
     *     tags={"Authorized"},
     *     summary="Delete user for access API",
     *     description="",
     *     operationId="delete",
     *     @OA\Parameter(
     *         name="id",
     *         required=true,
     *         in="query",
     *         description="id from last user created",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Hard delete user from database"),
     *     security={
     *         {"authorization": {}}
     *     },
     * )
     */
    public function delete(Request $r){
        $this->validate($r, [
            'id' => 'required|int',
        ]);

        $credentials = $r->only(['id']);

        try {
            // validate user exists or not
            if(!($sql = User::find($credentials['id']))) throw new \Exception("ID aren't found", 422);

            if(!$sql->delete()) throw new \Exception("Failed to remove user", 422);
        } catch(\Exception $e){
            $this->return = array_merge($this->return,[
                'status' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode()==0?422:$e->getCode()
            ]);
        }

        return response()->json($this->return, $this->return['code']);
    }

    /**
     * @OA\Get(
     *     path="/auth/list",
     *     tags={"Authorized"},
     *     summary="List of User was created",
     *     description="",
     *     operationId="list",
     *     @OA\Response(response="200", description="list of user was created"),
     *     security={
     *         {"authorization": {}}
     *     },
     * )
     */
    public function list(Request $r){
        try {
            $data = (new User)->orderBy('name','ASC')->orderBy('email','ASC')->get();
            $this->return = array_merge($this->return,[
                'data' => $data
            ]);
        } catch(\Exception $e){
            $this->return = array_merge($this->return,[
                'status' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode()==0?401:$e->getCode()
            ]);
        }

        return response()->json($this->return, $this->return['code']);
    }
}
