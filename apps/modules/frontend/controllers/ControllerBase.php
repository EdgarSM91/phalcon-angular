<?php
namespace Modules\Frontend\Controllers;
use Phalcon\Mvc\Controller;
class ControllerBase extends Controller
{
    public function initialize()
    {
        $this->assets->collection('JsIndex')
            ->setTargetPath("front/src/js/general.min.js")
            ->setTargetUri("front/src/js/general.min.js")
            ->addJs("front/src/js/jquery-1.11.1.min.js")
            ->addJs("default/angular/angular.min.js")
            ->addJs("default/angular/angular-route.min.js")
            ->addJs("default/angular/angular-strap.min.js")
            ->addJs("default/angular/angular-strap.tpl.min.js")
            ->addJs("default/angular/ng-map.min.js")
            ->addJs("front/app.js")
            ->addJs("front/services/contact/token.js")
            ->addJs("front/controllers/IndexController.js")
            ->addJs("front/controllers/ContactController.js")
            ->addJs("front/src/js/custom.js")
            ->join(true)
            ->addFilter(new \Phalcon\Assets\Filters\Jsmin());

        $this->assets->collection('CssIndex')
            ->setTargetPath("front/src/css/general.min.css")
            ->setTargetUri("front/src/css/general.min.css")
            ->addCss("default/css/bootstrap.min.css")
            ->addCss("front/src/css/font-awesome.min.css")
            ->addCss("front/src/css/superfish.css")
            ->addCss("front/src/css/owl.carousel.css")
            ->addCss("front/src/css/owl.theme.css")
            ->addCss("front/src/css/jquery.navgoco.css")
            ->addCss("front/src/css/flexslider.css")
            ->addCss("front/src/css/style.css")
            ->addCss("front/src/css/responsive.css")
            ->addCss("front/src/css/azul.css")
            ->addCss("front/src/css/custom.css")
            ->join(true)
            ->addFilter(new \Phalcon\Assets\Filters\Cssmin());
    }
    public function response($dataArray,$status)
    {
        $this->view->disable();
        if($status==200){
            $this->response->setStatusCode($status, "OK");
        }else{
            $this->response->setStatusCode($status, "ERROR");
        }
        $this->response->setJsonContent($dataArray);
        $this->response->send();
        exit();
    }
    public function metaHome($action,$canonical,$image,$description){
        $this->session->set("meta",
            array(
                "title"=>"$action",
                "url"=>$this->url->getBaseUri()."$canonical",
                "image"=>$this->url->getBaseUri()."dash/img/notes/800x600/$image",
                "description"=>"$description"
            )
        );
        /*{{ router.getRewriteUri() }}*/
    }
    public function header($action){
        $ct = array("futbol"=>"F","basquetbol"=>"B","beisbol"=>"BE","box"=>"BX","otros"=>"O","contactanos"=>"C","index"=>"I","acerca"=>"AC");
        $this->session->set("header",
            array(
                "$ct[$action]"=>"current-menu-ancestor",
            )
        );
    }
    public function cleaCategory($string){
        return  mb_strtolower(str_replace(' ', '-',str_replace('-','',$string)), 'UTF-8');
    }
    public function dateSpanish(){
        return array(
            "01"=>"Enero",
            "02"=>"Febrero",
            "03"=>"Marzo",
            "04"=>"Abril",
            "05"=>"Mayo",
            "06"=>"Junio",
            "07"=>"Julio",
            "08"=>"Agosto",
            "09"=>"Septiembre",
            "10"=>"Octubre",
            "11"=>"Noviembre",
            "12"=>"Diciembre"
        );
    }
}
