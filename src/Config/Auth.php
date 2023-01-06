<?php
namespace Be\App\Bookmark\Config;

/**
 * @BeConfig("身份验证")
 */
class Auth
{

    /**
     * @BeConfigItem("访问密码",
     *     description="留空时不需要密码",
     *     driver="FormItemInput"
     * )
     */
    public string $password = '123456';


}

