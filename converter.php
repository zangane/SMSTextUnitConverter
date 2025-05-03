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
        $encoding = "GSM 7bit";
        $parts = $length <= 160 ? 1 : ceil($length / 153);
    } else {
        $encoding = "Unicode";
        $parts = $length <= 70 ? 1 : ceil($length / 67);
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

    echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Result</title><link rel='stylesheet' href='style.css'></head><body>";
    echo "<div class='container'>";
    echo "<h2>Conversion Result</h2>";
    echo "<p><strong>Character count:</strong> {$result['length']}</p>";
    echo "<p><strong>Encoding type:</strong> {$result['encoding']}</p>";
    echo "<p><strong>SMS parts required:</strong> {$result['parts']}</p>";
    echo "<a href='index.php'>Back</a>";
    echo "</div></body></html>";
}
