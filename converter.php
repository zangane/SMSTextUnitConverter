<?php

function isGSM($text) {
    $gsmChars = "@£$¥èéùìòÇ\nØø\rÅåΔ_ΦΓΛΩΠΨΣΘΞ !\"#¤%&'()*+,-./0123456789:;<=>?¡ABCDEFGHIJKLMNOPQRSTUVWXYZÄÖÑÜ`abcdefghijklmnopqrstuvwxyzäöñüà";
    for ($i = 0; $i < mb_strlen($text); $i++) {
        if (strpos($gsmChars, mb_substr($text, $i, 1)) === false) {
            return false;
        }
    }
    return true;
}

function getSMSParts($text) {
    $length = mb_strlen($text);
    $isGSM = isGSM($text);

    if ($isGSM) {
        if ($length <= 160) {
            $parts = 1;
        } else {
            $parts = ceil($length / 153);
        }
        $encoding = "GSM 7bit";
    } else {
        if ($length <= 70) {
            $parts = 1;
        } else {
            $parts = ceil($length / 67);
        }
        $encoding = "Unicode";
    }

    return [
        "length" => $length,
        "parts" => $parts,
        "encoding" => $encoding
    ];
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["message"])) {
    $message = trim($_POST["message"]);
    $result = getSMSParts($message);

    echo "<!DOCTYPE html><html lang='fa'><head><meta charset='UTF-8'><title>نتیجه</title><link rel='stylesheet' href='style.css'></head><body>";
    echo "<div class='container'>";
    echo "<h2>نتیجه محاسبه:</h2>";
    echo "<p><strong>تعداد کاراکتر:</strong> {$result['length']}</p>";
    echo "<p><strong>نوع پیامک:</strong> {$result['encoding']}</p>";
    echo "<p><strong>تعداد پارت:</strong> {$result['parts']}</p>";
    echo "<a href='index.php'>🔙 بازگشت</a>";
    echo "</div></body></html>";
}
