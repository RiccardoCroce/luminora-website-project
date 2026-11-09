<?php
function connectDB() {
	$ambiente = 'casa';
	if ($ambiente === 'casa') {
		$host = '192.168.56.1';
		$db   = 'sys';
		$user = 'root';
		$pass = 'prova';
		$charset = 'utf8mb4';
	} elseif ($ambiente === 'scuola') {
		$host = '172.16.1.99';
		$db   = 'db20213';
		$user = 'ut20213';
		$pass = 'pw20213';
		$charset = 'utf8mb4';
	} else {
		die('Ambiente non riconosciuto');
	}
    
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        return $pdo;
    } catch (PDOException $e) {
        die("Errore di connessione al database: " . $e->getMessage());
    }
}
?>
