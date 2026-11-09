<?php
session_start();
$logged = $_SESSION['loggedin'] ?? false;
$username = $_SESSION['username'] ?? "";

if (!$logged || $username == "") {
    header("Location: loginDentro.php");
    exit;
}
ini_set('display_errors', 'On');
error_reporting(E_ALL);
require 'ConnessioneScuola.php';

$messaggio = "";
$successo  = false;

// ── Gestione POST ──────────────────────────────────────────────
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $azione = $_POST['azione'] ?? '';
    $id     = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    try {
        $pdo = connectDB();

        if ($azione === 'update_contratto') {
            $data = $_POST['data_fine_contratto'] ?? '';
            if (!$data || !DateTime::createFromFormat('Y-m-d', $data)) {
                $messaggio = "Errore: data non valida.";
            } else {
                $stmt = $pdo->prepare("UPDATE Luminora SET FineContratto = :d WHERE id = :id");
                $stmt->execute([':d' => $data, ':id' => $id]);
                header("Location: visualizzazioneImpianti.php#riga-" . $id);
                exit;
            }

        } else if ($azione === 'update_royalties') {
            $royalties = $_POST['royalties'] ?? '';
            if ($royalties === '' || (int)$royalties < 0) {
                $messaggio = "Errore: valore royalties non valido.";
            } else {
                $stmt = $pdo->prepare("UPDATE Luminora SET Royalties = :r WHERE id = :id");
                $stmt->execute([':r' => (int)$royalties, ':id' => $id]);
                header("Location: visualizzazioneImpianti.php#riga-" . $id);
                exit;
            }

        } else if ($azione === 'delete_row') {
            $stmt = $pdo->prepare("DELETE FROM Luminora WHERE id = :id");
            $stmt->execute([':id' => $id]);
            header("Location: visualizzazioneImpianti.php");
            exit;

        } else if ($azione === 'delete_all') {
            $pdo->exec("DELETE FROM Luminora");
            header("Location: visualizzazioneImpianti.php");
            exit;

        } else {
            $messaggio = "Azione non riconosciuta.";
        }

        $pdo = null;

    } catch (PDOException $e) {
        $messaggio = "Errore database: " . $e->getMessage();
    }
}

// ── Caricamento impianti ───────────────────────────────────────
$impianti = [];
try {
    $pdo  = connectDB();
    $stmt = $pdo->prepare("SELECT id, nomeCliente, indirizzo, modelloPannello, numeroPannelli, InizioContratto, FineContratto, Royalties FROM Luminora ORDER BY id");
    $stmt->execute();
    $impianti = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $pdo = null;
} catch (PDOException $e) {
    $erroreCaricamento = "Errore durante il recupero dei dati: " . $e->getMessage();
}

$editId    = isset($_GET['edit_id'])    ? (int)$_GET['edit_id']  : 0;
$editCampo = isset($_GET['edit_campo']) ? $_GET['edit_campo']     : '';
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Elenco Impianti Luminora Italia</title>
  <link rel="stylesheet" href="Luminora.css">
</head>
<body>

  <div class="page-wrapper">

    <h2>☀️ Elenco Impianti Fotovoltaici – Luminora Italia</h2>
    <hr>

    <?php if ($messaggio != "") { ?>
      <p class="<?php echo $successo ? 'msg-ok' : 'msg-err'; ?>"><?php echo htmlspecialchars($messaggio); ?></p>
    <?php } ?>

    <?php if (isset($erroreCaricamento)) { ?>

      <p class="error"><?php echo htmlspecialchars($erroreCaricamento); ?></p>

    <?php } else if (count($impianti) === 0) { ?>

      <p style="text-align:center;">Nessun impianto trovato nella tabella.</p>

    <?php } else { ?>

      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Nome Cliente</th>
              <th>Indirizzo</th>
              <th>Modello Pannello</th>
              <th>N. Pannelli</th>
              <th>Inizio Contratto</th>
              <th>Fine Contratto</th>
              <th>Royalties (€)</th>
              <th>Elimina Impianto</th>
            </tr>
          </thead>
          <tbody>

            <?php for ($i = 0; $i < count($impianti); $i++) {
                $imp = $impianti[$i];
                $id  = (int)$imp['id'];
                $nc  = htmlspecialchars($imp['nomeCliente']);
                $ind = htmlspecialchars($imp['indirizzo']);
                $mod = htmlspecialchars($imp['modelloPannello']);
                $np  = htmlspecialchars($imp['numeroPannelli']);
                $ini = htmlspecialchars($imp['InizioContratto'] ?? '—');
                $fc  = $imp['FineContratto'];
                $roy = $imp['Royalties'];
            ?>
            <tr id="riga-<?php echo $id; ?>">
              <td><?php echo $id; ?></td>
              <td><?php echo $nc; ?></td>
              <td><?php echo $ind; ?></td>
              <td><?php echo $mod; ?></td>
              <td><?php echo $np; ?></td>
              <td><?php echo $ini; ?></td>

              <!-- Fine Contratto -->
              <td>
                <?php if ($editId === $id && $editCampo === 'contratto') { ?>
                  <form method="POST" action="visualizzazioneImpianti.php">
                    <input type="hidden" name="azione" value="update_contratto">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="date" name="data_fine_contratto" value="<?php echo htmlspecialchars($fc ?? ''); ?>" required>
                    <button type="submit" class="btn btn-save">💾 Salva</button>
                    <a href="visualizzazioneImpianti.php" class="btn btn-cancel">✖</a>
                  </form>
                <?php } else { ?>
                  <?php echo $fc ? htmlspecialchars($fc) : ''; ?>
                  <a href="visualizzazioneImpianti.php?edit_id=<?php echo $id; ?>&edit_campo=contratto#riga-<?php echo $id; ?>" class="btn btn-edit">
                    <?php echo $fc ? '✏️' : '📅 Imposta'; ?>
                  </a>
                <?php } ?>
              </td>

              <!-- Royalties -->
              <td>
                <?php if ($editId === $id && $editCampo === 'royalties') { ?>
                  <form method="POST" action="visualizzazioneImpianti.php">
                    <input type="hidden" name="azione" value="update_royalties">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    € <input type="number" name="royalties" value="<?php echo htmlspecialchars($roy ?? ''); ?>" min="0" style="width:90px" required>
                    <button type="submit" class="btn btn-save">💾 Salva</button>
                    <a href="visualizzazioneImpianti.php" class="btn btn-cancel">✖</a>
                  </form>
                <?php } else { ?>
                  <?php echo $roy !== null ? '€ ' . htmlspecialchars($roy) : ''; ?>
                  <a href="visualizzazioneImpianti.php?edit_id=<?php echo $id; ?>&edit_campo=royalties#riga-<?php echo $id; ?>" class="btn btn-edit">
                    <?php echo $roy !== null ? '✏️' : '💰 Imposta'; ?>
                  </a>
                <?php } ?>
              </td>

              <!-- Elimina -->
              <td>
                <form method="POST" action="visualizzazioneImpianti.php"
                      onsubmit="return confirm('Sei sicuro di voler eliminare l\'impianto di <?php echo addslashes($nc); ?>?')">
                  <input type="hidden" name="azione" value="delete_row">
                  <input type="hidden" name="id" value="<?php echo $id; ?>">
                  <button type="submit" class="btn btn-delete">🗑️ Elimina</button>
                </form>
              </td>
            </tr>

            <?php } ?>

          </tbody>
        </table>
      </div>

      <!-- Elimina tutti -->
      <div class="delete-all-wrap">
        <form method="POST" action="visualizzazioneImpianti.php"
              onsubmit="return confirm('⚠️ ATTENZIONE: eliminare TUTTI gli impianti? Operazione irreversibile!')">
          <input type="hidden" name="azione" value="delete_all">
          <button type="submit" class="btn-danger-all">⚠️ ELIMINA TUTTI GLI IMPIANTI</button>
        </form>
      </div>

    <?php } ?>

    <div class="centered-button">
      <a href="PaginaPrincipaleLuminora.php" class="btn-back">↩️ Torna al Menu</a>
    </div>

  </div>

</body>
</html>