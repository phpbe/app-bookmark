<?php

namespace Be\App\Bookmark\Service;

use Be\Be;

class Url
{

    /**
     * 获取分类列表
     *
     * @return array 分类列表
     */
    public function getCategories(): array
    {
        $db = Be::getDb();
        $sql = 'SELECT * FROM bookmark_category WHERE is_delete=0 AND is_enable=0 ORDER BY ordering ASC';
        $categories = Be::getDb()->getObjects($sql);
        foreach ($categories as $category) {
            $sql = 'SELECT * FROM bookmark_group WHERE category_id=? AND is_delete=0 AND is_enable=0 ORDER BY ordering ASC';
            $groups = $db->getObjects($sql, [$category->id]);
            foreach ($groups as $group) {
                $group->ordering = (int)$group->ordering;
                $group->is_enable = (int)$group->is_enable;
                $group->is_delete = (int)$group->is_delete;

                $sql = 'SELECT * FROM bookmark_url WHERE group_id =? AND is_delete=0 AND is_enable=0 ORDER BY ordering ASC';
                $urls = $db->getObjects($sql, [$group->id]);
                foreach ($urls as $url) {
                    $url->ordering = (int)$url->ordering;
                    $url->has_account = (int)$url->has_account;
                    $url->is_enable = (int)$url->is_enable;
                    $url->is_delete = (int)$url->is_delete;
                }
                $group->urls = $urls;
            }
            $category->groups = $groups;
        }
        return $categories;
    }

    /**
     * 获取分类树
     *
     * @return array 分类树
     */
    public function getTree(): array
    {
        $categories = $this->getCategories();
        return $this->makeTree($categories);
    }

    /**
     * 生成树
     *
     * @param array $categories
     * @param string $parentId
     * @return array
     */
    private function makeTree(array $categories, string $parentId = '')
    {
        $children = [];
        foreach ($categories as $category) {
            if ($category->parent_id === $parentId) {
                $category->children = $this->makeTree($categories, $category->id);
                $children[] = $category;
            }
        }
        return $children;
    }


}
