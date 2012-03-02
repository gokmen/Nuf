<?php

    # Copyright (C) 2011-2012, Nuf (~Fun)
    # 2011,2012 - Gökmen Göksel <gokmen@goksel.me>
    #             http://github.com/gokmen

    # This program is free software; you can redistribute it and/or modify it
    # under the terms of the GNU General Public License as published by the Free
    # Software Foundation; either version 2 of the License, or (at your option)
    # any later version.

    $_template = dirname(__FILE__)."/google-analytics-header.html";

    if (array_key_exists("tracker_code", $PCM->config)) {
        $Layout->set("google_analytics", readContent($_template));
        $Layout->set("tracker_code", $PCM->config["tracker_code"]);
    }

?>
