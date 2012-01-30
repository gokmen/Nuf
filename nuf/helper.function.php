<?php

    # Copyright (C) 2011, Nuf (~Fun)
    # 2011 - Gökmen Göksel <gokmen@goksel.me>
    #        http://github.com/gokmen

    # This program is free software; you can redistribute it and/or modify it
    # under the terms of the GNU General Public License as published by the Free
    # Software Foundation; either version 2 of the License, or (at your option)
    # any later version.

    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

    require("template.class.php");

    # A simple function to read given file
    function readContent($file) {
        if (!file_exists($file))
            return false;
        return file_get_contents($file);
    }

    # Page builder function which based on given PCM instance
    function buildPage($PCM) {
        # If no such page requested or requested page is not valid
        # use defined homepage in config
        if (!$PCM->getCurrentPage())
            $PCM->setCurrentPage($PCM->config["homepage"]);

        $Layout = new Template("templates/{$PCM->config["template"]}/template{$PCM->pageStyle}.html");
        $Layout->set("title", $PCM->config["title"]);
        $Layout->set("hostname", $PCM->config["url"]);
        $Layout->set("templatedir", "templates/{$PCM->config["template"]}");
        $Layout->set("page", $PCM->getCurrentPageName());
        $Layout->set("menu", $PCM->buildMenu());
        $Layout->set("author", $PCM->config["author"]);
        $Layout->set("description", $PCM->config["description"]);

        $Content = new Template($PCM->getContent(true));

        # Set unknown keywords for template customization
        foreach(array_keys($PCM->config) as $keyword) {
            $Layout->set("c:{$keyword}", $PCM->config[$keyword]);
            $Content->set("c:{$keyword}", $PCM->config[$keyword]);
        }

        $Content = $Content->output($keep_keywords = true, $ignore_warnings = true);

        $_plugin_header = '';
        # FIXME Find a proper solution for plugin usage
        foreach (glob("plugins/*") as $filename) {
            $plugin = substr($filename, strlen("plugins/"));
            if (in_array($plugin, $PCM->plugins)) {
                include $filename."/plugin.php";
            }
        }
        $Layout->set("c:plugin_header", $_plugin_header);

        $Layout->set("content", $Content);
        return $Layout->output();
    }

?>
