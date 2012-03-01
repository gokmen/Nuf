<?php

/**
 * TCMB Döviz Kurları Sınıfı.
 *
 * TCMB sunucularındaki Döviz Kurlarını işler.
 * Daha sonraki kullanımlar için sunucu üzerinde cache_dosyası degiskeninde belirtilen isimle yedekler.
 * Günde bir defa yeni kur değerlerini sunucudan çeker.
 *
 * Geliştirici: Göktuğ ÖZTÜRK
 * E-Posta: goktug [nokta] ozturk [at] gmail [nokta] com
 * Web: http://www.goktugozturk.com.tr
 */
class Exchange{
  var $cache_dosyasi = ".exchange_rates";
  var $kurlar = array();
  var $aktif_kur = "USD";
  var $encoding = "ISO-8859-9";
  /**
   * Constructor
   *
   * @access protected
   * @param bool $CacheEnabled
   * @param int $CacheTime
   * @param string $Encoding
   * @return void
   */
  function Exchange($CacheEnabled = true, $CacheTime = 60, $Encoding = "ISO-8859-9"){
    $has_content = false;
    if ($CacheEnabled){
      $CacheSuresi = time() - $CacheTime * 60;
      if (file_exists($this->cache_dosyasi . ".db")
          AND is_readable($this->cache_dosyasi . ".db")
          AND filemtime($this->cache_dosyasi . ".db") > $CacheSuresi){
        $FileContent = $this->rFile($this->cache_dosyasi . ".db");
        if (trim($FileContent) != "") {
          $this->kurlar = unserialize(base64_decode($FileContent));
          $has_content = true;
        }
      }
    }
    $this->encoding = $Encoding;
    if (!$has_content){
      $xmlalanlari = array("Isim" => "Isim",
        "ForexBuying" => "Alis",
        "ForexSelling" => "Satis",
        "BanknoteBuying" => "EfektifAlis",
        "BanknoteSelling" => "EfektifSatis",
        );
      $FileContent = $this->rFile("http://www.tcmb.gov.tr/kurlar/today.xml");
      if ($this->encoding != "ISO-8859-9" AND function_exists("iconv"))
        $FileContent = iconv("ISO-8859-9", $this->encoding, $FileContent);
      preg_match_all('@<Currency Kod="([A-Z]{3})" CurrencyCode="[A-Z]{3}">(.*)<\/Currency>@iU', $FileContent, $Matches, PREG_SET_ORDER);
      foreach($Matches as $value){
        if (!in_array($value[1], array("USD", "EUR", "CAD", "DKK", "SEK", "CHF", "NOK", "JPY", "SAR", "KWD", "AUD", "GBP")))
          continue;
        preg_match_all('@<([a-z]+)>(.*)<\/\\1>@iU', $value[2], $SubMatches, PREG_SET_ORDER);
        foreach($SubMatches as $value2){
          if (!isset($xmlalanlari[$value2[1]])) continue;
          $this->kurlar[$value[1]][$value2[1]] = $value2[2];
        }
      }
      if ($CacheEnabled)
        $this->wFile($this->cache_dosyasi . ".db", base64_encode(serialize($this->kurlar)));
    }
  }

  /**
   * Aktif kuru belirler.
   *
   * Geçerli Kurlar:
   * USD, EUR, CAD, DKK, SEK, CHF, NOK, JPY, SAR, KWD, AUD, GBP
   *
   * @param string $Currency
   * @return string
   */
  function SetCurrency($Currency){
    $Currency = strtoupper($Currency);
    if (isset($this->kurlar[$Currency])){
      $this->aktif_kur = $Currency;
      return true;
    }
    return false;
  }

  /**
   * Aktif Kur için Alış değerini getirir.
   *
   * @param string $Currency
   * @return string
   */
  function ForexBuying($Currency = null){
    if (is_null($Currency))
      $Currency = $this->aktif_kur;
    else
      $Currency = strtoupper($Currency);
    return $this->kurlar[$Currency]["ForexBuying"];
  }

  /**
   * Aktif Kur için Satış değerini getirir.
   *
   * @param string $Currency
   * @return string
   */
  function ForexSelling($Currency = null){
    if (is_null($Currency))
      $Currency = $this->aktif_kur;
    else
      $Currency = strtoupper($Currency);
    return $this->kurlar[$Currency]["ForexSelling"];
  }

  /**
   * Belirtilen yoldaki dosya içeriğini getirir.
   *
   * @param string $FileName
   * @param string $Mode
   * @return string
   */
  function rFile($FileName){
    $Content = "";
    if (!preg_match('@^https?:@', $FileName)
        OR ini_get('allow_url_fopen') == '1'){
      if (function_exists('file_get_contents')){
        $Content = file_get_contents($FileName);
      }else{
        if (!$Handle = fopen($FileName, "r"))
          return "Dosya Açılamıyor ($FileName)";
        $Content = fread($Handle, filesize($FileName));
        fclose($Handle);
      }
    }else if (function_exists('curl_init')){
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $FileName);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $Content = curl_exec($ch);
      curl_close($ch);
    }else
      return "";
    return $Content;
  }

  /**
   * Verilen içeriği dosyaya yazar.
   *
   * @param string $FileName
   * @param string $Content
   * @param int $Chmod
   * @param string $Mode
   * @return bool
   */
  function wFile($FileName, $Content){
    if (function_exists('file_put_contents')){
      if (!file_put_contents($FileName, $Content))
        return false;
    }else{
      if (!$Handle = @fopen($FileName, "w"))
        return false;
      if (@fwrite($Handle, $Content) === false)
        return false;
      @fclose($Handle);
    }
    @chmod($FileName, 0644);
    return true;
  }
}

?>