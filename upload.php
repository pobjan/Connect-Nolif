<?php
// Wczytaj ustawienia z pliku JSON
$settingsFile = 'upload-settings.json';
$settingsContent = file_exists($settingsFile) ? file_get_contents($settingsFile) : '{}';
$settings = json_decode($settingsContent, true);

// Ustawienia daty/godziny
$dateTimezone = $settings['date-timezone'];

// Ustawienia pliku
$fileFormat = $settings['file-format'];
$recordsFolderName = rtrim($settings['records-folder-name']);
$recordsJsonName = rtrim($settings['records-json-name']);

// Wygeneruj timestamp
$timestamp = date("H-i-s_d-m-Y");

// Utwórz pełną ścieżkę do pliku
$target_file = $recordsFolderName . $timestamp . $fileFormat;

// Sprawdź, czy plik został prawidłowo przesłany
if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
    echo "Plik ". basename($_FILES["file"]["name"]). " został przesłany pomyślnie.";

    // Dodaj nazwę pliku do pliku JSON
    $jsonFile = $recordsFolderName . $recordsJsonName;

    // Wczytaj istniejący plik JSON lub utwórz nowy
    $jsonContent = file_exists($jsonFile) ? file_get_contents($jsonFile) : '{}';

    // Konwertuj zawartość JSON na tablicę
    $recordsArray = json_decode($jsonContent, true);

    // Dodaj nową nazwę pliku do tablicy
    $recordsArray['records'][] = $timestamp;

    // Zapisz tablicę z powrotem do pliku JSON
    file_put_contents($jsonFile, json_encode($recordsArray, JSON_PRETTY_PRINT));
} else {
    echo "Wystąpił błąd podczas przesyłania pliku.";
}
?>
