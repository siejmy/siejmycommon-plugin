<?php
class TimeAgoRenderer {
  static $scriptName = 'time-ago-script';
  static $enqueueScript = false;
  static function getTimeAgoKatoPL($post, $opts = array()) {
    self::$enqueueScript = true;

    $prepend = isset($opts['prepend']) ? $opts['prepend'] : '';
    $cssClass = isset($opts['cssClass']) ? $opts['cssClass'] : '';

    $timestampS = get_the_time('U', $post);
    $isoDatetime = get_the_time('c', $post);
    $descriptiveData = get_the_date('', $post);

    return '<div class="' . $cssClass . '">'
            . '<amp-script script="' . self::$scriptName . '" layout="fixed-height" height="16">'
              . $prepend
              . '<time datetime="' . $isoDatetime . '" data-timestamp-s="' . $timestampS . '">' . $descriptiveData . '</time>'
            . '</amp-script>'
          . '</div>';
  }

  static function getEnqueuedScripts() {
    if(!self::$enqueueScript) return '';
    return '
<script id="' . self::$scriptName . '" type="text/plain" target="amp-script">
const content = document.querySelector("time");
const timestamp = content.getAttribute("data-timestamp-s");
updateTimeAgo();

function updateTimeAgo() {
  content.innerHTML="◉ " + getKatoFunkyTimeAgo(Number(timestamp));
  setTimeout(() => updateTimeAgo(), 10 * 1000);
}

function getKatoFunkyTimeAgo(timestamp) {
  const zdrowaskaS = 20; // 20 seconds
  const dziesiatkaRozancaS = 5 * 60; // 5 minutes
  const czescRozanzaS = 25 * 60; // 25 minutes
  const hourS = 3600;
  const dayS = 24 * 3600;
  const weekS = 7 * 24 * 3600;
  const miesiaceDopelniacz = ["stycznia", "lutego", "marca", "kwietnia", "maja", "czerwca", "lipca", "sierpnia", "września", "października", "listopada", "grudnia"];

  const difference = (Date.now()/1000) - timestamp;

  if (difference < 90) {
    return "Właśnie teraz";
  }
  else if (difference < zdrowaskaS * 20) {
    const zdrowaski = Math.floor(difference / zdrowaskaS);
    return zdrowaski + " zdrowasiek temu";
  }
  else if (difference < czescRozanzaS) {
    const no = Math.floor(difference / dziesiatkaRozancaS);
    if (no == 1) return "Dziesiątkę różańca temu";
    else if (no < 5) return no + " dziesiątki różańca temu";
    else return no + " dziesiątek różańca temu";
  }
  else if (difference < 11 * czescRozanzaS) {
    const no = Math.floor(difference / czescRozanzaS);
    if (no == 1) return "Część różańca temu";
    else return no + " części różańca temu";
  }
  else if (difference < dayS) {
    const no = Math.floor(difference / hourS);
    if (no < 2) return "Godzinę temu";
    else if (no < 5) return no + " godziny temu";
    else return no + " godzin temu";
  }
  else if (difference < weekS) {
    const no = Math.floor(difference / dayS);
    if (no < 2) return "Wczoraj";
    else return no + " dni temu";
  }
  else if (difference < 4 * weekS) {
    const no = Math.floor(difference / weekS);
    if (no == 1) return "Tydzień temu";
    else if (no < 5) return no + " tegodnie temu";
    else return no + " tygodni temu";
  }

  const date = new Date(timestamp * 1000);
  return date.getDate() + " " + miesiaceDopelniacz[date.getMonth()] + " " + date.getYear();
}
</script>
    ';
  }
}

function time_ago_footer_hook() {
  echo TimeAgoRenderer::getEnqueuedScripts();
}
?>
