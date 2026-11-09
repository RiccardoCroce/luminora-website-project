<?php
session_start();
$logged = $_SESSION['loggedin'] ?? false;
$username = $_SESSION['username'] ?? "";

if (!$logged || $username == "") {
    header("Location: loginDentro.php");
    exit;
}
ini_set('display_errors', 'On');
error_reporting(E_ALL);

date_default_timezone_set('Europe/Rome');
require 'ConnessioneScuola.php';

// ── Tema ───────────────────────────────────────────────────────
if (isset($_GET['tema']) && count($_GET) === 1) {
    $tema_scelto = $_GET['tema'];
    if ($tema_scelto === 'light' || $tema_scelto === 'dark') {
        setcookie('tema', $tema_scelto, time() + (86400 * 30), "/");
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

$tema = 'light';
if (isset($_COOKIE['tema'])) {
    $cookie_tema = $_COOKIE['tema'];
    if ($cookie_tema === 'light' || $cookie_tema === 'dark') {
        $tema = $cookie_tema;
    }
}

// ── Visite ─────────────────────────────────────────────────────
$message = "";
$last_visit_date = null;
$last_ip = null;

function anonymize_ip($ip) {
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        $parts = explode('.', $ip);
        return implode('.', $parts);
    }
    return $ip;
}

try {
    $pdo = connectDB();

    $pdo->exec("CREATE TABLE IF NOT EXISTS visite (
        id  INT AUTO_INCREMENT PRIMARY KEY,
        data DATETIME,
        ip   VARCHAR(45)
    )");

    $current_ip   = anonymize_ip($_SERVER['REMOTE_ADDR']);
    $current_time = date("Y-m-d H:i:s");

    $stmt = $pdo->prepare("SELECT data, ip FROM visite ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result) {
        $last_visit_date = $result['data'];
        $last_ip         = $result['ip'];
        $message         = "La tua ultima visita è stata il: <br>" . $last_visit_date;
    } else {
        $message = "Benvenuto per la prima volta!";
    }

    $stmt_insert = $pdo->prepare("INSERT INTO visite (data, ip) VALUES (:data, :ip)");
    $stmt_insert->execute([':data' => $current_time, ':ip' => $current_ip]);

    $pdo = null;

} catch (PDOException $e) {
    $message = "Errore nel database: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Luminora Italia</title>
  <link rel="icon" href="LuminoraIt.png" type="image/png">
  <link rel="stylesheet" href="Luminora.css?v=1.1">
</head>
<body class="<?php echo $tema; ?>">

  <form method="get" style="margin:0;">
    <input type="hidden" name="tema" value="<?php echo ($tema === 'light') ? 'dark' : 'light'; ?>">
    <button id="cambiaTema" type="submit" class="btn-sole">
      <?php echo ($tema === 'light') ? '🌙 Dark' : '☀️ Light'; ?>
    </button>
  </form>

  <div class="top-left-message">
    <?php echo $message; ?>
    <?php if ($last_visit_date != null) { ?>
      <br>IP: <?php echo htmlspecialchars($last_ip); ?>
    <?php } ?>
    <br>Utente: <strong><?php echo htmlspecialchars($username); ?></strong>
  </div>

  <div class="main-content">
    <h1>Luminora Italia</h1>
    <p class="subtitle">Gestionale Impianti Fotovoltaici</p>
    <div class="solar-divider"></div>

    <div class="links-container">
      <a href="InserimentoImpianto.php">➕ Inserisci Impianto</a>
      <a href="visualizzazioneImpianti.php">📋 Visualizza Tutti gli Impianti</a>
      <a href="CambiamentoContratto.php">📅 Cambia Fine Contratto</a>
      <a href="CambiamentoRoyalties.php">💰 Aggiorna Royalties</a>
      <a href="CancellazioneTabella.php" style="color: var(--errore);">🗑️ Cancellazione Impianto</a>
      <a href="esci.php" class="logout" style="color: white !important;">🚪 Esci (Logout)</a>
    </div>
  </div>

</body>
</html>