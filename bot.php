<?php
include 'Telegram.php'; 
include 'simple_html_dom.php';
$url = file_get_html('https://islom.uz/lotin');
$dataTime = $url->find('div[class="date_time"]', 0)->plaintext;
$telegram = new Telegram('BOT_TOKEN');
$chat_id = $telegram->ChatID();
$text = $telegram->Text();

if ($text == '/start') {
    $option = [
        [$telegram->buildKeyboardButton("Nomoz vaqti")]
    ];
    $keyb = $telegram->buildKeyBoard($option, $onetime = false, $resize = true);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => 'Assalomu alekum, Botimizga xush kelibsiz!', );
    $telegram->sendMessage($content);
} elseif ($text == 'Nomoz vaqti') {
    $arr = prayerTime();
    $text = "<b>".$dataTime."</b> \n\n";
    foreach ($arr as $key => $value) {
        $text .= "<b>".$key.":</b> ". $value."\n";
    }
    $text = implode("\n", explode('\n', $text));
    $content = array('chat_id' => $chat_id, 'text' => $text, 'parse_mode' => "html" );
    $telegram->sendMessage($content);
}


function prayerTime(){
    global $url;
    $dataTime = $url->find('div[class="date_time"]', 0)->plaintext;
    $prayertime = $url->find('div[class="cricle"]');
    $arr = [];
    foreach ($prayertime as $item ) {
       $title = $item->find('div[class="p_v"]', 0)->plaintext; 
       $time = $item->find('b', 0)->plaintext;
        $arr[$title] = $time;
    } 
    return $arr;
}