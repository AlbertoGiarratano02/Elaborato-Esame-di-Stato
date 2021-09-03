<?php
function getAllTime(){
    $Apertura = '08:00:00';
    $Chiusura = '17:00:00';
    $i = 0;
    //echo($Apertura."    ".$Chiusura);
    $hourStart = explode(":",$Apertura);
    $hourFinish = explode(":",$Chiusura);
    //echo($hourStart[0]);
    //echo($hourFinish[0]); 
    $All = array();
    while($hourStart[0] != $hourFinish[0]){
        
        if($hourStart[0] != 8){
            $All[$i]= ($hourStart[0].":".$hourStart[1]."0".":".$hourStart[2]);
            if($hourStart[0] != 9)
                $All[$i]= ($hourStart[0].":".$hourStart[1]."0".":".$hourStart[2]);
            else
                $All[$i] = ("0".$hourStart[0].":"."0".$hourStart[1].":".$hourStart[2]);
        }
        else
            $All[$i]= ($hourStart[0].":".$hourStart[1].":".$hourStart[2]);
        $i++;
        while($hourStart[1] != 55){
            $hourStart[1] += 5;
            if($hourStart[0] == 9){
                if($hourStart[1] == 5)
                    $All[$i] = ("0".$hourStart[0].":"."0".$hourStart[1].":".$hourStart[2]);
                else
                    $All[$i] = ("0".$hourStart[0].":".$hourStart[1].":".$hourStart[2]);
                $i++;
            }
            else{
                if($hourStart[1] == 5)
                    $All[$i] = ($hourStart[0].":"."0".$hourStart[1].":".$hourStart[2]);
                else
                    $All[$i] = ($hourStart[0].":".$hourStart[1].":".$hourStart[2]);
                $i++;
            }
        }
        
        $hourStart[0] += 1;
        $hourStart[1] = 0;
    }
    $All[$i] = ($hourFinish[0].":".$hourFinish[1].":".$hourStart[2]);

    return $All;
}
?>

<?php
	if (!isset($_POST['codTessera']) || !isset($_POST['codImpianto']) || !isset($_POST['ora'])) {
?>
  <html>
   <head>
	 <meta charset="UTF-8">
     <title>Prenotazione di una risalita</title>
	 <link rel="stylesheet" href="StylePrenotazione.css">
   </head>
    <body>
		<form method="POST" action="PrenotazioneRisalita.php">

			<div class = "flex-container">
				<label>Codice tessera Skipass</label><br>
				<input name="codTessera" type="text" required><br><br>
				
				<label>Codice impianto</label><br>
                <select name="codImpianto">

			        <?php   
                        $connection = new mysqli("localhost", "root", "", "iperskii");
			            $query = "SELECT * FROM Impianto";
			            $result = $connection->query($query);
			            if ($result->num_rows != 0) {
				            while ($row = $result->fetch_array()) {
					            echo "<option value=$row[CodiceImpianto]> $row[CodiceImpianto].$row[Nome] </option>";
				            }
				        $result->free();
			            }
			        ?>
			    </select><br><br>

                <label>Data prenotazione</label><br>
				<input name="ora" type="date" required><br><br>

                <input class = "b" name="Orario" type=hidden/>
				
				<button type="submit">Scegli Orario</button><br><br>
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
     <title>Prenotazione di una risalita</title>
	 <link rel="stylesheet" href="StylePrenotazione.css">
   </head>
    <body>
        <?php
            $codTessera = $_POST['codTessera'];
            $codImpianto = $_POST['codImpianto'];
            $day = $_POST['ora'];
            $All = array();
            $Final = array();

            if (strlen($codTessera) != 0 && strlen($codImpianto) != 0 && strlen($day) != 0) {
                $connection = new mysqli ( "localhost","root","","iperskii");
                $query = "SELECT * FROM Skipass WHERE Skipass.CodiceTessera = '$codTessera'";
                $result = $connection->query($query) or die("Errore query : $query");
                if ($result->num_rows == 0){
                    echo "<div class = 'flex-container'>";
			        echo " La tessera $codTessera non &egrave; presente nel database.<br><br>";
			        echo " <button><a href=\"AcquistaSkipass.php\"> Acquista una tessera se non ne hai! </a></button><br>";
                    echo " <button><a href=\"PrenotazioneRisalita.php\"> Riprova </a></button><br>";
			        echo "</div>";    
                }
                else{
                    $query = "SELECT Prenotazione.Ora FROM Prenotazione WHERE Prenotazione._Data = CURRENT_DATE() AND Prenotazione.Annullata = false";
                    $result = $connection->query($query) or die("Errore query : $query");
                    if($result->num_rows == 0){
                        $All = getAllTime();
                        echo"<form method='POST' action='InsertPrenotazione.php'";
                        echo "<div class = 'flex-container'>";
                        echo"<label>Orario</label><br>";
                        echo"<select name='Orario'>";
                        foreach($All as $e) {
                            echo "<option value= '$e' > $e </option>";
                        }
                        echo"</select><br><br>";
				        echo"<input name='codTessera' type='hidden' value=$codTessera required><br><br>";
                        echo"<input name='codImpianto' type='hidden' value=$codImpianto required><br><br>";
				        echo"<input name='ora' type='hidden' value=$day required><br><br>";
                        echo"<button type='submit'>Conferma</button><br><br>";
                        echo "</div>";
                        echo"</form>";
                    }
                    else{
                        // si richiama la funzione getAllTime che ritorna un array con all'interno
                        // tutte le possibili prenotazione dalle ore 08:00:00 alle ore 17:00:00
                        //intervallate di 5 minuti
                        $All = getAllTime();
                        //indice per l'array $Final
                        $i = 0;
                        
                        //ciclo while per lo scorrimento dell'array risultante della query effettuata
                        //per prelevare tutte le prenotazioni effettuate in quel determinato giorno
                        while($row = $result->fetch_array()) {
                            //ciclo for per lo scorrimento dell'array $All, contenente tutte le possibili
                            //prenotazioni dalle 08:00:00 alle 17:00:00 intervallate di 5 minuti
                            for($j = 0;$j < sizeof($All); $j++) {
                                //controllo se l'orario di $All è differente dall'orario preso dalla query
                                //quindi un orario già prenotato
                                if($All[$j] != $row['Ora']){
                                    //se vero, si va a copiare l'orario di $All, quindi non ancora prenotato
                                    //nell'array $Final
                                    $Final[$i] = $All[$j];
                                    //incremento dell'indice di $Final
                                    $i++;
                                }
                            }
                        }
                        echo"<form method='POST' action='InsertPrenotazione.php'";
                        echo "<div class = 'flex-container'>";
                        echo"<label>Orario</label><br>";
                        echo"<select name='Orario'>";
                        foreach($Final as $t){
                            echo "<option value= '$t' > $t </option>";
                        }
                        echo"</select><br><br>";
                        echo"<input name='codTessera' type='hidden' value=$codTessera required><br><br>";
                        echo"<input name='codImpianto' type='hidden' value=$codImpianto required><br><br>";
				        echo"<input name='ora' type='hidden' value=$day required><br><br>";
                        echo"<button type='submit'>Conferma</button><br><br>";
                        echo "</div>";
                        echo"</form>";
                    }
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