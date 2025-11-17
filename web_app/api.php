<?php
// Suas chaves
$google_maps_api_key = "AIzaSyD1ymgJSOFD9yCS4hoC7hNeU8Km40bbQi0";

// Recebe dados do link
$local = $_GET['local'] ?? '';
$data_evento = $_GET['data'] ?? '';

if (!$local || !$data_evento) {
    echo "Dados insuficientes.";
    exit;
}

// Geocodificação do endereço
$url_endereco = urlencode($local);
$geo_url = "https://maps.googleapis.com/maps/api/geocode/json?address=$url_endereco&key=$google_maps_api_key";

$geo_resposta = json_decode(file_get_contents($geo_url), true);

if ($geo_resposta["status"] !== "OK") {
    echo "Endereço não encontrado.";
    exit;
}

$lat = $geo_resposta["results"][0]["geometry"]["location"]["lat"];
$lon = $geo_resposta["results"][0]["geometry"]["location"]["lng"];


$timestamp_evento = strtotime($data_evento);
$achou = false;