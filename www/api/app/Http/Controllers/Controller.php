<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *  title="API TEST Halotek",
 *  version="0.0.0",
 *  termsOfService="http://swagger.io/terms/",
 *  @OA\Contact(
 *   email="faujiakbar@gmail.com"
 *  ),
 *  description="This is a public API. Provide by [http://swagger.io](http://swagger.io) or on [irc.freenode.net, #swagger](http://swagger.io/irc/).",
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="authorization",
 *     type="apiKey",
 *     in="header",
 *     name="authorization"
 * )
 */

/**
 * @OA\Post(
 *     path="/",
 *     tags={"System"},
 *     @OA\Response(response="200", description="Lumen Version")
 * )
 */


class Controller extends BaseController
{
    //

    public $return;

    public function __construct(){
        $this->return = [
            'status' => true,
            'message' => "Success",
            'data' => [],
            'code' => 200
        ];
    }
}
