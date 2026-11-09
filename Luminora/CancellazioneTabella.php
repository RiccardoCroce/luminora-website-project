<?php
session_start();
$logged = $_SESSION['loggedin'] ?? false;
$username = $_SESSION['username'] ?? "";

if (!$logged || $username == "") {
    header("Location: loginDentro.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8" />
<title>Cancellazione Impianto</title>
<link rel="stylesheet" href="Luminora.css">
</head>
<body class="form-page">

<div class="card" style="border-top: 4px solid var(--errore);"> <div class="site-header">
    <img src="LuminoraIt.png" alt="Logo" class="logo-img" style="width: 80px;">
    <h2 style="color: var(--errore);">🗑️ Cancellazione Impianto</h2>
    <div class="solar-divider" style="background: var(--errore);"></div>
  </div>

  <div class="msg msg-err">
    ⚠️ Inserisci <strong>0</strong> per cancellare TUTTI gli impianti.
  </div>

  <form action="PaginaLuminoraCancellazione.php" method="POST">
    <div class="form-group">
      <label for="id">ID dell'impianto da eliminare *</label>
      <input type="number" id="id" name="id" required min="0">
    </div>

    <button type="submit" class="btn-danger">🗑️ Conferma Cancellazione</button>
  </form>

  <div class="centered-button">
    <a href="PaginaPrincipaleLuminora.php" class="btn-back">↩️ Annulla e Ritorna</a>
  </div>
</div>

</body>
</html>