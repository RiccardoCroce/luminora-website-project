<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8" />
  <title>Creazione Account</title>
  <link rel="stylesheet" href="Luminora.css">
</head>
<body>

  <div class="page-center">
    <header class="site-header">
      <span class="logo-sole">☀️</span>
      <h1 class="site-title">Luminora</h1>
    </header>

    <h2>🚻 Creazione Account</h2>
    <hr>

    <div class="card">
      <p class="subtitle">Inserisci nome utente e password</p>

      <form action="PaginaCreaAccount.php" method="POST">

        <div class="form-group">
          <label for="username">Nome Utente</label>
          <input type="text" id="username" name="username" placeholder="es. mario.rossi" required>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="••••••••" required>
        </div>

        <button type="submit">Creazione Account</button>

      </form>
    </div>

    <div class="mt-16 text-center">
      <a href="loginDentro.php" class="btn-back">↩️ Ritorna alla pagina di Login</a>
    </div>
  </div>

</body>
</html>