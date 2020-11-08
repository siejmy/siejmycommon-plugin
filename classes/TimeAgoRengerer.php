<?php
class TimeAgoRengerer {
  static $zdrowaskaS = 20; // 20 seconds
  static $dziesiatkaRozancaS = 5 * 60; // 5 minutes
  static $czescRozanzaS = 25 * 60; // 25 minutes
  static $hourS = 3600;
  static $dayS = 24 * 3600;
  static $weekS = 7 * 24 * 3600;

  static function getTimeAgoKatoPL($post, $prepend) {
    $timestamp = get_the_time('U', $post);
    $timeFormatted = get_the_time( '', $post );

    return '<span class="time tooltip" data-tooltip-title="' . $timeFormatted . '" data-tooltip-position="bottom"'
    . '>'
      . $prepend
      . self::getFunkKatoFormatted($timestamp, $timeFormatted)
    . '</span>';
  }

  static  function getFunkKatoFormatted($timestamp, $timeFormatted) {
    $difference = time() - $timestamp;

    if ($difference < 90) {
      return 'Właśnie teraz';
    }
    else if ($difference < self::$zdrowaskaS * 20) {
      $zdrowaski = floor($difference / self::$zdrowaskaS);
      return $zdrowaski . ' zdrowasiek temu';
    }
    else if ($difference < self::$czescRozanzaS) {
      $no = floor($difference / self::$dziesiatkaRozancaS);
      if ($no == 1) return 'Dziesiątkę różańca temu';
      else if ($no < 5) return $no . ' dziesiątki różańca temu';
      else return $no . ' dziesiątek różańca temu';
    }
    else if ($difference < 11 * self::$czescRozanzaS) {
      $no = floor($difference / self::$czescRozanzaS);
      if ($no == 1) return 'Część różańca temu';
      else return $no . ' części różańca temu';
    }
    else if ($difference < self::$dayS) {
      $no = floor($difference / self::$hourS);
      if ($no < 2) return 'Godzinę temu';
      else if ($no < 5) return $no . ' godziny temu';
      else return $no . ' godzin temu';
    }
    else if ($difference < self::$weekS) {
      $no = floor($difference / self::$dayS);
      if ($no < 2) return 'Wczoraj';
      else return $no . ' dni temu';
    }
    else if ($difference < 4 * self::$weekS) {
      $no = floor($difference / self::$weekS);
      if ($no == 1) return 'Tydzień temu';
      else if ($no < 5) return $no . ' tegodnie temu';
      else return $no . ' tygodni temu';
    }
    return $timeFormatted;
  }
}
?>
