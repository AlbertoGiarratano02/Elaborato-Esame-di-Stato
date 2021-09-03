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
            $time = $_POST['Orario'];
            $constPoint = 3;
            $connection = new mysqli ( "localhost","root","","iperskii");
            
            if ($time > date("H:i:s")) {
                $queryPoint = "SELECT Skipass.Punti FROM Skipass WHERE Skipass.CodiceTessera = '$codTessera'";
                $resultPoint = $connection->query($queryPoint) or die("Errore query : $queryPoint");
                if($resultPoint->num_rows != 0){
                    $user_row = $resultPoint->fetch_array();
                    $PointUpdate = $user_row['Punti'];
                    if($PointUpdate < 3){
                        echo "<div class = 'flex-container'>";
                        echo "Punti INSUFFICIENTI per poter prenotare, punti: $PointUpdate <br><br>";
                        echo " <button><a href=\"PrenotazioneRisalita.php\"> Prenota una nuova risalita</a></button><br>";
                        echo "</div>";
                    }else{
                    $PointUpdate = $PointUpdate - $constPoint;
                    //query update punti
                    $queryUpdate = "UPDATE Skipass SET Skipass.Punti = '$PointUpdate' WHERE Skipass.CodiceTessera = '$codTessera'";
                    $resultUpdate = $connection->query($queryUpdate) or die("Errore query : $queryUpdate");
                    //query insert prenotazione 
                    $query = "INSERT INTO Prenotazione (_Data,Ora,Effettuazione,Annullata,CodiceTessera,CodiceImpianto) 
                              VALUES ('$day','$time','false','false','$codTessera','$codImpianto')";
                    $result = $connection->query($query) or die("Errore query : $query");
               
                    echo "<div class = 'flex-container'>";
                    echo " Prenotazione aggiunta con successo!.<br><br>";
                    echo " <button><a href=\"PrenotazioneRisalita.php\"> Prenota una nuova risalita</a></button><br>";
                    echo "</div>";
                    }
                }
            }
            else{
                echo "<div class = 'flex-container'>";
                echo " L'orario non è più disponibile perchè antecedente all'orario attuale!<br><br>";
                echo " <button><a href=\"PrenotazioneRisalita.php\">Riprova</a></button><br>";
                echo "</div>";   
            }
        ?>
    </body>
</html>
