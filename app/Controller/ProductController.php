<?php

namespace Idharf\PhpMvc\Controller;

class ProductController
{
    function categories($id, $category)
    {
        echo "Product ID ".$id." Category : ".$category;
    }
}