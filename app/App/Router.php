<?php

namespace Idharf\PhpMvc\App;

class Router
{
    private static $routes;

    public static function add($method, $path, $controller, $function, $middlewares)
    {
        self::$routes[] = array(
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'function' => $function,
            'middlewares' => $middlewares
        ); 
    }

    public static function run(){
        $path = '/';
        if(isset($_SERVER['PATH_INFO'])) $path = $_SERVER['PATH_INFO'];
        $method = $_SERVER['REQUEST_METHOD'];

        if(!empty(self::$routes)){
            foreach(self::$routes as $key => $route){
                $patern = "#^".$route['path']."$#";
                if(preg_match($patern, $path, $variables) && $method == $route['method']){
                    // echo "CONTROLLER ". $route['controller'] . ' FUNCTION ' . $route['function'];
                    
                    foreach($route['middlewares'] as $middleware){
                        $instance = new $middleware;
                        $instance->before();
                    }

                    $controller = new $route['controller'];
                    $function = $route['function'];
                    // $controller->$function();

                    array_shift($variables);
                    call_user_func_array([$controller, $function], $variables);
                    return;
                }
            }
        }

        http_response_code(404);
        echo 'CONTROLLER NOT FOUND';
    }
}