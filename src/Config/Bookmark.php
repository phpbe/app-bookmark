<?php
namespace Be\App\Bookmark\Config;

/**
 * @BeConfig("书签")
 */
class Bookmark
{

    /**
     * @BeConfigItem("访问密码",
     *     description="留空时不需要密码",
     *     driver="FormItemInput"
     * )
     */
    public string $password = '';


}

