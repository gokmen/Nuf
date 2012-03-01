<?php

    # Copyright (C) 2011, Nuf (~Fun)
    # 2012 - Gökmen Göksel <gokmen@goksel.me>
    #        http://github.com/gokmen

    # This program is free software; you can redistribute it and/or modify it
    # under the terms of the GNU General Public License as published by the Free
    # Software Foundation; either version 2 of the License, or (at your option)
    # any later version.

    require_once("exchange_lib.php");

    $TCMB = new Exchange(true, 60, "UTF-8");
    
    $Layout->set("exchange:b:usd", $TCMB->ForexBuying("USD"));
    $Layout->set("exchange:s:usd", $TCMB->ForexSelling("USD"));
    $Layout->set("exchange:b:eur", $TCMB->ForexBuying("EUR"));
    $Layout->set("exchange:s:eur", $TCMB->ForexSelling("EUR"));
    
?>
