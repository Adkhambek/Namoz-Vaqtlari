<?php
include 'Telegram.php';
include 'simple_html_dom.php';

//Telegram variables

$bot_token = 'BOT_TOKEN';
$telegram = new Telegram($bot_token);
$chat_id = $telegram->ChatID();
$text = $telegram->Text();

//Web-Scraping variables

$url = file_get_html('https://islom.uz/vaqtlar/27/5');
$tbody = $url->find('tbody', 0);
$today = $tbody->find('tr[class="p_day bugun"]', 0);
$tomorrow = $tbody->find('tr[class="p_day erta"]', 0);
$juma = $tbody->find('tr[class="juma erta"]', 0);
$jumaToday = $tbody->find('tr[class="juma bugun"]', 0);

// Date for checking Friday

$date = new DateTime();
$weeknumber = $date->format('N');

switch ($text) {
    case '/start':
        mainMenu();
        break;
    case '⌛️ Бугун':
        if ($weeknumber === 5) {
            prayerTime($jumaToday);
        } else {
            prayerTime($today);
        }
        break;
    case '⏳ Эртага':
        if ($weeknumber === 4) {
            prayerTime($juma, "<b>Тошкент</b>  учун эртанги кун намоз вақтлари:");
        } else {
            prayerTime($tomorrow, "<b>Тошкент</b>  учун эртанги кун намоз вақтлари:");
        }
        break;
    case '🌇 Бошқа шаҳарлар':
        otherCity();
        break;
    case '🤲 Оғиз очиш,ёпиш дуоси':
        fastingDua();
        break;
}

function mainMenu()
{
    global $telegram, $chat_id;
    $option = [
        [$telegram->buildKeyboardButton("⌛️ Бугун"), $telegram->buildKeyboardButton("⏳ Эртага")],
        [$telegram->buildKeyboardButton("🌇 Бошқа шаҳарлар"), $telegram->buildKeyboardButton("🤲 Оғиз очиш,ёпиш дуоси")],
    ];
    $keyb = $telegram->buildKeyBoard($option, $onetime = false, $resize = true);
    $content = array('chat_id' => $chat_id, 'text' => 'Ушбу бот орқали кунлик намоз вақтларини исталган вақтингизда билиб боришингиз мумкин, Намоз вақтлари мунтазам islom.uz сайтидан олиб борилмоқда');
    $telegram->sendMessage($content);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => 'Намоз вақтлари  Тошкент  uchun, қуйидагилардан бирини танланг!');
    $telegram->sendMessage($content);
}

function prayerTime($arrDay, $header = "<b>Тошкент</b> учун бугунги кун намоз вақтлари:")
{
    global $chat_id, $telegram, $url;
    $arrThead = [];
    $arrTbodyToday = [];
    $thead = $url->find('thead', 0)->find('tr', 0);
    $string = $header . "\n\n";

    foreach ($thead->find('th') as $value) {
        $title = $value->plaintext;
        $arrThead[] = $title;
    }
    foreach ($arrDay->find('td') as $item) {
        $time = $item->plaintext;
        $arrTbodyToday[] = $time;
    }
    for ($i = 3; $i < 9; $i++) {
        $string .= "<b>" . $arrThead[$i] . ":</b> " . $arrTbodyToday[$i] . "\n";
    }
    $string = implode("\n", explode("\n", $string));
    $content = array('chat_id' => $chat_id, 'text' => $string, 'parse_mode' => 'html');
    $telegram->sendMessage($content);

}

function otherCity()
{
    global $telegram, $chat_id;
    $text = "<b>Тошкентдан бошқа шаҳарлардаги вақлар фарқи(дақиқа):</b>\n
<b>Аввал:</b>
Чимкент (-1)
Конибодом (-5)
Қўқон (-7)
Жамбул (-7)
Наманган(-10)
Фарғона(-10)
Марғилон(-10)
Андижон (-12)
Ўш (-14)
Жалолобод (-15)
Бишкек (-21)
\n<b>Кейин:</b>
Бекобод (+4)
Туркистон (+4)
Жиззах (+6)
Гулистон (+7)
Денов (+7)
Жонбой (+7)
Самарқанд (+9)
Шаҳрисабз (+10)
Каттақўрғон (+12)
Қарши (+9)
Нурота (+14)
Навоий (+19)
Бухоро (+21)
Хива (+35)
Нукус (+42)";
    $content = array('chat_id' => $chat_id, 'text' => $text, 'parse_mode' => 'html');
    $telegram->sendMessage($content);
}

function fastingDua()
{
    global $telegram, $chat_id;
    $text = "<b>Саҳарлик (оғиз ёпиш) дуоси:</b>
Навайту ан асума совма шаҳри Рамазона минал фажри илал мағриби, холисан лиллаҳи таъала.
\n<b>Ифторлик (оғиз очиш) дуоси:</b>
Аллоҳумма лака сумту ва бика аманту ва аъалайка таваккалту ва ъала ризқика афтарту, фағфирли, йа Ғоффару, ма қоддамту вама аххорту.";
    $content = array('chat_id' => $chat_id, 'text' => $text, 'parse_mode' => 'html');
    $telegram->sendMessage($content);
}
