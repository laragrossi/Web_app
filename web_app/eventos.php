<?php
session_start();
include "db.php";

// Verifica se o usuário está logado
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

// Chave das APIs
$google_maps_api_key = "AIzaSyD1ymgJSOFD9yCS4hoC7hNeU8Km40bbQi0";
$weather_key = "69372cda46f535a7e11c458f6c63530b";

// Função para buscar clima baseado na data do evento
function getWeatherForEvent($endereco, $data_evento, $google_key, $weather_key) {

    // 1) Geocodificação
    $url_endereco = urlencode($endereco);
    $geo_url = "https://maps.googleapis.com/maps/api/geocode/json?address=$url_endereco&key=$google_key";

    $geo_resposta = json_decode(file_get_contents($geo_url), true);

    if ($geo_resposta["status"] !== "OK") {
        return "Endereço não encontrado.";
    }

    $lat = $geo_resposta["results"][0]["geometry"]["location"]["lat"];
    $lon = $geo_resposta["results"][0]["geometry"]["location"]["lng"];

    // 2) Buscar previsão de 7 dias
    $onecall_url = "https://api.openweathermap.org/data/2.5/onecall?lat=$lat&lon=$lon&exclude=hourly,minutely,current,alerts&units=metric&lang=pt_br&appid=$weather_key";

    $clima_resposta = json_decode(file_get_contents($onecall_url), true);

    if (!isset($clima_resposta["daily"])) {
        return "Não foi possível obter o clima.";
    }

    $timestamp_evento = strtotime($data_evento);

    foreach ($clima_resposta["daily"] as $dia) {
        if (date("Y-m-d", $dia["dt"]) == date("Y-m-d", $timestamp_evento)) {

            $temp = $dia["temp"]["day"];
            $desc = ucfirst($dia["weather"][0]["description"]);
            $icone = $dia["weather"][0]["icon"];

            return "
                <p><strong>Clima previsto:</strong> $desc</p>
                <p><strong>Temperatura média:</strong> $temp°C</p>
                <img src='https://openweathermap.org/img/wn/$icone.png'>
            ";
        }
    }

    return "A previsão só está disponível para os próximos 7 dias.";
}

// Busca os eventos no banco
$sql = "SELECT * FROM eventos ORDER BY data ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos Culturais</title>
    <link rel="stylesheet" href="../css/eventos.css">

    <style>
        .btn-api {
            margin-top: 10px;
            display: inline-block;
            background: #f87cf6;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            border: none;
        }
        .btn-api:hover { background: #facbf9; }
       
    </style>
</head>

<body>
    <div class="container">

        <header>
            <h1>Eventos Culturais</h1>
            <a href="home.php" class="voltar">← Voltar para Home</a>
        </header>

        <section class="lista-eventos">

            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($evento = $result->fetch_assoc()): ?>

                    <div class="card-evento">
                        <h2><?= htmlspecialchars($evento['nome']); ?></h2>

                        <p><strong>Descrição:</strong> <?= htmlspecialchars($evento['descricao']); ?></p>
                        <p><strong>Local:</strong> <?= htmlspecialchars($evento['local']); ?></p>
                        <p><strong>Data:</strong> <?= date("d/m/Y", strtotime($evento['data'])); ?></p>
                        <p><strong>Hora:</strong> <?= date("H:i", strtotime($evento['hora'])); ?></p>
                        <p><strong>Capacidade:</strong> <?= $evento['capacidade']; ?> pessoas</p>

                        <?php if (!empty($evento['imagem'])): ?>
                            <img src="uploads/<?= htmlspecialchars($evento['imagem']); ?>" class="imagem-evento">
                        <?php endif; ?>

                        <!-- BOTÃO MAPA -->
                        <a class="btn-api"
                           href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($evento['local']); ?>"
                           target="_blank">
                            Ver Localização
                        </a>

                        <div class="weather"></div>

                        <form action="reserva.php" method="POST">
                            <input type="hidden" name="id_evento" value="<?= $evento['id_evento']; ?>">
                            <button type="submit" class="btn-reservar">Reservar</button>
                        </form>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>
                <p class="mensagem">Nenhum evento disponível no momento.</p>
            <?php endif; ?>

        </section>
    </div>

    <script>
        function mostrarClima(botao, local, data) {
            const div = botao.nextElementSibling;

            div.innerHTML = "Carregando clima...";

            fetch("clima_evento.php?local=" + encodeURIComponent(local) + "&data=" + data)
                .then(r => r.text())
                .then(res => div.innerHTML = res)
                .catch(() => div.innerHTML = "Erro ao buscar o clima.");
        }
    </script>

</body>
</html>