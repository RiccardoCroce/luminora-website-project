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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = (int) $_POST['id'];

    try {
        $pdo = connectDB();
        if ($id === 0) {
            $sql = "DELETE FROM Luminora";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $righe_affette = $stmt->rowCount();
            if ($righe_affette > 0) {
                $successo = true;
                $messaggio = "Tutti gli impianti sono stati cancellati ($righe_affette righe eliminate).";
            } else {
                $messaggio = "La tabella era già vuota, nessun impianto da cancellare.";
            }
        } else {
            $sql = "DELETE FROM Luminora WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $righe_affette = $stmt->rowCount();
            if ($righe_affette > 0) {
                $successo = true;
                $messaggio = "Impianto con ID n. $id cancellato con successo!";
            } else {
                $messaggio = "Nessun impianto trovato con ID n. $id.";
            }
        }
    } catch (PDOException $e) {
        $messaggio = "Errore durante la cancellazione: " . $e->getMessage();
    } finally {
        $pdo = null;
    }

} else {
    $messaggio = "Accesso non autorizzato.";
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Cancellazione Impianto – Luminora Italia</title>
  <link rel="stylesheet" href="Luminora.css">
</head>
<body>

  <div class="page-center">
    <div class="card">

      <div class="site-header">
        <img src="LuminoraIt.png" alt="Luminora Logo" class="logo-img">
        <h2><?php echo $successo ? '✅ Cancellazione Completata' : '❌ Errore Cancellazione'; ?></h2>
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