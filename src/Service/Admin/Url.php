<?php

namespace Be\App\Bookmark\Service\Admin;

use Be\App\ServiceException;
use Be\Be;

class Url
{

    /**
     * 获取分类列表
     *
     * @return array 分类列表
     */
    public function getGroupUrls($categoryId): array
    {
        $db = Be::getDb();

        $sql = 'SELECT * FROM bookmark_group WHERE category_id=? AND is_delete=0 ORDER BY ordering ASC';
        $groups = $db->getObjects($sql, [$categoryId]);

        foreach ($groups as $group) {
            $sql = 'SELECT * FROM bookmark_url WHERE group_id =? AND is_delete=0 ORDER BY ordering ASC';
            $group->urls = $db->getObjects($sql, [$group->id]);
        }

        return $groups;
    }


}
