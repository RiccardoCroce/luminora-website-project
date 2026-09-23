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
<title>Cambiamento Fine Contratto</title>
<link rel="stylesheet" href="Luminora.css">
</head>
<body class="form-page"> <div class="card"> <div class="site-header">
    <img src="LuminoraIt.png" alt="Logo" class="logo-img" style="width: 80px;">
    <h2>📅 Aggiornamento Fine Contratto</h2>
    <div class="solar-divider"></div>
  </div>

  <p class="subtitle">Inserisci l'ID dell'impianto e la nuova data di fine contratto.</p>

  <form action="PaginaLuminoraContratto.php" method="POST">
    <div class="form-group">
      <label for="id">ID Impianto *</label>
      <input type="number" id="id" name="id" required min="1" placeholder="Es. 101">
    </div>

    <div class="form-group">
      <label for="data_fine_contratto">Nuova Data Fine Contratto *</label>
      <input type="date" id="data_fine_contratto" name="data_fine_contratto" required>
    </div>

    <button type="submit">📅 Aggiorna Fine Contratto</button>
  </form>

  <div class="centered-button">
    <a href="PaginaPrincipaleLuminora.php" class="btn-back">↩️ Torna al Menu</a>
  </div>
</div>

</body>
</html>