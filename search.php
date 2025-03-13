<?php
    include("connessione.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scelta attori</title>
</head>
<body>
    <?php
        $num = $_GET["num"];

        if ($num >=1) {

            //Selezione degli attori dal database
            $sql = "SELECT a.CodAttore, a.Nome from attori as a ORDER BY a.Nome ASC LIMIT $num";
            $res = $conn->query($sql);
            if ($res->num_rows > 0) { 
                echo "<div>";
                    echo "<h1>$res->num_rows attori trovati</h1>";
                    $i=1;
                        while ($row = $res->fetch_assoc()) {
                            $codAttore = $row["CodAttore"];
                            $nome = $row["Nome"];

                            //Per ogni attore, si conta il numero di film in cui ha recitato
                            $sql2 = "SELECT COUNT(r.CodFilm) as numFilmFatti FROM recita as r LEFT JOIN attori as a ON r.CodAttore = a.CodAttore WHERE a.CodAttore = $codAttore";
                            $res2 = $conn->query($sql2);
                                if ($res2->num_rows > 0) {  
                                    $row_nf = $res2->fetch_assoc();
                                    $numFilm = $row_nf['numFilmFatti'];
                                    if ($numFilm >= 1) {

                                        //Se l'attore ha recitato in almeno un film, vengono recuperati i dettagli
                                        $sql3 = "SELECT f.CodFilm, f.Titolo, f.AnnoProduzione FROM attori as a LEFT JOIN recita as r ON a.CodAttore = r.CodAttore LEFT JOIN film as f ON r.CodFilm = f.CodFilm WHERE a.CodAttore = $codAttore";
                                        $res3 = $conn->query($sql3);
                                        if ($res3->num_rows > 0) {
                                            showData($i, $codAttore, $nome, $numFilm, $res3);
                                        }
                                    } else {
                                        showDataNull($i, $codAttore, $nome);
                                    }  
                                }
                            $i++;
                        }
                    echo "<br>";
                    echo "<br>";
                    echo "<a href='home.html'>HOME</a>";
                echo "</div>";
            }
        } else {
            showDataErr();
        }

        function showData($i, $codAt, $name, $nf, $lf){  
            echo "<h1>ATTORE $i</h1>";
            echo "<p><b>Codice attore: </b>$codAt</p>";
            echo "<p><b>Nome attore: </b>$name</p>";
            echo "<p><b>Numero di film fatti: </b>$nf</p>";
            echo "<p><b>Lista film fatti: </b></p>";
            while ($row = $lf->fetch_assoc()) { 
                echo "<p><i>" . $row["CodFilm"] . " " . $row["Titolo"] . " " . $row["AnnoProduzione"] . "</i></p>";
            }
            echo "-----------------------------------------------------";   
            
        }

        function showDataNull($i, $codAt, $name){       
            echo "<h1>ATTORE $i</h1>";
            echo "<p><b>Codice attore: </b>$codAt</p>";
            echo "<p><b>Nome attore: </b>$name</p>";
            echo "<p>L'attore non ha fatto film</p>";
            echo "-----------------------------------------------------";
        }

        function showDataErr(){         
            echo "<div>";
                echo "<h1 class='error'>NESSUN ATTORE TROVATO!</h1>";
                echo "<br>";
                echo "<br>";
                echo "<a href='home.html'>HOME</a>";
            echo "</div>";
        }   
       
    ?>
</body>
</html>