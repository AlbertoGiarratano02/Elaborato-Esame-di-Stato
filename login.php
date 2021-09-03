<?php
	// il codice della pagina è come già visto suddiviso in due parti
	// se non sono settati i campi dell'array POST si visualizza la form, altrimenti si elabora la richiesta
	if (!isset($_POST['username']) || !isset($_POST['password'])) {
?>

<!-- visualizzazione form -->
<html>
  <head>
   <title>Login</title>
   <link rel="stylesheet" href="StyleLogin.css">
  </head>
  <body>
	<form method="POST" action="login.php">
		<div class = "flex-container">
			<label>Username</label><br>
			<input name="username" type="text" required><br><br>
				
			<label>Password</label><br>
			<input name="password" type="password" required ><br><br>
			
			<button type="submit"> Login </button>
		</div>
   </form>
   <!-- facciamo un'altra piccola form con un bottone che rimanda alla registrazione di un nuovo utente -->
	<form action="nuovo_utente.php">
		<div class = "flex-container">
			<button type="submit">Registrati</button>
		</div>
	</form>
  </body>
</html>
<?php
	}
	else {
?>

<!-- si elabora la richiesta e si visualizza l'esito dell'operazione di login - 
     si mostra un menu (uguale per tutti gli utenti loggati, ma potrebbe essere personalizzato)
-->

<html>
  <head>
    <title>Login</title>
  </head>
  <body>
<?php
	$username = $_POST['username'];
	$password = $_POST['password'];
	// controllo sulla presenza dei campi username e password
	if (strlen($username) != 0 && strlen($password) != 0){
		$connection = new mysqli("localhost","root","","iperskii") or die("Errore connessione al DB");

		// si controlla se nel database è presente un utente con lo username inserito
		$query ="SELECT * FROM utente WHERE username = '$username'";
		//echo($query);
		$result = $connection->query($query) or die("Errore query");
			if ($result->num_rows == 0) {
				// se il numero di righe restituite dalla query è 0 lo username non è presente nel DB
				// si rimanda al login 
				echo "<div class = 'flex-container'>";
				echo "Utente $username sconosciuto: <br><br>";
				echo " <button><a href=\"login.php\"> Riprova </a></button><br>";
				echo "</div>";
			}
			else {
				// se l'utente è presente si deve verificare la corrispondenza tra la password presente nel DB (criptata) e quella inserita
				$user_row = $result->fetch_array();
				// cifratura e verifica della password
				$password = crypt($password, '/rl');
				if($password == $user_row['Password']) {
					
					if($user_row['Ruolo'] == 'Cliente'){
						
						// la password è corretta. Si crea la sessione (prima se ne distrugge una eventualmente già presente)
						// e si inseriscono nella sessione le informazioni di interesse
					
						// distruzione eventuale sessione
						// precedente
						session_start();	//si recupera una sessione già attiva (se c'è)
						session_unset();	//si resetta e poi si distrugge tale sessione
						session_destroy();
						
						// inizializzazione nuova sessione
						session_start();
						$_SESSION['username'] = $username;
						$_SESSION['start_time'] = time();

						header('Location:Clienti.html');
					}
					else{
						header('Location:Dipendenti.html');
					}
				}
				else {
					// se la password è errata si rimanda al login
					echo "Password errata: ";
					echo " <a href=\"login.php\"> riprova.</a>";
				}
			}
			$result->free();
			$connection->close();
		}
	else {
		echo "Username/password non validi: ";
		echo " <a href=\"login.php\">riprova.</a>";
		die();
	}
?>
 </body>
</html>
<?php
}
?>

