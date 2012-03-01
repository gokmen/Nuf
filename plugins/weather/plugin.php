<?php

    # Copyright (C) 2012, Nuf (~Fun)
    # 2012 - Gökmen Göksel <gokmen@goksel.me>
    #        http://github.com/gokmen

    # This program is free software; you can redistribute it and/or modify it
    # under the terms of the GNU General Public License as published by the Free
    # Software Foundation; either version 2 of the License, or (at your option)
    # any later version.

    $ar = xml2array("http://www.mgm.gov.tr/FTPDATA/analiz/sonSOA.xml");
    
    function getWeather($raw_list, $city)
    {
        $min = '';
        foreach ($raw_list['SOA']['sehirler'] as $cities)
            if ($cities['ili'] == $city) {
                if (!is_array($cities['Min']))
                    $min = " / <b>".$cities['Min']."</b>°C";
                return " <b>".$cities['Mak']."</b>°C".$min;
            }
        return "<!-- Hava durumu alınamıyor -->";
    }
    
    foreach(array('ISTANBUL', 'ANKARA', 'IZMIR', 'BURSA', 'EDIRNE') as $city)
        $Layout->set("weather:{$city}", getWeather($ar, $city));

?>
