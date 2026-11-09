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
<title>Inserisci Nuovo Impianto</title>
<link rel="stylesheet" href="Luminora.css">
</head>
<body class="form-page"> <div class="card"> <div class="site-header">
    <img src="LuminoraIt.png" alt="Luminora Logo" class="logo-img" style="width: 80px; height: auto;" />
    <h2>☀️ Nuovo Impianto</h2>
    <div class="solar-divider"></div> </div>

  <p class="subtitle">Inserisci i dati tecnici per registrare un nuovo impianto nel gestionale.</p>

  <form action="PaginaLuminoraInserimento.php" method="POST">
    
    <div class="form-group">
      <label for="nomeCliente">Nome del Cliente *</label>
      <input type="text" id="nomeCliente" name="nomeCliente" maxlength="100" required placeholder="Nome e Cognome">
    </div>

    <div class="form-group">
      <label for="indirizzo">Indirizzo Installazione *</label>
      <input type="text" id="indirizzo" name="indirizzo" maxlength="150" required placeholder="Via, Città, CAP">
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
      <div class="form-group">
        <label for="modelloPannello">Modello Pannello *</label>
        <input type="text" id="modelloPannello" name="modelloPannello" maxlength="100" required>
      </div>
      <div class="form-group">
        <label for="numeroPannelli">N° Pannelli *</label>
        <input type="number" id="numeroPannelli" name="numeroPannelli" min="1" required>
      </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
      <div class="form-group">
        <label for="InizioContratto">Inizio Contratto *</label>
        <input type="date" id="InizioContratto" name="InizioContratto" required>
      </div>
      <div class="form-group">
        <label for="FineContratto">Fine Contratto</label>
        <input type="date" id="FineContratto" name="FineContratto">
      </div>
    </div>

    <div class="form-group">
      <label for="Royalties">Royalties annuali (€)</label>
      <input type="number" id="Royalties" name="Royalties" min="0" placeholder="0.00">
    </div>

    <button type="submit">✅ Registra Impianto</button>
  </form>

  <div class="centered-button">
    <a href="PaginaPrincipaleLuminora.php" class="btn-back">↩️ Ritorna al menu</a>
  </div>
</div>

</body>
</html>
