<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prodi;

class ProdiController extends Controller
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
     *     path="/prodi/add",
     *     tags={"Prodi"},
     *     summary="Add prodi",
     *     description="",
     *     operationId="add",
     *     @OA\Parameter(
     *         name="prodi_name",
     *         required=true,
     *         in="query",
     *         description="Name of prodi",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="prodi_program",
     *         required=true,
     *         in="query",
     *         description="Program in prodi",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="prodi_level",
     *         required=true,
     *         in="query",
     *         description="Prodi level (Choose: Master/Bachelor)",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(response="201", description="Created Prodi"),
     *     security={
     *         {"authorization": {}}
     *     },
     * )
     */
    public function add(Request $r){
        $this->validate($r, [
            'prodi_name' => 'required|string',
            'prodi_program' => 'required|string',
            'prodi_level' => 'required|string',
        ]);

        $prodi = $r->only(['prodi_name', 'prodi_program', 'prodi_level', 'user']);
        
        try {
            // trim data
            foreach($prodi as $key => $val){
                if(is_string($val)) $prodi[$key] = trim($val);
            }
            
            // validate user exists or not
            // if(Prodi::exists($prodi)) throw new \Exception("Prodi & Program are exists", 422);

            // validate prodi_level
            $level = ["Bachelor", "Master"];
            if(!in_array($prodi['prodi_level'],$level)) throw new \Exception("Prodi level must in(".implode(", ",$level).")", 422);
            
            // insert into prodi
            $data = Prodi::add($prodi);

            $this->return = array_merge($this->return,[
                'data' => $data->only(['id','reg_no', 'prodi_name', 'prodi_program', 'prodi_level', 'created_by', 'created_at'])
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
     * @OA\Get(
     *     path="/prodi/list",
     *     tags={"Prodi"},
     *     summary="List of Prodi was created",
     *     description="",
     *     operationId="list",
     *     @OA\Response(response="200", description="list of prodi was created")
     * )
     */
    public function list(Request $r){
        try {
            $data = (new Prodi)->orderBy('prodi_name','ASC')->orderBy('reg_no','ASC')->get();
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

    /**
     * @OA\Post(
     *     path="/prodi/del",
     *     tags={"Prodi"},
     *     summary="Delete Prodi",
     *     description="",
     *     operationId="delete",
     *     @OA\Parameter(
     *         name="id",
     *         required=true,
     *         in="query",
     *         description="id from list prodi created",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Hard delete prodi from database"),
     *     security={
     *         {"authorization": {}}
     *     },
     * )
     */
    public function delete(Request $r){
        $this->validate($r, [
            'id' => 'required|int',
        ]);

        $prodi = $r->only(['id']);

        try {
            // validate user exists or not
            if(!($sql = Prodi::find($prodi['id']))) throw new \Exception("ID aren't found", 422);

            if(!$sql->delete()) throw new \Exception("Failed to remove prodi", 422);
        } catch(\Exception $e){
            $this->return = array_merge($this->return,[
                'status' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode()==0?422:$e->getCode()
            ]);
        }

        return response()->json($this->return, $this->return['code']);
    }
}