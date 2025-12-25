<?php
namespace App;

class Controller
{
    public function view($view, $data = [])
    {
        extract($data);

        $path = __DIR__ . "/views/" . $view . ".php"; 
    

        if (file_exists($path)) {
            include $path;
        } else {
            echo "View not found: " . $path;  
        }
    }
   
}

