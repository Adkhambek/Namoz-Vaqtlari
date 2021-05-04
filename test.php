<?php
include 'simple_html_dom.php';
$url = file_get_html('https://islom.uz/lotin');
echo $url->find('div[class="date_time"]', 0)->plaintext;
$prayertime = $url->find('div[class="cricle"]');

foreach ($prayertime as $variable ) {
    echo '<br>';
    echo $variable->find('div[class="p_v"]', 0)->plaintext .": ". $variable->find('b', 0)->plaintext;
    echo '<br>';
}

$quron = file_get_html('https://islom.uz/mano_tarjima/78');
$rows = $quron->find('div[class="row quran_oyat"]');
$suraName = $quron->find('h2[class="col-12 title_block"]', 0)->plaintext;
echo $suraName;

$arr = [];
foreach ($rows as $row ) {
    $call = $row->find('div[class="col-11"]', 0);
    $text_islom = $call->find('div[class="col-12 text_islom"]',0)->plaintext;
    $arr[] = $text_islom;

}

print_r($arr);
