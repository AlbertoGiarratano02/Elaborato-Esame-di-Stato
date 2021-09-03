<?php
	if (!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['nome']) || !isset($_POST['cognome'])) {
?>
  <html>
   <head>
	 <meta charset="UTF-8">
     <title>Registrazione nuovo cliente al servizio iperskii</title>
	 <link rel="stylesheet" href="StyleRegistrati.css">
   </head>
    <body>
		<form method="POST" action="nuovo_utente.php">
			<div class = "flex-container">
				<label>Username</label><br>
				<input name="username" type="text" required><br><br>
				
				<label>Password</label><br>
				<input name="password" type="password" required ><br><br>
				
				<label>Nome</label><br>
				<input name="nome" type="text" required><br><br>
				
				<label>Cognome</label><br>
				<input name="cognome" type="text" required><br><br>
				
				<button type="submit"> Registrati </button><br><br>
			</div>
		</form>
    </body>
  </html>
<?php
	}
	else {
?>
<html>
   <head>
   	 <meta charset="UTF-8">
     <title>Registrazione nuovo cliente al servizio iperskii</title>
	 <link rel="stylesheet" href="StyleRegistrati.css">
   </head>
    <body>
<?php
	$username = $_POST['username'];
	$password = $_POST['password'];
	$nome = $_POST['nome'];
	$cognome = $_POST['cognome'];
	if (strlen($username) != 0 && strlen($password) != 0 && strlen($nome) != 0 && strlen($cognome) != 0) {
		$password = crypt($password, '/rl'); // cifratura della  password
		$connection = new mysqli ( "localhost","root","","Iperskii");
		$query = "SELECT Utente.* FROM Utente WHERE Utente.Username = '$username'";
		$result = $connection->query($query) or die("Errore query : $query");
		if ($result->num_rows != 0){
			echo "<div class = 'flex-container'>";
			echo " L'utente $username &egrave; gi&agrave; presente nel database.<br><br>";
			echo " <button> <a href=\"login.php\"> Effettua il LOGIN </a></button><br>";
			echo "</div>";
		}
		else {
			
			$queryInsert = "INSERT INTO Utente (Utente.Username,Utente.Password,Utente.Nome,Utente.Cognome,Utente.Ruolo) 
		              VALUES ('$username', '$password', '$nome', '$cognome', 'Cliente')";
			//echo $queryInsert;
		    $connection->query($queryInsert);
			header('Location:login.php');
		}
		$result->free();
		$connection->close();
	}
	else
		echo "Username/password mancanti";
?>
 </body>
</html>
<?php
}
?>
