<?php

    # Copyright (C) 2011, Nuf (~Fun)
    # 2011 - Gökmen Göksel <gokmen@goksel.me>
    #        http://github.com/gokmen

    # This program is free software; you can redistribute it and/or modify it
    # under the terms of the GNU General Public License as published by the Free
    # Software Foundation; either version 2 of the License, or (at your option)
    # any later version.

    require("markdown.php");

    if ($PCM->getCurrentPageProperty("type") == "markdown" or
        $PCM->getConfig("render_method") == "markdown")
            $Content = Markdown($Content);

?>
