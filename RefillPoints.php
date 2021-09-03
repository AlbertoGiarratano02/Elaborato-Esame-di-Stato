<?php
	if (!isset($_POST['codTessera'])) {
?>
  <html>
   <head>
	 <meta charset="UTF-8">
     <title>Rifornimento punti</title>
	 <link rel="stylesheet" href="StyleRefill.css">
   </head>
    <body>
		<form method="POST" action="RefillPoints.php">
			<div class = "flex-container">
				<label>CodiceTessera</label><br>
				<input name="codTessera" type="text" required><br><br>

                <label>Punti</label><br>
				<input name="points" type="number" min="3" max="150" required><br><br>

				<button type="submit"> Conferma  </button><br><br>
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
     <title>Rifornimento punti</title>
	 <link rel="stylesheet" href="StyleRefill.css">
   </head>
    <body>
<?php
	$codTessera = $_POST['codTessera'];
    $Points = $_POST['points'];
        $PointsUpdate = 0;
	if (strlen($codTessera) != 0 && strlen($Points) != 0) {
		$connection = new mysqli ( "localhost","root","","iperskii");
		$query = "SELECT Skipass.Punti FROM Skipass WHERE Skipass.CodiceTessera = '$codTessera'";
		$result = $connection->query($query) or die("Errore query : $query");
		if ($result->num_rows != 0){
            $user_row = $result->fetch_array();
            $Points_row = $user_row['Punti'];
            if($Points_row <= 3){
                $PointsUpdate = $Points + $Points_row;
                $query = "UPDATE Skipass SET Skipass.Punti = '$PointsUpdate' WHERE Skipass.CodiceTessera = '$codTessera'";
                $result = $connection->query($query) or die("Errore query : $query");
                if($result != 0){
                    echo "<div class = 'flex-container'>";
			        echo " $Points sono stati aggiunti alla tessera $codTessera!<br><br>";
			        echo " <button><a href=\"Clienti.html\"> HomePage </a></button><br>";
			        echo "</div>";
                }
                else{
                    echo"Errore";
                }
            }
            else{
                echo"punti non ancora terminati";
            }
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
