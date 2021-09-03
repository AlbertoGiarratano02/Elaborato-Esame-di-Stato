<?php
	if (!isset($_POST['codTessera'])) {
?>
  <html>
   <head>
	 <meta charset="UTF-8">
     <title>Annullamento di una prenotazione</title>
	 <link rel="stylesheet" href="StyleAnnullamento.css">
   </head>
    <body>
		<form method="POST" action="Annullamento.php">
			<div class = "flex-container">
				<label>CodiceTessera</label><br>
				<input name="codTessera" type="text" required><br><br>

				<button type="submit"> Scegli la prenotazione da annullare </button><br><br>
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
     <title>Annullamento prenotazione</title>
	 <link rel="stylesheet" href="StyleAcquistaSkipass.css">
   </head>
    <body>
<?php
	$codTessera = $_POST['codTessera'];
	if (strlen($codTessera) != 0) {
		$connection = new mysqli ( "localhost","root","","iperskii");
		$query = "SELECT * FROM Skipass WHERE Skipass.CodiceTessera = '$codTessera'";
		$result = $connection->query($query) or die("Errore query : $query");
		if ($result->num_rows != 0){
            echo"<form method='POST' action='Annullamento2.php'";
            echo "<div class = 'flex-container'>";
            echo"<label>Prenotazione</label><br>";
            echo"<select name='codPrenotazione'>";
                $connection = new mysqli("localhost", "root", "", "iperskii");
                $query = "SELECT * FROM Prenotazione WHERE Prenotazione.CodiceTessera = '$codTessera' AND Prenotazione.Annullata = false";
                $result = $connection->query($query);
                if ($result->num_rows != 0) {
                    while ($row = $result->fetch_array()) {
                        echo "<option value=$row[CodicePrenotazione]> $row[CodicePrenotazione] -> $row[CodiceImpianto] -> $row[Ora]</option>";
                    }
                $result->free();
                }
			      echo"</select><br><br>";
            echo " <button type = 'submit'> Annulla</button><br>";
            echo "</div>";
            echo"</form>";
		}
        else{
            echo "<div class = 'flex-container'>";
			echo " La tessera $codTessera non &egrave; presente nel database.<br><br>";
			echo " <button><a href=\"Annullamento.php\"> Riprova </a></button><br>";
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
