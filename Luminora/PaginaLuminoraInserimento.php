<?php
session_start();
$logged = $_SESSION['loggedin'] ?? false;
$username = $_SESSION['username'] ?? "";

if (!$logged || $username == "") {
    header("Location: loginDentro.php");
    exit;
}
require 'ConnessioneScuola.php';

$messaggio = "";
$successo = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" &&
    !empty($_POST['nomeCliente']) &&
    !empty($_POST['indirizzo']) &&
    !empty($_POST['modelloPannello']) &&
    !empty($_POST['numeroPannelli']) &&
    !empty($_POST['InizioContratto'])) {

    $nomeCliente     = htmlspecialchars(trim($_POST['nomeCliente']));
    $indirizzo       = htmlspecialchars(trim($_POST['indirizzo']));
    $modelloPannello = htmlspecialchars(trim($_POST['modelloPannello']));
    $numeroPannelli  = (int) $_POST['numeroPannelli'];
    $InizioContratto = $_POST['InizioContratto'];
    $FineContratto   = !empty($_POST['FineContratto']) ? $_POST['FineContratto'] : NULL;
    $Royalties       = isset($_POST['Royalties']) && $_POST['Royalties'] !== '' ? (int) $_POST['Royalties'] : NULL;

    if (!DateTime::createFromFormat('Y-m-d', $InizioContratto)) {
        $messaggio = "Errore: La data di inizio contratto non è in un formato valido.";
    } else {
        try {
            $pdo = connectDB();
            $sql = "INSERT INTO Luminora (nomeCliente, indirizzo, modelloPannello, numeroPannelli, InizioContratto, FineContratto, Royalties)
                    VALUES (:nomeCliente, :indirizzo, :modelloPannello, :numeroPannelli, :InizioContratto, :FineContratto, :Royalties)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nomeCliente',     $nomeCliente);
            $stmt->bindParam(':indirizzo',        $indirizzo);
            $stmt->bindParam(':modelloPannello',  $modelloPannello);
            $stmt->bindParam(':numeroPannelli',   $numeroPannelli,  PDO::PARAM_INT);
            $stmt->bindParam(':InizioContratto',  $InizioContratto);
            $stmt->bindParam(':FineContratto',    $FineContratto);
            $stmt->bindParam(':Royalties',        $Royalties);
            $stmt->execute();
            $successo = true;
            $messaggio = "Impianto fotovoltaico registrato con successo!";
        } catch (PDOException $e) {
            $messaggio = "Errore durante l'inserimento nel database: " . $e->getMessage();
        } finally {
            $pdo = null;
        }
    }
} else {
    $messaggio = "Accesso non autorizzato o campi obbligatori mancanti.";
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Inserimento Impianto – Luminora Italia</title>
  <link rel="stylesheet" href="Luminora.css">
</head>
<body>

  <div class="page-center">
    <div class="card">

      <div class="site-header">
        <img src="LuminoraIt.png" alt="Luminora Logo" class="logo-img">
        <h2><?php echo $successo ? '✅ Impianto Inserito' : '❌ Errore Inserimento'; ?></h2>
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