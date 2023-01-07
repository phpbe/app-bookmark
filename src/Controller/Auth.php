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

        if ($request->isPost()) {
            $password = $request->post('password');
            $config = Be::getConfig('App.Bookmark.Auth');
            if ($config->password === $password) {
                $response->cookie('Bookmark:Password', md5('Bookmark:Password:' . $config->password), time() + 86400 * 180, '/', $request->getDomain(), false, true);
                $response->success( '登录成功！', ['url' => beUrl('Bookmark.Url.index')]);
            } else {
                $response->error('密码错误！', ['url' => beUrl('Bookmark.Auth.login')]);
            }
        } else {
            $response->set('title', '登录');
            $response->display();
        }
    }


}
