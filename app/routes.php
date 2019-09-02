<?php
namespace App;

class Routes{
    static public function getRoutes($r){
        $r->addRoute('GET', '/users/{user_id}',  'UserController.getList');
    }
}
