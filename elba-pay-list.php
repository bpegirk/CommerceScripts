<?php
/**
 * Скрипкт для создания переходного файла для эльбы. Формирует список для ипморта - в формат 1С.
 * Входной файл берется с txt файла.
 * формат:
 * ДАТА / НАЛ / БЕЗНАЛ
 *
 * @author Bpeg
 * @version 1.0
 */
$params = [
    'schet' => 'РасчСчет=40802810231110008646'
];

$file = "elba-pay-list.txt";

if (!file_exists($file)) {
    echo "File not found";
    return;
}
$content = file($file);
$numStart = 1;

$result = '';
$minDate = "";
$maxDate = "";
foreach ($content as $line) {
    list($date, $nal, $card) = explode("\t", $line);
    $minDate = min($minDate, $date);
    $maxDate = max($maxDate, $date);
    $result .= writeNal($date, $nal);
    $result .= writeCard($date, $card);
}
file_put_contents('elba-pay-result.txt',
    writeHeader($minDate, $maxDate) .
    $result
);


function writeHeader($minDate, $maxDate)
{
    global $params;
    return "1CClientBankExchange\n" .
        "ВерсияФормата=1.02\n" .
        "Кодировка=Windows\n" .
        "Отправитель=Банк Клиент Онлайн\n" .
        "Получатель=1С:Предприятие\n" .
        "ДатаСоздания=" . date("d.m.Y") . "\n" .
        "ВремяСоздания=" . date("H:i:s") . "\n" .
        "ДатаНачала=" . $minDate . "\n" .
        "ДатаКонца=" . $maxDate . "\n" .
        "РасчСчет=" . $params['schet'] . "\n";

}

function writeNal($date, $sum)
{
    global $numStart;
    if (!$sum > 0) {
        return '';
    }

    return "СекцияДокумент=Платежное поручение\n" .
        "Номер=" . $numStart++ . "\n" .
        "Дата=" . $date . "\n" .
        "Сумма=" . writeSum($sum) . "\n" .
        "КвитанцияДата=" . $date . "\n" .
        "КвитанцияВремя=00:00:00\n" .
        "КвитанцияСодержание=статус документа: Внешний\n" .
        "ПлательщикСчет=00000000000000000000\n" .
        "ПлательщикИНН=0000000000\n" .
        "Плательщик1=Физ лицо\n" .
        "ПлательщикРасчСчет=00000000000000000000\n" .
        "ПлательщикБанк1=Наличка\n" .
        "ПлательщикБанк2=Г. ИРКУТСК\n" .
        "ПлательщикБИК=000000000\n" .
        "ПлательщикКорсчет=00000000000000000000\n" .
        "ПолучательСчет=40802810231110008646\n" .
        "ДатаПоступило=" . $date . "\n" .
        "ПолучательИНН=381116569485\n" .
        "Получатель1=Индивидуальный предприниматель Чернигова Татьяна Валерьевна\n" .
        "ПолучательРасчСчет=00000000000000000000\n" .
        "ПолучательБанк1=Касса\n" .
        "ПолучательБанк2=г. Иркутск\n" .
        "ПолучательБИК=000000000\n" .
        "ПолучательКорсчет=00000000000000000000\n" .
        "ВидПлатежа=\n" .
        "ВидОплаты=01\n" .
        "Очередность=05\n" .
        "НазначениеПлатежа=Оприходование розничной выручки\n" .
        "ПлательщикКПП=000000000\n" .
        "ПолучательКПП=0\n" .
        "КонецДокумента\n";
}

function writeCard($date, $sum)
{
    global $numStart;
    if (!$sum > 0) {
        return '';
    }

    return "СекцияДокумент=Платежное поручение\n" .
        "Номер=" . $numStart++ . "\n" .
        "Дата=" . $date . "\n" .
        "Сумма=" . writeSum($sum) . "\n" .
        "КвитанцияДата=" . $date . "\n" .
        "КвитанцияВремя=00:00:00\n" .
        "КвитанцияСодержание=статус документа: Внешний\n" .
        "ПлательщикСчет=30233810418350101000\n" .
        "ПлательщикИНН=7707083893\n" .
        "Плательщик1=БАЙКАЛЬСКИЙ БАНК ПАО СБЕРБАНК\n" .
        "ПлательщикРасчСчет=30233810418350101000\n" .
        "ПлательщикБанк1=БАЙКАЛЬСКИЙ БАНК ПАО СБЕРБАНК\n" .
        "ПлательщикБанк2=Г. ИРКУТСК\n" .
        "ПлательщикБИК=042520607\n" .
        "ПлательщикКорсчет=30101810900000000607\n" .
        "ПолучательСчет=40802810231110008646\n" .
        "ДатаПоступило=" . $date . "\n" .
        "ПолучательИНН=381116569485\n" .
        "Получатель1=Индивидуальный предприниматель Чернигова Татьяна Валерьевна\n" .
        "ПолучательРасчСчет=40802810231110008646\n" .
        "ПолучательБанк1=ФИЛИАЛ № 5440 БАНКА ВТБ (ПАО)\n" .
        "ПолучательБанк2=ГНовосибирск\n" .
        "ПолучательБИК=045004719\n" .
        "ПолучательКорсчет=30101810450040000719\n" .
        "ВидПлатежа=\n" .
        "ВидОплаты=01\n" .
        "Очередность=05\n" .
        "НазначениеПлатежа=Зачисление средств по операциям с МБК (на основании реестров платежей).\n" .
        "ПлательщикКПП=000000000\n" .
        "ПолучательКПП=0\n" .
        "КонецДокумента\n";
}


function writeSum($sum)
{
    return number_format(trim($sum), 2, ".", "");
}

/**
 * Скрипт для клиентской части, что бы переделать все в наличку

<script>
    function doNormal() {
        window.cbList = {};
        var $dom = $("#ItemsList_Rows  span:contains('Оплата товаров и'):first").parents('div[id*="_tr"]:first');
        if ($dom.length == 0) {
            return false;
        }
        $dom.trigger('click');
        window.cbList['f'] = setTimeout(waitModal, 100);
    }

    function waitModal() {
        if ($("#Lightboxes").find('.c-lightbox-wrap').is(':hidden')) {
            window.cbList['f'] = setTimeout(waitModal, 100);
            return;
        }
        $("#ComponentsHost_PaymentEditLightbox_FormOfMoneySelect").trigger('click');
        $("#ComponentsHost_PaymentEditLightbox_FormOfMoneySelect_Options > div:eq(1)").trigger('click');
        $("#ComponentsHost_PaymentEditLightbox_AcceptButton").trigger('mousedown');
        $("#ComponentsHost_PaymentEditLightbox_AcceptButton").trigger('mouseup');

        window.cbList['s'] = setTimeout(waitLoad, 100);
    }

    function waitLoad() {
        if (!$("#Lightboxes").find('.c-lightbox-wrap').is(':hidden')) {
            window.cbList['s'] = setTimeout(waitLoad, 100);
            return;
        }
        setTimeout(doNormal, 2000);
    }

</script>

 **/