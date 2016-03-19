<?php

namespace Test\Controller;

use \Think\Controller;

class IndexController extends Controller
{

    public function index()
    {
        header("Content-type: text/html; charset=utf-8");

        //建立 Person这个类的反射类
        $class = new \ReflectionClass('Test\Controller\AllTestController');
        $methods = $class->getMethods();
        foreach ($methods as $methodName) {
            if (strstr($methodName, "test")) {
                echo "请求方法:";
                echo $methodName->getName();
                echo '<hr>';
                $methodName->invoke(new AllTestController());
                echo '<hr>';
            }
        }
    }
}