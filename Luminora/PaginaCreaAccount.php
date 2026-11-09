<?php
require 'ConnessioneScuola.php';

$messaggio = "";
$successo = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" &&
    !empty($_POST['username']) &&
    !empty($_POST['password'])) {

    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];

    try {
        $pdo = connectDB();
        $sql = "INSERT INTO utenti (utente, password) VALUES (:utente, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':utente', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $successo = true;
        $messaggio = "Account creato con successo!";
    } catch (PDOException $e) {
        $messaggio = "Errore durante l'inserimento nel database: " . $e->getMessage();
    } finally {
        $pdo = null;
    }

} else {
    $messaggio = "Accesso non autorizzato o campi obbligatori mancanti.";
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Creazione Account – Luminora Italia</title>
  <link rel="stylesheet" href="Luminora.css">
</head>
<body>

  <div class="page-center">
    <div class="card">

      <div class="site-header">
        <img src="LuminoraIt.png" alt="Luminora Logo" class="logo-img">
        <h2><?php echo $successo ? '✅ Account Creato' : '❌ Errore Registrazione'; ?></h2>
        <div class="solar-divider"></div>
      </div>

      <?php if ($messaggio != "") { ?>
        <p class="<?php echo $successo ? 'msg-ok' : 'msg-err'; ?>"><?php echo htmlspecialchars($messaggio); ?></p>
      <?php } ?>

      <div class="centered-button">
        <a href="loginDentro.php" class="btn-back">↩️ Ritorna alla pagina di Login</a>
      </div>

    </div>
  </div>

</body>
</html>