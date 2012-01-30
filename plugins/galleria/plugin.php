<?php

    # Copyright (C) 2011, Nuf (~Fun)
    # 2011 - Gökmen Göksel <gokmen@goksel.me>
    #        http://github.com/gokmen

    # This program is free software; you can redistribute it and/or modify it
    # under the terms of the GNU General Public License as published by the Free
    # Software Foundation; either version 2 of the License, or (at your option)
    # any later version.

    $_template = dirname(__FILE__)."/galleria-header.html";

    if (strpos($Content, "[@picasa_album]") !== false) {
        $_plugin_header .= readContent($_template);
        $_content = strip_tags($Content);
        $_album = new Template(dirname(__FILE__)."/album-template.html");
        $_album->set("album", trim($_content));
        $Content = $_album->output();
    }

?>
