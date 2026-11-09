# Luminora Italia

Web application for managing photovoltaic installations / Applicazione web per la gestione di impianti fotovoltaici.

**Languages / Lingue:** [English](#english) | [Italiano](#italiano)

---

<a id="english"></a>

# English

## Table of contents

1. [Overview](#overview)
2. [Features](#features)
3. [Technology stack](#technology-stack)
4. [Project structure](#project-structure)
5. [Application flow](#application-flow)
6. [Database schema](#database-schema)
7. [Installation](#installation)
8. [Usage](#usage)
9. [Front-end and styling](#front-end-and-styling)
10. [Security notes and known limitations](#security-notes-and-known-limitations)
11. [Roadmap](#roadmap)
12. [Glossary](#glossary)

## Overview

Luminora Italia is a back-office application for a company that installs photovoltaic systems. Authenticated users can register customers' installations, record contract dates and annual royalties, and edit or delete records. The application is written in plain PHP with PDO and uses a MySQL/MariaDB database. It has no external PHP dependencies and no build step.

## Features

**Authentication and sessions**
- Login with username and password, with an error message on invalid credentials.
- Logout, which destroys the session.
- Account registration.
- Every management page checks the session and redirects unauthenticated users to the login page. The login page redirects already authenticated users to the main menu.

**Plant management**
- Registration of a new plant with: customer name, installation address, panel model, number of panels, contract start date, contract end date (optional) and annual royalties (optional).
- List of all plants in a table, ordered by ID.
- Inline editing of the contract end date and of the royalties directly in the list, with save and cancel actions.
- Deletion of a single plant from the list, with a confirmation prompt.
- Deletion of all plants from the list, with a confirmation prompt.
- Stand-alone pages to update the contract end date or the royalties of a plant by entering its ID.
- Stand-alone page to delete a plant by ID. Entering `0` deletes all plants.

**Interface**
- Light and dark theme, selected from the main menu and stored in a cookie named `tema` for 30 days.
- Visit log: the main menu displays the date and the IP address recorded at the previous visit and the name of the logged-in user.
- Responsive layout (breakpoint at 600 px).

## Technology stack

| Layer | Technology |
|-------|------------|
| Back end | PHP 7 or later (PDO, native sessions, cookies) |
| Database | MySQL / MariaDB (InnoDB, `utf8mb4`) |
| Front end | HTML5, CSS3 (`Luminora.css`), Google Fonts (Syne, DM Sans) |

The connection layer uses PDO with exceptions enabled (`ERRMODE_EXCEPTION`), associative fetch mode, native prepared statements (`ATTR_EMULATE_PREPARES` disabled) and the `utf8mb4` charset. The default time zone of the main menu is `Europe/Rome`.

## Project structure

The project is flat: all files live in the same directory.

| File | Role |
|------|------|
| `CreaTabella.php` | Creates the `Luminora` and `utenti` tables and fills them with sample data |
| `ConnessioneScuola.php` | Database connection, exposes `connectDB()` |
| `loginDentro.php` | Login page |
| `DaEntrare.php` | Verifies the credentials against the database and starts the session |
| `esci.php` | Logout |
| `CreaAccount.php` | Account registration form |
| `PaginaCreaAccount.php` | Stores the new account in the database |
| `PaginaPrincipaleLuminora.php` | Main menu, entry point of the project, theme switch and visit log |
| `visualizzazioneImpianti.php` | Plant list with inline editing of contract end date and royalties, and deletion |
| `InserimentoImpianto.php` | Form to register a plant |
| `PaginaLuminoraInserimento.php` | Inserts the plant into the database |
| `CambiamentoContratto.php` | Form to change a contract end date |
| `PaginaLuminoraContratto.php` | Updates the contract end date in the database |
| `CambiamentoRoyalties.php` | Form to change royalties |
| `PaginaLuminoraRoyalties.php` | Updates the royalties in the database |
| `CancellazioneTabella.php` | Form to delete one plant, or all plants with ID `0` |
| `PaginaLuminoraCancellazione.php` | Performs the deletion in the database |
| `Luminora.css` | Style sheet shared by all pages |
| `LuminoraIt.png` | Logo and favicon (referenced by the pages) |
| `Luminora.png` | Page background image (referenced by `Luminora.css`) |

Both image files are referenced by the code and must be present in the same directory as the PHP files.

## Application flow

Most operations follow a two-step pattern: a page with a form sends a `POST` request to a processing page, which validates the input, runs the query and shows the outcome with a link back to the menu.

| Operation | Form page | Processing page |
|-----------|-----------|-----------------|
| Login | `loginDentro.php` | `DaEntrare.php` |
| Registration | `CreaAccount.php` | `PaginaCreaAccount.php` |
| Add plant | `InserimentoImpianto.php` | `PaginaLuminoraInserimento.php` |
| Change contract end | `CambiamentoContratto.php` | `PaginaLuminoraContratto.php` |
| Change royalties | `CambiamentoRoyalties.php` | `PaginaLuminoraRoyalties.php` |
| Delete | `CancellazioneTabella.php` | `PaginaLuminoraCancellazione.php` |

`visualizzazioneImpianti.php` handles its own `POST` requests (actions `update_contratto`, `update_royalties`, `delete_row` and `delete_all`). Inline editing is driven by the query-string parameters `edit_id` and `edit_campo` (`contratto` or `royalties`). After a successful update the page redirects to itself and anchors on the edited row.

Validation and data handling:
- All SQL statements use PDO prepared statements.
- Text fields are trimmed and escaped with `htmlspecialchars()` before storage in the insert and registration pages, and output is escaped when displayed.
- Dates must match the `YYYY-MM-DD` format.
- Numeric fields (ID, number of panels, royalties) are cast to integers. Royalties cannot be negative.
- Required fields: customer name, address, panel model, number of panels and contract start date.

## Database schema

**Table `Luminora`**

| Column | Type | Constraints |
|--------|------|-------------|
| `id` | INT(11) | Primary key, auto increment |
| `nomeCliente` | VARCHAR(100) | Not null |
| `indirizzo` | VARCHAR(150) | Not null |
| `modelloPannello` | VARCHAR(100) | Not null |
| `numeroPannelli` | INT(11) | Not null |
| `InizioContratto` | DATE | Not null |
| `FineContratto` | DATE | Null allowed |
| `Royalties` | INT(40) | Null allowed, annual amount in euro |

**Table `utenti`**

| Column | Type | Constraints |
|--------|------|-------------|
| `id` | INT(11) | Primary key, auto increment |
| `utente` | VARCHAR(100) | Not null |
| `password` | VARCHAR(255) | Not null |

**Table `visite`** is created automatically by the main menu if it does not exist.

| Column | Type | Constraints |
|--------|------|-------------|
| `id` | INT | Primary key, auto increment |
| `data` | DATETIME | |
| `ip` | VARCHAR(45) | |

`CreaTabella.php` inserts 8 sample plants (two of them with contract end date and royalties already set) and 15 sample user accounts.

## Installation

**Requirements**
- A web server with PHP 7 or later and the PDO MySQL extension enabled (for example Apache with PHP, XAMPP, WAMP or MAMP).
- A MySQL or MariaDB server and a database with a user that can create tables.

**Steps**

1. Copy all project files, including `LuminoraIt.png` and `Luminora.png`, into a directory served by the web server.
2. Open `ConnessioneScuola.php`. The function `connectDB()` supports two environments, selected by the variable `$ambiente`:
   - `casa` (home)
   - `scuola` (school)

   Set `$ambiente` to the environment you want and enter the host, database name, user and password for it. Any other value stops the script with the message "Ambiente non riconosciuto".
3. Open `CreaTabella.php` once in the browser. It creates the `Luminora` and `utenti` tables and inserts the sample data. Run it only once: a second run fails because the tables already exist.
4. Open `loginDentro.php`, sign in with one of the accounts inserted by `CreaTabella.php`, or create a new account with the registration link.

Do not commit real database credentials to a public repository. Keep `ConnessioneScuola.php` free of production secrets, or load them from a file that is excluded from version control.

## Usage

1. Sign in from `loginDentro.php`. The main menu opens.
2. The main menu offers: add a plant, view all plants, change a contract end date, update royalties, delete a plant, and log out.
3. In the plant list, the edit control next to the contract end date or the royalties turns the cell into a form. Save to apply the change or use the cancel control to discard it. A plant without a value shows a button to set it.
4. Use the theme button in the top-right corner of the main menu to switch between light and dark mode.
5. Use the logout entry to end the session.

## Front-end and styling

`Luminora.css` is the single style sheet for the whole project. It is organised in these sections: design variables, reset and base styles, page layout, header and logo, solar divider, cards, forms, buttons (primary, sun, danger, logout, small edit/save/cancel/delete), messages, tables, dark theme, menu grid, visit widget, theme toggle, entry animation, delete-all button, back button, form page container, responsive rules and utilities.

- The visual identity is based on a solar palette: gold and amber accents (`--sol-oro`, `--sol-ambra`) on dark green tones (`--verde-scuro`, `--verde-medio`, `--verde-vivo`).
- Colours, shadows, radii, fonts and the transition are defined as CSS custom properties in `:root`.
- The dark theme is activated by the class `dark` on the `body` element, set by the PHP code from the `tema` cookie.
- Fonts are loaded from Google Fonts at runtime, so the pages need internet access to display them as designed; otherwise the generic `sans-serif` fallback is used.

## Security notes and known limitations

This project was developed as a learning project and is not hardened for production use. Known limitations:

- Passwords are stored and compared in plain text. Before any real deployment, use `password_hash()` and `password_verify()`; the `VARCHAR(255)` column is already large enough for the hashes.
- Account registration is open to everyone and does not check for duplicate usernames.
- Database credentials are written in `ConnessioneScuola.php`.
- `display_errors` is enabled in several pages and database error messages are shown to the user.
- Destructive operations (single and bulk delete) are not protected against CSRF.
- The visit log is global rather than per user, and the IP address is stored in full.
- The session identifier is not regenerated after login.
- There is no role model: every authenticated user can modify and delete every record.

## Roadmap

- `StatisticheLuminora.php`: page with charts and monetary summaries.
- `GeneraReport.php`: generation of downloadable reports (Excel and PDF), to be linked from the plant list in `visualizzazioneImpianti.php`.
- Dropdown navigation menu.
- Completion of the style sheet.

## Glossary

**Royalties**: sums of money that the owner of a large solar installation, typically a ground-mounted photovoltaic park, has to pay to local authorities (municipalities or regions) as rent or compensation for the use of the land. In the application the value is stored per plant as an annual amount in euro.

---

<a id="italiano"></a>

# Italiano

## Indice

1. [Panoramica](#panoramica)
2. [Funzionalità](#funzionalità)
3. [Tecnologie](#tecnologie)
4. [Struttura del progetto](#struttura-del-progetto)
5. [Flusso dell'applicazione](#flusso-dellapplicazione)
6. [Schema del database](#schema-del-database)
7. [Installazione](#installazione)
8. [Utilizzo](#utilizzo)
9. [Front-end e grafica](#front-end-e-grafica)
10. [Note di sicurezza e limiti noti](#note-di-sicurezza-e-limiti-noti)
11. [Sviluppi futuri](#sviluppi-futuri)
12. [Glossario](#glossario)

## Panoramica

Luminora Italia è un gestionale per un'azienda che installa impianti fotovoltaici. Gli utenti autenticati possono registrare gli impianti dei clienti, annotare le date di contratto e le royalties annuali, modificare o cancellare i record. L'applicazione è scritta in PHP puro con PDO e usa un database MySQL/MariaDB. Non ha dipendenze PHP esterne né fasi di compilazione.

## Funzionalità

**Autenticazione e sessioni**
- Login con nome utente e password, con messaggio di errore in caso di credenziali non valide.
- Logout, che distrugge la sessione.
- Registrazione di un account.
- Ogni pagina di gestione controlla la sessione e reindirizza al login gli utenti non autenticati. La pagina di login reindirizza al menu principale chi è già autenticato.

**Gestione degli impianti**
- Registrazione di un nuovo impianto con: nome del cliente, indirizzo di installazione, modello del pannello, numero di pannelli, data di inizio contratto, data di fine contratto (facoltativa) e royalties annuali (facoltative).
- Elenco di tutti gli impianti in una tabella, ordinata per ID.
- Modifica diretta della fine contratto e delle royalties nell'elenco, con azioni di salvataggio e annullamento.
- Eliminazione di un singolo impianto dall'elenco, con richiesta di conferma.
- Eliminazione di tutti gli impianti dall'elenco, con richiesta di conferma.
- Pagine dedicate per aggiornare la fine contratto o le royalties di un impianto indicandone l'ID.
- Pagina dedicata per cancellare un impianto tramite ID. Inserendo `0` vengono cancellati tutti gli impianti.

**Interfaccia**
- Tema chiaro e scuro, selezionabile dal menu principale e memorizzato in un cookie chiamato `tema` per 30 giorni.
- Registro delle visite: il menu principale mostra data e indirizzo IP registrati alla visita precedente e il nome dell'utente connesso.
- Layout responsive (breakpoint a 600 px).

## Tecnologie

| Livello | Tecnologia |
|---------|------------|
| Back end | PHP 7 o successivo (PDO, sessioni native, cookie) |
| Database | MySQL / MariaDB (InnoDB, `utf8mb4`) |
| Front end | HTML5, CSS3 (`Luminora.css`), Google Fonts (Syne, DM Sans) |

Il livello di connessione usa PDO con le eccezioni attivate (`ERRMODE_EXCEPTION`), fetch associativo, prepared statement nativi (`ATTR_EMULATE_PREPARES` disattivato) e charset `utf8mb4`. Il fuso orario predefinito del menu principale è `Europe/Rome`.

## Struttura del progetto

Il progetto è piatto: tutti i file si trovano nella stessa cartella.

| File | Ruolo |
|------|-------|
| `CreaTabella.php` | Crea le tabelle `Luminora` e `utenti` e le riempie con dati di esempio |
| `ConnessioneScuola.php` | Connessione al database, espone `connectDB()` |
| `loginDentro.php` | Pagina di login |
| `DaEntrare.php` | Verifica le credenziali nel database e avvia la sessione |
| `esci.php` | Logout |
| `CreaAccount.php` | Form di registrazione account |
| `PaginaCreaAccount.php` | Salva il nuovo account nel database |
| `PaginaPrincipaleLuminora.php` | Menu principale, punto di ingresso del progetto, cambio tema e registro visite |
| `visualizzazioneImpianti.php` | Elenco impianti con modifica diretta di fine contratto e royalties, ed eliminazione |
| `InserimentoImpianto.php` | Form di registrazione di un impianto |
| `PaginaLuminoraInserimento.php` | Inserisce l'impianto nel database |
| `CambiamentoContratto.php` | Form per cambiare la fine contratto |
| `PaginaLuminoraContratto.php` | Aggiorna la fine contratto nel database |
| `CambiamentoRoyalties.php` | Form per cambiare le royalties |
| `PaginaLuminoraRoyalties.php` | Aggiorna le royalties nel database |
| `CancellazioneTabella.php` | Form per cancellare un impianto, o tutti con ID `0` |
| `PaginaLuminoraCancellazione.php` | Esegue la cancellazione nel database |
| `Luminora.css` | Foglio di stile condiviso da tutte le pagine |
| `LuminoraIt.png` | Logo e favicon (richiamati dalle pagine) |
| `Luminora.png` | Immagine di sfondo delle pagine (richiamata da `Luminora.css`) |

Entrambe le immagini sono richiamate dal codice e devono trovarsi nella stessa cartella dei file PHP.

## Flusso dell'applicazione

La maggior parte delle operazioni segue uno schema in due passi: una pagina con un form invia una richiesta `POST` a una pagina di elaborazione, che valida i dati, esegue la query e mostra l'esito con un collegamento di ritorno al menu.

| Operazione | Pagina con form | Pagina di elaborazione |
|------------|-----------------|------------------------|
| Login | `loginDentro.php` | `DaEntrare.php` |
| Registrazione | `CreaAccount.php` | `PaginaCreaAccount.php` |
| Inserimento impianto | `InserimentoImpianto.php` | `PaginaLuminoraInserimento.php` |
| Cambio fine contratto | `CambiamentoContratto.php` | `PaginaLuminoraContratto.php` |
| Cambio royalties | `CambiamentoRoyalties.php` | `PaginaLuminoraRoyalties.php` |
| Cancellazione | `CancellazioneTabella.php` | `PaginaLuminoraCancellazione.php` |

`visualizzazioneImpianti.php` gestisce autonomamente le proprie richieste `POST` (azioni `update_contratto`, `update_royalties`, `delete_row` e `delete_all`). La modifica diretta è guidata dai parametri di query `edit_id` e `edit_campo` (`contratto` oppure `royalties`). Dopo un aggiornamento riuscito la pagina si ricarica e si posiziona sulla riga modificata.

Validazione e gestione dei dati:
- Tutte le istruzioni SQL usano prepared statement PDO.
- I campi di testo vengono ripuliti con `trim()` ed escapati con `htmlspecialchars()` prima del salvataggio nelle pagine di inserimento e registrazione, e l'output viene escapato in visualizzazione.
- Le date devono rispettare il formato `AAAA-MM-GG`.
- I campi numerici (ID, numero di pannelli, royalties) vengono convertiti in interi. Le royalties non possono essere negative.
- Campi obbligatori: nome cliente, indirizzo, modello del pannello, numero di pannelli e data di inizio contratto.

## Schema del database

**Tabella `Luminora`**

| Colonna | Tipo | Vincoli |
|---------|------|---------|
| `id` | INT(11) | Chiave primaria, auto increment |
| `nomeCliente` | VARCHAR(100) | Not null |
| `indirizzo` | VARCHAR(150) | Not null |
| `modelloPannello` | VARCHAR(100) | Not null |
| `numeroPannelli` | INT(11) | Not null |
| `InizioContratto` | DATE | Not null |
| `FineContratto` | DATE | Consente NULL |
| `Royalties` | INT(40) | Consente NULL, importo annuale in euro |

**Tabella `utenti`**

| Colonna | Tipo | Vincoli |
|---------|------|---------|
| `id` | INT(11) | Chiave primaria, auto increment |
| `utente` | VARCHAR(100) | Not null |
| `password` | VARCHAR(255) | Not null |

**Tabella `visite`** viene creata automaticamente dal menu principale se non esiste.

| Colonna | Tipo | Vincoli |
|---------|------|---------|
| `id` | INT | Chiave primaria, auto increment |
| `data` | DATETIME | |
| `ip` | VARCHAR(45) | |

`CreaTabella.php` inserisce 8 impianti di esempio (due dei quali con fine contratto e royalties già valorizzate) e 15 account utente di esempio.

## Installazione

**Requisiti**
- Un web server con PHP 7 o successivo e l'estensione PDO MySQL attiva (ad esempio Apache con PHP, XAMPP, WAMP o MAMP).
- Un server MySQL o MariaDB e un database con un utente che possa creare tabelle.

**Procedura**

1. Copia tutti i file del progetto, comprese `LuminoraIt.png` e `Luminora.png`, in una cartella servita dal web server.
2. Apri `ConnessioneScuola.php`. La funzione `connectDB()` supporta due ambienti, scelti con la variabile `$ambiente`:
   - `casa`
   - `scuola`

   Imposta `$ambiente` sull'ambiente desiderato e inserisci host, nome del database, utente e password corrispondenti. Qualsiasi altro valore interrompe lo script con il messaggio "Ambiente non riconosciuto".
3. Apri una sola volta `CreaTabella.php` dal browser. Crea le tabelle `Luminora` e `utenti` e inserisce i dati di esempio. Eseguilo una sola volta: una seconda esecuzione fallisce perché le tabelle esistono già.
4. Apri `loginDentro.php` ed entra con uno degli account inseriti da `CreaTabella.php`, oppure crea un nuovo account dal link di registrazione.

Non pubblicare credenziali reali del database in un repository pubblico. Tieni `ConnessioneScuola.php` privo di segreti di produzione, oppure caricali da un file escluso dal controllo di versione.

## Utilizzo

1. Accedi da `loginDentro.php`. Si apre il menu principale.
2. Il menu principale offre: inserimento di un impianto, visualizzazione di tutti gli impianti, cambio della fine contratto, aggiornamento delle royalties, cancellazione di un impianto e logout.
3. Nell'elenco impianti, il pulsante di modifica accanto a fine contratto o royalties trasforma la cella in un form. Salva per applicare la modifica oppure usa il pulsante di annullamento per scartarla. Per un impianto privo di valore compare un pulsante per impostarlo.
4. Usa il pulsante del tema in alto a destra nel menu principale per passare dalla modalità chiara a quella scura.
5. Usa la voce di logout per terminare la sessione.

## Front-end e grafica

`Luminora.css` è l'unico foglio di stile del progetto. È organizzato nelle sezioni: variabili di design, reset e base, layout di pagina, header e logo, divisore solare, card, form, bottoni (primario, sole, pericolo, logout, piccoli modifica/salva/annulla/elimina), messaggi, tabelle, tema scuro, griglia del menu, widget visita, toggle del tema, animazione d'ingresso, bottone elimina tutto, bottone di ritorno, contenitore delle pagine form, regole responsive e utilità.

- L'identità visiva si basa su una palette solare: accenti oro e ambra (`--sol-oro`, `--sol-ambra`) su toni di verde scuro (`--verde-scuro`, `--verde-medio`, `--verde-vivo`).
- Colori, ombre, raggi, font e transizione sono definiti come variabili CSS in `:root`.
- Il tema scuro si attiva con la classe `dark` sull'elemento `body`, impostata dal codice PHP a partire dal cookie `tema`.
- I font vengono caricati da Google Fonts a runtime: serve quindi una connessione a internet per vederli come progettato, altrimenti viene usato il `sans-serif` generico.

## Note di sicurezza e limiti noti

Il progetto è stato sviluppato a scopo didattico e non è predisposto per un uso in produzione. Limiti noti:

- Le password sono salvate e confrontate in chiaro. Prima di qualsiasi uso reale occorre usare `password_hash()` e `password_verify()`; la colonna `VARCHAR(255)` è già adatta.
- La registrazione è aperta a chiunque e non controlla i nomi utente duplicati.
- Le credenziali del database sono scritte in `ConnessioneScuola.php`.
- `display_errors` è attivo in diverse pagine e i messaggi di errore del database vengono mostrati all'utente.
- Le operazioni distruttive (eliminazione singola e totale) non sono protette da CSRF.
- Il registro visite è globale e non per utente, e l'indirizzo IP viene salvato per intero.
- L'identificatore di sessione non viene rigenerato dopo il login.
- Non esiste un modello di ruoli: ogni utente autenticato può modificare ed eliminare qualsiasi record.

## Sviluppi futuri

- `StatisticheLuminora.php`: pagina con grafici e riepiloghi monetari.
- `GeneraReport.php`: generazione di report scaricabili (Excel e PDF), da collegare all'elenco impianti in `visualizzazioneImpianti.php`.
- Menu di navigazione a tendina.
- Completamento del foglio di stile.

## Glossario

**Royalties**: somme di denaro che il proprietario di un grande impianto solare, solitamente un parco fotovoltaico a terra, deve pagare agli enti locali (Comuni o Regioni) come affitto o ricompensa per l'uso del territorio. Nell'applicazione il valore è memorizzato per ogni impianto come importo annuale in euro.
