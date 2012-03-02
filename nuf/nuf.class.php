<?php

    # Copyright (C) 2011-2012, Nuf (~Fun)
    # 2011,2012 - Gökmen Göksel <gokmen@goksel.me>
    #             http://github.com/gokmen

    # This program is free software; you can redistribute it and/or modify it
    # under the terms of the GNU General Public License as published by the Free
    # Software Foundation; either version 2 of the License, or (at your option)
    # any later version.

    require("helper.function.php");

    # Nuf (~Fun) Class
    class Nuf {

        protected $pages = array();
        protected $currentPage;

        public $template = "bootstrap";

        # Contstructor
        public function __construct($config_file = "config.ini", $pages_file  = "pages.ini") {
            $this->config = parse_ini_file($config_file);
            $this->_pages = parse_ini_file($pages_file, true);
            $this->pages  = array_keys($this->_pages);
            $this->pageExtension = "html";
            $this->plugins = explode(', ', $this->getConfig("plugins", ''));
            $this->template = $this->getConfig("template", "default");
        }

        # Set Current Page
        public function setCurrentPage($page) {
            $ignore_not_added_pages = $this->getConfig("ignore_not_added_pages", false);
            if (array_search($page, $this->pages) !== false or $ignore_not_added_pages) {
                $this->currentPage = $page;
                if (!$ignore_not_added_pages)
                    if (array_key_exists("template", $this->_pages[$page]))
                        $this->pageStyle = "-{$this->_pages[$page]["template"]}";
                return true;
            }
            if (array_search("404", $this->pages) !== false)
                $this->setCurrentPage("404");
            return false;
        }

        # Return current page item
        public function getCurrentPage() {
            return $this->currentPage;
        }

        # Get current page name from current page item
        public function getCurrentPageName() {
            if (!$this->isPageHidden($this->currentPage))
                return $this->getCurrentPageProperty("name");
        }

        # Return given property for current page item
        # If property does not exists or its empty it returns default value
        public function getCurrentPageProperty($property, $default_value = false) {
            if (array_search($page, $this->pages) !== false)
                if (array_key_exists($property, $this->_pages[$this->currentPage]))
                    return $this->_pages[$this->currentPage][$property];
            return $default_value;
        }

        # Get site wide system config property
        # IF property does not exists or its empty it returns default value
        public function getConfig($property, $default_value = false) {
            if (array_key_exists($property, $this->config))
                return $this->config[$property];
            return $default_value;
        }

        # Check if page is hidden
        private function isPageHidden($page) {
            if (array_search($page, $this->pages) !== false)
                if (array_key_exists("hidden", $this->_pages[$page]))
                    return $this->_pages[$page]["hidden"];
            return false;
        }

        # It builds menu for given page values
        # It uses selected template's menuitem.html and menuitem_active.html for building the menu
        # If page hidden in its property it doesn't show up in the menu
        public function buildMenu() {
            foreach (array_keys($this->_pages) as $page) {
                if (!$this->isPageHidden($page)) {
                    if ($page == $this->currentPage)
                        $row = new Template("templates/{$this->template}/menuitem_active.html");
                    else
                        $row = new Template("templates/{$this->template}/menuitem.html");

                    $row->set("name", $this->_pages[$page]["name"]);

                    if (array_key_exists("link", $this->_pages[$page]))
                        $row->set("address", $this->_pages[$page]["link"]);
                    else
                        $row->set("address", "{$this->config['url']}/{$page}");
                    $menuItems[] = $row;
                }
            }
            if ($menuItems)
                return Template::merge($menuItems);
        }

        # Return current page content
        public function getContent($just_addr = false) {
            $file = "pages/{$this->currentPage}.html";
            $this->pageExtension = "html";
            $content = readContent($file);
            if ($content === false) {
                $file = "pages/{$this->currentPage}.txt";
                $this->pageExtension = "txt";
                $content = readContent($file);
                if ($content === false) {
                    if (array_search("404", $this->pages) !== false) {
                        $this->setCurrentPage("404");
                        return $this->getContent();
                    }
                    $content = "<b>Page not found.</b> Please put something into <pre>{$this->config["url"]}/{$file}</pre>";
                }
            }
            if ($just_addr)
                return $file;
            return $content;
        }

    }

?>
