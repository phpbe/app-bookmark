<?php

namespace Be\App\Bookmark\Controller;

use Be\Be;

class Url
{

    /**
     * 网址列表
     *
     * @BeMenu("网址列表")
     * @BeRoute("/bookmark")
     */
    public function index()
    {
        $request = Be::getRequest();
        $response = Be::getResponse();

        $password = $request->cookie('Bookmark:Password');
        $config = Be::getConfig('App.Bookmark.Auth');

        // 校验权限
        if (md5('Bookmark:Password:' . $config->password) !== $password) {
            $redirect = [
                'url' => beUrl('Bookmark.Auth.login'),
                'message' => '{timeout} 秒后跳转到 <a href="{url}">登录页</a>',
                'timeout' => 3,
            ];

            $response->error('请先登录！', $redirect);
            return;
        }

        $response->set('title', '网址列表');
        $response->display();
    }


}
