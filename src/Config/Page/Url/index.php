<?php

namespace Be\App\Bookmark\Config\Page\Url;

class index
{

    public int $north = 0;
    public int $middle = 1;
    public int $south = 0;

    public array $middleSections = [
        [
            'name' => 'App.Bookmark.UrlTree',
        ],
    ];

}
