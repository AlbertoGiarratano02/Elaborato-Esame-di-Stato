<?php
    $Apertura = '08:00';
    $Chiusura = '17:00';
    $i = 0;
    //echo($Apertura."    ".$Chiusura);
    $hourStart = explode(":",$Apertura);
    $hourFinish = explode(":",$Chiusura);
    //echo($hourStart[0]);
    //echo($hourFinish[0]); 
    $All = array();
    while($hourStart[0] != $hourFinish[0]){
        
        if($hourStart[0] != 8){
            $All[$i]= ("<br>".$hourStart[0].":".$hourStart[1]."0"."<br>");
            if($hourStart[0] != 9)
                $All[$i]= ("<br>".$hourStart[0].":".$hourStart[1]."0"."<br>");
            else
                $All[$i] = ("<br>"."0".$hourStart[0].":"."0".$hourStart[1]."<br>");
        }
        else
            $All[$i]= ("<br>".$hourStart[0].":".$hourStart[1]."<br>");
        $i++;
        while($hourStart[1] != 55){
            $hourStart[1] += 5;
            if($hourStart[0] == 9){
                if($hourStart[1] == 5)
                    $All[$i] = ("0".$hourStart[0].":"."0".$hourStart[1]."<br>");
                else
                    $All[$i] = ("0".$hourStart[0]).":".$hourStart[1]."<br>";
                $i++;
            }
            else{
                if($hourStart[1] == 5)
                    $All[$i] = ($hourStart[0].":"."0".$hourStart[1]."<br>");
                else
                    $All[$i] = ($hourStart[0]).":".$hourStart[1]."<br>";
                $i++;
            }
        }
        
        $hourStart[0] += 1;
        $hourStart[1] = 0;
    }
    $All[$i] = ("<br>".$hourFinish[0].":".$hourFinish[1]);
    foreach($All as $e) {
        echo($e);
    }
?>