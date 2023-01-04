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
            $group->is_enable = (int)$group->is_enable;
            $group->ordering = (int)$group->ordering;

            $sql = 'SELECT * FROM bookmark_url WHERE group_id =? AND is_delete=0 ORDER BY ordering ASC';
            $urls = $db->getObjects($sql, [$group->id]);
            foreach ($urls as $url) {
                $url->is_enable = (int)$url->is_enable;
                $url->ordering = (int)$url->ordering;
                $url->has_account = (int)$url->has_account;
            }
            $group->urls = $urls;
        }

        return $groups;
    }


}
