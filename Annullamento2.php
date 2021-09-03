<html>
   <head>
	 <meta charset="UTF-8">
     <title>Annullamento di una prenotazione</title>
	 <link rel="stylesheet" href="StyleAnnullamento.css">
   </head>
    <body>
		<?php
            $codPrenotazione = $_POST['codPrenotazione'];
            if (strlen($codPrenotazione) != 0) {
                $connection = new mysqli ( "localhost","root","","iperskii");
                $query = "UPDATE Prenotazione SET Annullata = True WHERE Prenotazione.CodicePrenotazione = '$codPrenotazione'";
                $result = $connection->query($query) or die("Errore query : $query");
                if($result != 0){
                    echo "<div class = 'flex-container'>";
                    echo"Prenotazione annullata correttamente!";
                    echo "<button><a href=\"Clienti.html\"> HomePage </a></button><br>";
                    echo"</div>";
                }
                else{
                    echo "<div class = 'flex-container'>";
                    echo"Annullamento non eseguito correttamente!";
                    echo "<button><a href=\"Clienti.html\"> HomePage </a></button><br>";
                    echo"</div>";
                }
            }
            else{
                echo "Dati mancanti";
            }
        ?>
		
    </body>
  </html>

