<?php

namespace Be\App\Bookmark\Controller;

use Be\Be;

class Auth
{

    /**
     * 登录
     * @BeRoute("/bookmark/login")
     */
    public function login()
    {
        $request = Be::getRequest();
        $response = Be::getResponse();

        if ($request->isAjax()) {
            $password = $request->post('password');
            $config = Be::getConfig('App.Bookmark.Auth');
            if ($config->password === $password) {
                $response->cookie('Bookmark:Password', md5('Bookmark:Password:' . $config->password), 86400 * 180);

                $response->set('success', true);
                $response->set('message', '登录成功！');
                $response->set('redirectUrl', beUrl('Bookmark.Url.index'));
                $response->json();
            } else {
                $response->set('success', false);
                $response->set('message', '密码错误！');
                $response->json();
            }
        } else {
            $response->set('title', '登录');
            $response->display();
        }
    }


}
