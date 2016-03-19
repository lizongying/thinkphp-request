<?php

namespace Test\Controller;

class AllTestController
{

    public function first()
    {

//        httpRequest($url, $post = [], $limit = 0, $cookie = '', $timeout = 15, $block = TRUE)

//        get数据  数组
        $get = ["Mode" => "testMode",];

//        请求url 建议U方法
        $url = U("Test/test", $get);

//        post数据  数组
        $post = ['user'=>'1234','password'=>[7,8,9]];

//        返回内容字节限制
        $limit = 0;

        $result = D("HttpClient", "Service")->httpRequest($url, $post, $limit);

//        输出结果
        $request = ['get'=>$get,'post'=>$post];

        echo '请求数据:'.'<br>';
//        echo json_encode($request);
        var_dump($result['request']);
        echo '<hr>';
        echo '返回页头:'.'<br>';
//        echo json_encode($result['header']);
        var_dump($result['header']);
        echo '<hr>';
        echo '返回内容:'.'<br>';
//        echo json_encode($result['content']);
        var_dump($result['content']);
        echo '<hr>';
    }
} 