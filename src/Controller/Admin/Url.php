<?php

namespace Be\App\Bookmark\Controller\Admin;

use Be\App\System\Controller\Admin\Auth;
use Be\Be;

/**
 * @BePermissionGroup("网址")
 */
class Url extends Auth
{

    /**
     * 指定项目下的项目文档管理
     *
     * @BeMenu("网址", icon="bi-bookmark-heart", ordering="2.1")
     * @BePermission("网址管理", ordering="2.1")
     */
    public function manager()
    {
        $request = Be::getRequest();
        $response = Be::getResponse();

        $serviceCategory = Be::getService('App.Bookmark.Admin.Category');
        $categoryTree = $serviceCategory->getTree();
        $response->set('categoryTree', $categoryTree);

        $response->set('title', '网址管理');
        $response->display();
    }

    /**
     * 获取文档
     *
     * @BePermission("项目文档管理")
     */
    public function getGroupUrls()
    {
        $request = Be::getRequest();
        $response = Be::getResponse();

        try {
            $categoryId = $request->json('category_id', '');
            $groupUrls = Be::getService('App.Bookmark.Admin.Url')->getGroupUrls($categoryId);
            $response->set('success', true);
            $response->set('message', '获取文档成功！');
            $response->set('groupUrls', $groupUrls);
            $response->json();
        } catch (\Throwable $t) {
            $response->set('success', false);
            $response->set('message', $t->getMessage());
            $response->json();
        }
    }

    /**
     * 添加文档
     *
     * @BePermission("项目文档管理")
     */
    public function create()
    {
        $request = Be::getRequest();
        $response = Be::getResponse();

        try {
            $chapter = Be::getService('App.Doc.Admin.Chapter')->create($request->json());
            $response->set('success', true);
            $response->set('message', '新建文档成功！');
            $response->set('chapter', $chapter);
            $response->json();
        } catch (\Throwable $t) {
            $response->set('success', false);
            $response->set('message', $t->getMessage());
            $response->json();
        }
    }

    /**
     * 保存文档
     *
     * @BePermission("项目文档管理")
     */
    public function edit()
    {
        $request = Be::getRequest();
        $response = Be::getResponse();

        try {
            Be::getService('App.Doc.Admin.Chapter')->edit($request->json('formData'));
            $response->set('success', true);
            $response->set('message', '保存文档成功！');
            $response->json();
        } catch (\Throwable $t) {
            $response->set('success', false);
            $response->set('message', $t->getMessage());
            $response->json();
        }
    }

    /**
     * 文档排序
     *
     * @BePermission("项目文档管理")
     */
    public function sort()
    {
        $request = Be::getRequest();
        $response = Be::getResponse();

        try {
            Be::getService('App.Doc.Admin.Chapter')->sort($request->json('formData'));
            $response->set('success', true);
            $response->set('message', '文档排序成功！');
            $response->json();
        } catch (\Throwable $t) {
            $response->set('success', false);
            $response->set('message', $t->getMessage());
            $response->json();
        }
    }

    /**
     * 删除文档
     *
     * @BePermission("项目文档管理")
     */
    public function delete()
    {
        $request = Be::getRequest();
        $response = Be::getResponse();
        try {
            $chapterId = $request->json('chapter_id', '');
            Be::getService('App.Doc.Admin.Chapter')->delete($chapterId);
            $response->set('success', true);
            $response->set('message', '删除文档成功！');
            $response->json();
        } catch (\Throwable $t) {
            $response->set('success', false);
            $response->set('message', $t->getMessage());
            $response->json();
        }
    }

}
