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
    isset($_POST['royalties']) && $_POST['royalties'] !== '') {

    $id        = (int) $_POST['id'];
    $Royalties = (int) $_POST['royalties'];

    try {
        $pdo = connectDB();
        $sql = "UPDATE Luminora SET Royalties = :Royalties WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':Royalties', $Royalties, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $righe_affette = $stmt->rowCount();
        if ($righe_affette > 0) {
            $successo = true;
            $messaggio = "Royalties dell'impianto n. $id aggiornate con successo! (€ $Royalties/anno)";
        } else {
            $messaggio = "Nessun impianto trovato con ID n. $id.";
        }
    } catch (PDOException $e) {
        $messaggio = "Errore durante l'aggiornamento: " . $e->getMessage();
    } finally {
        $pdo = null;
    }
} else {
    $messaggio = "Accesso non autorizzato o dati mancanti.";
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Aggiornamento Royalties – Luminora Italia</title>
  <link rel="stylesheet" href="Luminora.css">
</head>
<body>

  <div class="page-center">
    <div class="card">

      <div class="site-header">
        <img src="LuminoraIt.png" alt="Luminora Logo" class="logo-img">
        <h2><?php echo $successo ? '✅ Royalties Aggiornate' : '❌ Errore Aggiornamento'; ?></h2>
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