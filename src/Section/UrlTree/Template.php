<?php

namespace Be\App\Bookmark\Section\UrlTree;

use Be\Be;
use Be\Theme\Section;

class Template extends Section
{

    public array $positions = ['middle', 'center'];


    public function display()
    {
        if ($this->config->enable === 0) {
            return;
        }

        $this->css();

        $serviceUrl = Be::getService('App.Bookmark.Url');
        $urlTree = $serviceUrl->getTree();

        echo '<div class="be-tab">';
        echo '<div class="be-tab-nav">';
        $i = 0;
        foreach ($urlTree as $category) {
            echo '<a';
            if ($i === 0) {
                echo ' class="be-tab-nav-active"';
            }
            echo 'data-be-target="#be-tab-pane-'.$category->id.'">'.$category->name.'</a>';
            $i++;
        }
        echo '</div>';
        echo '<div class="be-tab-content be-pt-200">';
        foreach ($urlTree as $category) {
            echo '<div class="be-tab-pane" id="be-tab-pane-'.$category->id.'">';
            $this->urls($category->groups);
            if (count($category->children) > 0) {
                echo '<div class="be-tab">';
                echo '<div class="be-tab-nav">';
                $j = 0;
                foreach ($category->children as $subCategory) {
                    echo '<a';
                    if ($j === 0) {
                        echo ' class="be-tab-nav-active"';
                    }
                    echo 'data-be-target="#be-tab-pane-'.$subCategory->id.'">'.$subCategory->name.'</a>';
                    $j++;
                }
                echo '</div>';
                echo '<div class="be-tab-content be-pt-200">';
                foreach ($category->children as $subCategory) {
                    echo '<div class="be-tab-pane" id="be-tab-pane-'.$subCategory->id.'">';
                    $this->urls($subCategory->groups);
                    if (count($subCategory->children) > 0) {
                        echo '<div class="be-tab">';
                        echo '<div class="be-tab-nav">';
                        $k = 0;
                        foreach ($subCategory->children as $subSubCategory) {
                            echo '<a';
                            if ($k === 0) {
                                echo ' class="be-tab-nav-active"';
                            }
                            echo 'data-be-target="#be-tab-pane-'.$subSubCategory->id.'">'.$subSubCategory->name.'</a>';

                            $k++;
                        }
                        echo '</div>';
                        echo '<div class="be-tab-content be-pt-200">';
                        foreach ($subCategory->children as $subSubCategory) {
                            echo '<div class="be-tab-pane" id="be-tab-pane-'.$subSubCategory->id.'">';
                            $this->urls($subSubCategory->groups);
                            echo '</div>';
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';

        $this->js();
    }

    public function urls($groups)
    {
        foreach ($groups as $group) {
            if (count($group->urls) === 0) {
                continue;
            }

            if ($group->name !== '') {
                echo '<h4 class="be-h4">' . $group->name . '</h4>';
            }

            foreach ($group->urls as $url) {
                if ($url->name !== '') {
                    echo $url->name . '：';
                }

                if ($url->url !== '') {
                    echo '<a href="'. $url->url .'" target="_blank">'. $url->url .'</a>&nbsp;';
                }

                if ($url->has_account === 1) {
                    echo $url->username . '/' . $url->password;
                }

                echo '&nbsp;&nbsp;&nbsp;';
            }

            echo '<br>';
        }
    }

    private function css() {
        echo '.be-tab {';
        echo '}';

        echo '.be-tab-nav {';
        echo 'display: flex;';
        echo 'border-bottom: #666 1px solid;';
        echo '}';

        echo '.be-tab-nav a {';
        echo 'display: block;';
        echo 'border: 1px solid transparent;';
        echo 'padding: .5rem 1rem;';
        echo 'cursor: pointer;';
        echo 'margin-bottom: -1px;';
        echo 'text-decoration: none;';
        echo 'color: #000;';
        echo 'font-weight: bold;';
        echo '}';

        echo '.be-tab-nav a:before {';
        echo 'background-color: transparent;';
        echo '}';

        echo '.be-tab-nav a:hover {';
        echo 'text-decoration: none;';
        echo '}';

        echo '.be-tab-nav-active {';
        echo 'border-color: #666 !important;';
        echo 'border-bottom-color: #fff !important;';
        echo 'border-top-left-radius: .25rem;';
        echo 'border-top-right-radius: .25rem;';
        echo '}';

        echo '.be-tab-pane {';
        echo 'display: none;';
        echo '}';

        echo '.be-tab-pane-active {';
        echo 'display: block;';
        echo '}';
    }


    private function js() {
        echo '<script type="text/javascript">';
        echo '$(function () {';
        echo '$(".be-tab").each(function () {';
        echo 'let $tab = $(this);';
        echo 'let $tabNav = $tab.children(".be-tab-nav");';
        echo 'let $tabNavItem = $tabNav.children("a");';

        echo '$tabNavItem.click(function () {';
        echo '$tabNavItem.removeClass("be-tab-nav-active");';
        echo 'let $this = $(this);';
        echo '$this.addClass("be-tab-nav-active");';
        echo '$tab.children(".be-tab-content").children(".be-tab-pane").removeClass("be-tab-pane-active");';
        echo '$($this.data("be-target")).addClass("be-tab-pane-active");';
        echo 'return false;';
        echo '});';

        echo 'let $tabNavItemActive = $(".be-tab-nav .be-tab-nav-active", $tab);';
        echo 'if ($tabNavItemActive.length === 0) {';
        echo '$tabNavItemActive = $(".be-tab-nav li:first-child", $tab);';
        echo '$tabNavItemActive.addClass("be-tab-nav-active");';
        echo '}';

        echo 'if ($tabNavItemActive) {';
        echo '$tabNavItemActive.trigger("click");';
        echo '}';

        echo '});';
        echo '});';
        echo '</script>';
    }


}

