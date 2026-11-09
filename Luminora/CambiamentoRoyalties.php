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
<title>Aggiornamento Royalties</title>
<link rel="stylesheet" href="Luminora.css"></head>
<body class="form-page">

<div class="card">
  <div class="site-header">
    <img src="LuminoraIt.png" alt="Logo" class="logo-img" style="width: 80px;">
    <h2>💰 Aggiornamento Royalties</h2>
    <div class="solar-divider"></div>
  </div>

  <p class="subtitle">Inserisci l'ID dell'impianto e il valore delle royalties annuali in €.</p>

  <form action="PaginaLuminoraRoyalties.php" method="POST">
    <div class="form-group">
      <label for="id">ID Impianto *</label>
      <input type="number" id="id" name="id" required min="1">
    </div>

    <div class="form-group">
      <label for="royalties">Royalties annuali (€) *</label>
      <input type="number" id="royalties" name="royalties" required min="0">
    </div>

    <button type="submit">💰 Aggiorna Royalties</button>
  </form>

  <div class="centered-button">
    <a href="PaginaPrincipaleLuminora.php" class="btn-back">↩️ Torna al Menu</a>
  </div>
</div>

</body>
</html>
