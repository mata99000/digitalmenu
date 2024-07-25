<?php

require __DIR__ . '/vendor/autoload.php';

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

try {
    // Povezivanje na štampač koristeći ime štampača
    $connector = new WindowsPrintConnector("BIXOLON SRP-350");
    $printer = new Printer($connector);

    // Postavljanje kodne strane CP858 koja podržava euro znak
    $printer->selectCharacterTable(858);

    // Tekst sa specijalnim karakterima
    $text = "Naši proizvodi:\n";
    $text .= "- Jabuke: 2,50 € po kg\n";
    $text .= "- Kruške: 3,00 € po kg\n";
    $text .= "- Grožđe: 4,00 € po kg\n";
    $text .= "Ukupno: 9,50 €\n";

    // Štampanje teksta
    $printer->text($text);

    $printer->cut();
    $printer->close();

    echo "Printed successfully!";
} catch (Exception $e) {
    echo "Greška prilikom štampanja: " . $e->getMessage();
}
?>
