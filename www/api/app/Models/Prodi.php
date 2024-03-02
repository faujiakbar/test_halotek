<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Prodi extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'reg_no', 'prodi_name', 'prodi_program', 'prodi_level', 'created_by'
    ];

    protected $table = "prodis";

    public static function exists($data=[]){
        $query = self::where(['prodi_name' => $data['prodi_name'], 'prodi_program' => $data['prodi_program']])->first();
        return $query;
    }

    private static function autoGenerateNo($data=[]){
        // default variable
        $no_order = 1;
        $prefix = "";
        $year = date("Y");

        // generate default prefix
        $tmp_abbr = explode(" ", $data['prodi_name']);
        foreach($tmp_abbr as $str){
            $prefix .= strtoupper(substr($str,0,1));
        }

        // check prefix is exists
        $sql = self::select('prodi_name')->where('reg_no','ilike',$prefix."%")->groupBy('prodi_name')->get()->toArray();
        if(count($sql) > 0){
            $exists_prefix = false;
            foreach($sql as $i => $row){
                if(strtolower($row["prodi_name"]) == strtolower($data['prodi_name'])){
                    $sql_pre = self::select('reg_no')->where(['prodi_name' => $row["prodi_name"]])->first();
                    list($prefix,$year,$no_order) = explode("/",$sql_pre->reg_no);
                    $exists_prefix = true;
                    break;
                }
            }
            if(!$exists_prefix) $prefix .= count($sql) + 1;
        }

        // checking data first if exists
        $sql = self::where(['prodi_name' => $data['prodi_name']])->whereRaw("extract('year' from created_at) = '".$year."'")->orderBy("reg_no", "DESC")->first();
        if($sql){
            list($prefix,$year,$no_order) = explode("/",$sql->reg_no);
            $no_order = (int) $no_order;
            $no_order++;
        }

        return $prefix."/".$year."/".$no_order;
    }

    public static function add($data=[]){
        $sql = new Prodi;
        $sql->reg_no = self::autoGenerateNo($data);
        $sql->prodi_name = $data['prodi_name'];
        $sql->prodi_program = $data['prodi_program'];
        $sql->prodi_level = $data['prodi_level'];
        $sql->created_by = $data['user']->id;

        $ret = $sql->save();

        return ($ret?$sql:null);
    }
}
