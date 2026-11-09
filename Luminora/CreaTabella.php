<?php
require 'ConnessioneScuola.php';

try {
    $pdo = connectDB();
    $sql = "
    CREATE TABLE `Luminora` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `nomeCliente` varchar(100) NOT NULL,
        `indirizzo` varchar(150) NOT NULL,
        `modelloPannello` varchar(100) NOT NULL,
        `numeroPannelli` int(11) NOT NULL,
        `InizioContratto` date NOT NULL,
        `FineContratto` date DEFAULT NULL,
        `Royalties` int(40),
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ";
	
	$pdo->exec($sql);
    echo "Tabella <strong>Luminora</strong> creata con successo! Faccio Inserimenti<br>";
	$sql = null;
	
	$sql = "INSERT INTO `Luminora` 
	(`nomeCliente`, `indirizzo`, `modelloPannello`, `numeroPannelli`, `InizioContratto`, `FineContratto`, `Royalties`)
	VALUES
	('Mario Rossi', 'Via Roma 12, Milano', 'SunPower X22-360', 10, '2023-01-15', '2033-01-15', 4200),
	('Lucia Bianchi', 'Via Garibaldi 45, Roma', 'LG NeON 2 350W', 8, '2023-03-01', NULL, NULL),
	('Azienda Verdi Srl', 'Via Industriale 7, Torino', 'Jinko Solar Tiger 400W', 50, '2022-11-10', NULL, NULL),
	('Giovanni Ferrari', 'Corso Italia 88, Napoli', 'Canadian Solar CS3W-415', 12, '2023-06-20', NULL, NULL),
	('Hotel Sole e Mare', 'Lungomare 1, Rimini', 'SunPower X22-360', 80, '2024-01-05', NULL, NULL),
	('Cooperativa AgriSole', 'Via dei Campi 33, Bologna', 'Jinko Solar Tiger 400W', 120, '2023-09-15', NULL, NULL),
	('Paolo Marini', 'Via Dante 22, Firenze', 'LG NeON 2 350W', 6, '2024-02-28', NULL, NULL),
	('Scuola Elementare Pertini', 'Via della Scuola 5, Verona', 'Canadian Solar CS3W-415', 30, '2024-04-01', '2034-04-01', 8900)";
	$pdo->exec($sql);
	echo "Ho inserito nella tabella <strong>Luminora</strong> con successo!<br>";
	$sql = null;
	
	$sql = "
    CREATE TABLE `utenti` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `utente` varchar(100) NOT NULL,
        `password` VARCHAR(255) NOT NULL,
        PRIMARY KEY (`id`)
    ) 
    ";
	$pdo->exec($sql);
	echo "Tabella <strong>utenti</strong> creata con successo! Faccio Inserimenti<br>";
	$sql = null;
	
	$sql = "INSERT INTO utenti (utente, password) VALUES
	('alessio.neri',  'Alessio!2026'),
	('chiara.moro',   'ChiaraPass#1'),
	('davide.riva',   'DavideSecure88'),
	('elena.ferri',   'Elena_2026!'),
	('fabio.matti',   'Fabio.Strong'),
	('giulia.toni',   'GiuliaPass123'),
	('lorenzo.bini',  'LorenzoSecure!'),
	('marco.valli',   'Marco2026$'),
	('nadia.rossi',   'Nadia#Safe'),
	('paolo.costa',   'Paolo!Login'),
	('roberta.mari',  'RobertaSecure1'),
	('simone.galli',  'Simone$2026'),
	('tiziana.bore',  'TizianaPass!'),
	('valerio.novi',  'Valerio#Login'),
	('zaira.monti',   'ZairaSecure88')";
	$pdo->exec($sql);
	echo "Ho inserito nella tabella <strong>utenti</strong> con successo!<br>";
	$sql = null;
	
} catch (\PDOException $e) {
    echo "Errore durante la creazione della tabella: " . $e->getMessage() . "<br>";
} catch (\Exception $e) {
    echo "Si è verificato un errore generico: " . $e->getMessage() . "<br>";
}
$pdo = null;
?>
