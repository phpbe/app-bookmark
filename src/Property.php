<?php

namespace Be\App\Bookmark;


class Property extends \Be\App\Property
{

    protected string $label = '书签';
    protected string $icon = 'bi-cart';
    protected string $description = '书签（网址收藏）';

    public function __construct() {
        parent::__construct(__FILE__);
    }

}
