<?php
require 'ConnessioneScuola.php';
session_start();

$username = $_POST['username'] ?? "";
$password = $_POST['password'] ?? "";

try {
    $pdo = connectDB();
    $sql = "SELECT utente, password FROM utenti WHERE utente = :utente LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':utente', $username);
    $stmt->execute();
    $utente = $stmt->fetch(PDO::FETCH_ASSOC);
    $pdo = null;

    if ($utente && $password === $utente['password']) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: PaginaPrincipaleLuminora.php");
        exit;
    }

} catch (PDOException $e) {
    // In caso di errore DB mandiamo comunque al login
}

header("Location: loginDentro.php?error=1");
exit;
?>