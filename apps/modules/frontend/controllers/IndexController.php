<?php
namespace Modules\Frontend\Controllers;
class IndexController extends ControllerBase
{
    public function indexAction(){
    }
    public function contactAction(){
    }
    public function tokenAction(){
        $this->response(array("token"=>"1"),200);
    }
}