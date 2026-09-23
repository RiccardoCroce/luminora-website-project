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
require 'ConnessioneScuola.php';

$messaggio = "";
$successo = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" &&
    !empty($_POST['id']) &&
    !empty($_POST['data_fine_contratto'])) {

    $id = (int) $_POST['id'];
    $FineContratto = $_POST['data_fine_contratto'];

    if (!DateTime::createFromFormat('Y-m-d', $FineContratto)) {
        $messaggio = "Errore: La data non è in un formato valido (AAAA-MM-GG).";
    } else {
        try {
            $pdo = connectDB();
            $sql = "UPDATE Luminora SET FineContratto = :FineContratto WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':FineContratto', $FineContratto);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $righe_affette = $stmt->rowCount();
            if ($righe_affette > 0) {
                $successo = true;
                $messaggio = "Fine contratto dell'impianto n. $id aggiornata con successo!";
            } else {
                $messaggio = "Nessun impianto trovato con ID n. $id.";
            }
        } catch (PDOException $e) {
            $messaggio = "Errore durante l'aggiornamento: " . $e->getMessage();
        } finally {
            $pdo = null;
        }
    }
} else {
    $messaggio = "Accesso non autorizzato o dati mancanti.";
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Aggiornamento Contratto – Luminora Italia</title>
  <link rel="stylesheet" href="Luminora.css">
</head>
<body>

  <div class="page-center">
    <div class="card">

      <div class="site-header">
        <img src="LuminoraIt.png" alt="Luminora Logo" class="logo-img">
        <h2><?php echo $successo ? '✅ Contratto Aggiornato' : '❌ Errore Aggiornamento'; ?></h2>
        <div class="solar-divider"></div>
      </div>

      <?php if ($messaggio != "") { ?>
        <p class="<?php echo $successo ? 'msg-ok' : 'msg-err'; ?>"><?php echo htmlspecialchars($messaggio); ?></p>
      <?php } ?>

      <div class="centered-button">
        <a href="PaginaPrincipaleLuminora.php" class="btn-back">↩️ Ritorna al Menu</a>
      </div>

    </div>
  </div>

</body>
</html>