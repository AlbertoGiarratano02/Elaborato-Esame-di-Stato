<?php
	if (!isset($_POST['codImpianto'])) {
?>
  <html>
   <head>
	 <meta charset="UTF-8">
     <title>Storico prenotazioni</title>
	 <link rel="stylesheet" href="StyleChiusuraImpianto.css">
   </head>
    <body>
		<form method="POST" action="AperturaImpianto.php">
			<div class = "flex-container">
                <?php
                    echo"<label>Codice Impianto</label><br>";
                    echo"<select name='codImpianto' class = 'b'>";
                    $connection = new mysqli("localhost", "root", "", "iperskii");
                    $query = "SELECT * FROM Impianto";
                    $result = $connection->query($query);
                    if ($result->num_rows != 0) {
                        while ($row = $result->fetch_array()) {
                            echo "<option value=$row[CodiceImpianto]> $row[CodiceImpianto] -> $row[Nome]</option>";
                        }
                    $result->free();
                    }
                    echo"</select><br><br>";
                ?>
				<button type="submit"> Visualizza lo storico </button><br><br>
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
     <title>Storico prenotazioni</title>
	 <link rel="stylesheet" href="StyleChiusuraImpianto.css">
   </head>
    <body>
<?php
	$codImpianto = $_POST['codImpianto'];

	if (strlen($codImpianto) != 0) {
		$connection = new mysqli ( "localhost","root","","iperskii");
        //controllare se c'è gia un impianto con il solito nome
		$query = "UPDATE Impianto SET Impianto.Stato = 'true' WHERE Impianto.CodiceImpianto = '$codImpianto'";
		$result = $connection->query($query) or die("Errore query : $query");
		if ($result != 0){
			echo "<div class = 'flex-container'>";
			echo " L'impianto con il nome: $nome &egrave stato aperto <br><br>";
			echo " <button><a href=\"Dipendenti.html\">Home Page </a></button><br>";
			echo "</div>"; 
		}
        else{
		   echo "<div class = 'flex-container'>";
		   echo " Impianto $nome non &egrave stato aperto !<br><br>";
		   echo " <button><a href=\"AperturaImpianto.php\"> Riprova </a></button><br>";
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
