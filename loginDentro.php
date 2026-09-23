<?php
session_start();
$logged = $_SESSION['loggedin'] ?? false;
$username = $_SESSION['username'] ?? "";

if ($logged && $username != "") {
    header("Location: PaginaPrincipaleLuminora.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login - Luminora Italia</title>
    <link rel="stylesheet" href="Luminora.css">
</head>
<body class="form-page"> <div class="card">
        <div class="site-header">
            <img src="LuminoraIt.png" alt="Luminora Logo" class="logo-img" style="width: 100px; height: auto;">
            <h2>☀️ Accedi a Luminora!</h2>
            <div class="solar-divider"></div>
        </div>
        <?php
        $err = $_GET['error'] ?? -1;
        if ($err == 1) {
            echo '<div class="msg msg-err" style="margin-bottom: 20px;">
                    ❌ Credenziali non valide. Riprova.
                  </div>';
        }
        ?>

        <form action="DaEntrare.php" method="POST">
            <div class="form-group">
                <label for="username">Nome Utente</label>
                <input type="text" id="username" name="username" required placeholder="Inserisci username">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>

            <button type="submit" style="width: 100%;">Accedi</button>
        </form>

        <div style="margin-top: 25px; text-align: center; border-top: 1px solid var(--grigio-mid); padding-top: 20px;">
            <p style="font-size: 0.9em; color: var(--testo-medio);">
                Non hai un account? <a href="CreaAccount.php" style="color: var(--verde-medio); font-weight: bold; text-decoration: none;">Registrati qui</a>
            </p>
        </div>
    </div>

</body>
</html>