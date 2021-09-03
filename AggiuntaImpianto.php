<?php
	if (!isset($_POST['nome'])|| !isset($_POST['descrizione'])) {
?>
  <html>
   <head>
	 <meta charset="UTF-8">
     <title>Aggiunta Impianto</title>
	 <link rel="stylesheet" href="StyleAggiuntaImpianto.css">
   </head>
    <body>
		<form method="POST" action="AggiuntaImpianto.php">
			<div class = "flex-container">
				<label>Nome</label><br>
				<input name="nome" type="text" required><br><br>

                <label>Descrizione</label><br>
				<input name="descrizione" type="text" required><br><br>

				<button type="submit"> Aggiungi Impianto </button><br><br>
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
     <title>Aggiunta Impianto</title>
	 <link rel="stylesheet" href="StyleAggiuntaImpianto.css">
   </head>
    <body>
<?php
	$nome = $_POST['nome'];
    $descrizione = $_POST['descrizione'];
	if (strlen($nome) != 0 && strlen($descrizione) != 0) {
		$connection = new mysqli ( "localhost","root","","iperskii");
        //controllare se c'è gia un impianto con il solito nome
		$query = "SELECT * FROM Impianto WHERE Impianto.nome = '$nome'";
		$result = $connection->query($query) or die("Errore query : $query");
		if ($result->num_rows != 0){
			echo "<div class = 'flex-container'>";
			echo " L'impianto con il nome: $nome &egrave gia' presente nel database<br><br>";
			echo " <button><a href=\"AggiuntaImpianto.php\"> Riprova </a></button><br>";
			echo "</div>"; 
		}
        else{
           $query = "INSERT INTO Impianto(nome,descrizione,stato) VALUES('$nome','$descrizione','1')";
		   $result = $connection->query($query) or die("Errore query : $query");
		   echo "<div class = 'flex-container'>";
		   echo " Impianto $nome aggiunto con successo!<br><br>";
		   echo " <button><a href=\"Dipendenti.html\"> Home Page </a></button><br>";
		   echo "</div>";
        }
	}
	else
		echo "Dati mancanti";
?>
 </body>
</html>
<?php
}
?>
