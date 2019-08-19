<?php
error_log("toto");
$post = json_decode(file_get_contents("php://input"), true);
error_log("Coucou c'est moi le coach");

//include "fonctions.php";
$mysqli = new mysqli('127.0.0.1', 'root', '', 'coach');
mysqli_set_charset($mysqli, 'utf8');
// Oh no! A connect_errno exists so the connection attempt failed!
if ($mysqli->connect_errno) {
    // The connection failed. What do you want to do? 
    // You could contact yourself (email?), log the error, show a nice page, etc.
    // You do not want to reveal sensitive information

    // Let's try this:
    error_log( "Sorry, this website is experiencing problems.");

    // Something you should not do on a public site, but this example will show you
    // anyways, is print out MySQL error related information -- you might log this
    echo "Error: Failed to make a MySQL connection, here is why: \n";
    echo "Errno: " . $mysqli->connect_errno . "\n";
    echo "Error: " . $mysqli->connect_error . "\n";
    
    // You might want to show them something nice, but we will simply exit
    die();
}

error_log("PROUT ".$post[0]);
foreach ($post as $element) {
	error_log("Mon element :".$element);
}
//$mynewarray = explode(',',$post[0],1);
//error_log("PROUT PROUT".$mynewarray[0]);
//error_log("PROUT PROUT".$mynewarray[1]);



//contrôle de reception de paramètre
if(count($post)!=0){
		error_log("Prout ");

	// demande de récupération du dernier profil
	if ($post[0] == "dernier_profil"){
		//print("dernier");
		error_log("Dernier profil recherché");
	
				// Perform an SQL query
				$sql = "select datemesure,poids, taille, age,sexe from profil order by datemesure DESC";
				error_log("La requete ".$sql);

				$result = $mysqli->query($sql);
				if (!$result ) {
					error_log("error during query");
				}	else {
					$row = $result->fetch_array(MYSQLI_NUM);
					$enjson = json_encode($row,JSON_PARTIAL_OUTPUT_ON_ERROR );
					print($enjson);
					error_log("tout est OK");
					error_log ($enjson);
				}

		
		
		//enregistrement d'un nouveau profile
	} elseif ($post[0] =="new_profil" ) {
				error_log("Enregistre un nouveau profil");

				//recuperation des donnees en post 
				$mesDonnee =$post[1];
				$donnee=$mesDonnee;
				error_log("Ma valeur :".$mesDonnee);
				$donnee = json_decode($mesDonnee);
				$datemesure=$donnee[0];
				$taille=$donnee[1];
				$poids=$donnee[2];
				$age=$donnee[3];
				$sexe=$donnee[4];
				
				//insertion dans la BBD
				
				//print ("new_profil%");
				$sql = "insert into profil values (\"$datemesure\", $poids,$taille,$age,$sexe)";
				error_log("sql=".$sql);
				if (!$result = $mysqli->query($sql)) {
					error_log("error during query");
					error_log( "Errno: " . $mysqli->errno . "\n");
					error_log( "Error: " . $mysqli->error . "\n");
				} else {
					$myarray= ["OK"];
					$enjson2 = json_encode($myarray,JSON_PARTIAL_OUTPUT_ON_ERROR );
					print $enjson2;
				}
				print ($sql);
					
				
	} 	elseif($post[0] == "tous_profil"){
				error_log("récupération de tous les profils");
				//print("tous");
				// Perform an SQL query
				$sql = "select datemesure,poids, taille, age,sexe from profil" ;
				error_log("La requete ".$sql);

				$result = $mysqli->query($sql);
				if (!$result ) {
					error_log("error during query");
				}	else {
					$row[]= $result->fetch_all(MYSQLI_NUM);
					$enjson = json_encode($row,JSON_PARTIAL_OUTPUT_ON_ERROR );
					print($enjson);
					error_log("tout est OK");
					error_log ($enjson);
				}
		}
		
		elseif ($post[0] == "delete_profil"){
		//print("dernier");
		error_log("Suppression d'un profil recherché");
				$mesDonnee =$post[1];
				$donnee=$mesDonnee;
				error_log("Ma valeur :".$mesDonnee);
				$donnee = json_decode($mesDonnee);
				$datemesure=$donnee[0];

				// Perform an SQL query
				$sql = "delete from profil where datemesure=\"$datemesure\"";
				error_log("La requete ".$sql);

				$result = $mysqli->query($sql);
				if (!$result ) {
					error_log("error during query");
				}	else {
					$myarray= ["OK"];
					$enjson3 = json_encode($myarray,JSON_PARTIAL_OUTPUT_ON_ERROR );
					print $enjson3;
				}

	}
}	


?>