<?php 

namespace Idharf\PhpMvc\App;

class View
{
    public static function render($view, $model)
    {
        require __DIR__ . "/../View/header.php";
        require __DIR__ . "/../View/" . $view . ".php";
        require __DIR__ . "/../View/footer.php";
    }

    public static function redirect($url){
        header("Location: $url");
    }
}