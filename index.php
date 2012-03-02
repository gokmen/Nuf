<?php

    # Copyright (C) 2011-2012, Nuf (~Fun)
    # 2011,2012 - Gökmen Göksel <gokmen@goksel.me>
    #             http://github.com/gokmen

    # This program is free software; you can redistribute it and/or modify it
    # under the terms of the GNU General Public License as published by the Free
    # Software Foundation; either version 2 of the License, or (at your option)
    # any later version.

    if (file_exists('config.ini') and file_exists('pages.ini')) {
        # Main Nuf (~Fun) Content Management Class
        include("nuf/nuf.class.php");
        $cachefile = "cache/".$_GET["page"].".html";

        // Serve from the cache if it is the same age or younger than the last
        // modification time of the included file (includes/$reqfilename)

        $cachetime = 5 * 60;
        if (file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile))) {
           include($cachefile);
           echo "<!-- Cached at ".date('H:i', filemtime($cachefile))." -->\n";
           exit;
        }

        // start the output buffer
        ob_start();

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

    // open the cache file for writing
    $fp = fopen($cachefile, 'w');

    // save the contents of output buffer to the file
    fwrite($fp, ob_get_contents());

    // close the file
    fclose($fp);

    // Send the output to the browser
    ob_end_flush();

?>
