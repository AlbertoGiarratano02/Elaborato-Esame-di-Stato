<?php
	if (!isset($_POST['username']) || !isset($_POST['dataInizio']) || !isset($_POST['dataFine'])) {
?>
  <html>
   <head>
	 <meta charset="UTF-8">
     <title>Acquisto di un nuovo skipass</title>
	 <link rel="stylesheet" href="StyleRegistrati.css">
   </head>
    <body>
		<form method="POST" action="AcquistaSkipass.php">
			<div class = "flex-container">
				<label>Username</label><br>
				<input name="username" type="text" required><br><br>
				
				<label>Data Inizio</label><br>
				<input name="dataInizio" type="date" required ><br><br>
				
				<label>Data Fine</label><br>
				<input name="dataFine" type="date" required><br><br>
				
				<button type="submit"> Acquista </button><br><br>
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
     <title>Acquisto di un nuovo skipass</title>
	 <link rel="stylesheet" href="StyleAcquistaSkipass.css">
   </head>
    <body>
<?php
	$username = $_POST['username'];
	$dataInizio = $_POST['dataInizio'];
	$dataFine = $_POST['dataFine'];
	if (strlen($username) != 0 && strlen($dataInizio) != 0 && strlen($dataFine) != 0) {
		$connection = new mysqli ( "localhost","root","","iperskii");
        $query = "SELECT COUNT(*) FROM Skipass WHERE Skipass.DataAcquisto = '$dataInizio'";
        $result = $connection->query($query) or die("Errore query : $query");
        if($result->num_rows != 0){
            $user_row = $result->fetch_array();
            if($user_row['COUNT(*)'] < 20){
                $query = "SELECT * FROM utente WHERE username = '$username'";
                $result = $connection->query($query) or die("Errore query : $query");
                if ($result->num_rows == 0){
                    echo "<div class = 'flex-container'>";
                    echo " L'utente $username non &egrave; presente nel database.<br><br>";
                    echo " <button><a href=\"AcquistaSkipass.php\"> Riprova </a></button><br>";
                    echo "</div>";
                }else{
                    //controllo per non far acquistare una tessera con date antecedenti alla data odierna
                    if($dataInizio >= date("Y-m-d") && $dataFine >= date("Y-m-d")){
                        $nextday = $dataInizio;
                        //contatore
                        $cont = 1;
                        //var per controllo
                        $check = true;
                        //ciclo while, finchè non ciclo tutti i giorni fino a quando il primo giorno incrementato è uguale all'ultimo 
                        while($nextday != $dataFine){
                            $queryControl = "SELECT * FROM skipass WHERE skipass.username = '$username' AND '$nextday' BETWEEN skipass.DataAcquisto AND skipass.DataScadenza";
                            $result = $connection->query($queryControl);
                            if($result->num_rows != 0)
                                //se il risultato della query è != 0, quindi restituisce una corrispondenza dei giorni 
                                //la var check viene impostata a false
                                //e si esce dall'if con un break
                                $check = false;
                            break;
                            // la data iniziale viene incrementata sempre di 1 per controllare tutti i giorni
                            $nextday = date("Y-m-d", strtotime("$dataInizio +$cont day"));
                            //incremento del contatore
                            $cont++;
                        }
                        if($check) {
                            $queryInsert = "INSERT INTO skipass (DataAcquisto,DataScadenza,oraAcquisto,Punti,Username) 
                                    VALUES ('$dataInizio','$dataFine','CURRENT_TIME()','150','$username')";
                            $connection->query($queryInsert);
                            echo "<table align='center' border='2' >
                                    <tr>
                                        <th>Username</th>
                                        <th>CodiceTessera</th>
                                        <th>Punti</th>
                                        <th>DataInizio</th>
                                        <th>DataFine</th>
                                    </tr>";
                                    
                                        $username = $_POST['username'];
                                        if(strlen($username) == 0)
                                            die("dati non corretti!");
                                        $connection = new mysqli("localhost","root","","iperskii");
                                        $query = " SELECT * FROM skipass WHERE skipass.username = '$username'";
                                        $result=$connection->query($query);
                                        if($result->num_rows!=0)    
                                        {
                                            while($row=$result->fetch_array())
                                            {
                                                echo"<tr>
                                                        <td>$row[Username]</td>
                                                        <td>$row[CodiceTessera]</td>
                                                        <td>$row[Punti]</td>
                                                        <td>$row[DataAcquisto]</td>
                                                        <td>$row[DataScadenza]</td>
                                                    </tr>";
                                            }
                                            
                                            $result->free();
                                        }
                                        
                                        else
                                        {
                                            echo "errore ". "<br>";
                                        }
                                        echo("</table>");
                                        echo "<div class = 'flex-container'>";
                                        echo " <button><a href=\"Clienti.html\"> HomePage </a></button><br>";                
                                        echo "</div>";
                                        $connection->close();
                                    
                                    
                        }
                        else{
                            echo "<div class = 'flex-container'>";
                            echo "$username hai già una tessera in quel periodo di tempo scelto!";
                            echo " <button><a href=\"AcquistaSkipass.php\"> Riprova </a></button><br>";
                            echo "</div>";
                        }
                    }
                    else{
                        echo "<div class = 'flex-container'>";
                        echo "$username le date selezionate sono antecedenti alla data odierna!";
                        echo " <button><a href=\"AcquistaSkipass.php\"> Riprova </a></button><br>";
                        echo "</div>";
                    }
                }
            }
            else{
                echo "<div class = 'flex-container'>";
                echo "Non è possibile acquistare uno skipass, limite massimo giornaliero raggiunto.<br><br>";
                echo " <button><a href=\"AcquistaSkipass.php\"> Riprova </a></button><br>";
                echo "</div>";
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
