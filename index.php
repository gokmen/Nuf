<?php

    # Copyright (C) 2011, Nuf (~Fun)
    # 2011 - Gökmen Göksel <gokmen@goksel.me>
    #        http://github.com/gokmen

    # This program is free software; you can redistribute it and/or modify it
    # under the terms of the GNU General Public License as published by the Free
    # Software Foundation; either version 2 of the License, or (at your option)
    # any later version.

    if (file_exists('config.ini') and file_exists('pages.ini')) {
        # Main Nuf (~Fun) Content Management Class
        include("nuf/nuf.class.php");

        # Create a new instance
        $PCM = new Nuf();

        # If there is a page requested try to set it as current page
        if ($_GET["page"])
            $PCM->setCurrentPage($_GET["page"]);

        # Build and show the page
        echo buildPage($PCM);
    }
    else {
        echo "Please look at README.md file.";
    }

?>
