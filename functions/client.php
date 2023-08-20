<?php 
    require('../config.php');
    if(isset($_POST['action'])){
		$action=$_POST['action'];
        if($action=='estimer'){
			if(isset($_POST['quantitesable'],$_POST['datelivraison'],$_POST['lieulivraison'])){
                $quantitesable=$_POST['quantitesable'];
                $datelivraison=date("Y-m-d H:i", strtotime($_POST['datelivraison']));
                $datelivraison_m90=date_sub(date_create($datelivraison),date_interval_create_from_date_string("90 minutes"))->format('Y-m-d H:i');
                $datelivraison_p90=date_add(date_create($datelivraison),date_interval_create_from_date_string("90 minutes"))->format('Y-m-d H:i');
                $lieulivraison=$_POST['lieulivraison'];
                // chercher les camions dispo
                $camions_dispo_sql="SELECT id_camion
                FROM camion c
                WHERE NOT EXISTS (
                    SELECT * FROM panne p 
                    WHERE p.id_camion = c.id_camion 
                    AND (p.datefin_panne IS NULL OR p.datefin_panne >= STR_TO_DATE('".$datelivraison."','%Y-%m-%d %h:%i') ) ORDER BY id_panne LIMIT 1
                ) AND NOT EXISTS(
                    SELECT * FROM commande com 
                    WHERE com.id_camion = c.id_camion 
                    AND (com.datelivraison_commande >= STR_TO_DATE('".$datelivraison."','%Y-%m-%d %h:%i') AND com.datelivraison_commande <= STR_TO_DATE('".$datelivraison."','%Y-%m-%d %h:%i') )
                );";
                $camions_dispo=$conn->query($camions_dispo_sql);
				if ($camions_dispo->num_rows > 0 ) {
                    // calculer prix
                    $kilosable=250;
                    $prixgonflé = 300; // 1h avant de fermer, samedis soirs

                    $prixcommune = [
                        "cocody"=>1,

                        "abobo"=>1,
                        "adjame"=>1,
                        "plateau"=>1,
                        "marcory"=>1,
                        "koumassi"=>1,
                        
                        "attecoube"=>1.2,
                        "portbouet"=>1.2,
                        "treichville"=>1.2,

                        "yopougon"=>1.5,
                        "anyama"=>1.5,
                        "bingerville"=>1.5,
                    ];

                    die(json_encode(['success'=>true,'data'=>[
                        'quantitesable'=>$quantitesable,
                        'datelivraison'=>$datelivraison,
                        'lieulivraison'=>$lieulivraison,
                        'prixtotal'=>$prixtotal,
                        
                    ]]));
				} else {					
					die(json_encode(['success'=>false,'message'=>'identifiants non valides']));
				}
			}
        }elseif($action=='commander'){
			if(isset($_POST['nom'],$_POST['prenom'],$_POST['tel'],$_POST['email'],$_POST['password'])){
				// check for user email
				$nom=$_POST['nom'];
				$prenom=$_POST['prenom'];
				$tel=$_POST['tel'];
				$email=$_POST['email'];
				$password=md5($_POST['password']);
				$sql = "SELECT * FROM client WHERE email_client='$email' LIMIT 1";
				$result = $conn->query($sql);
				if ($result->num_rows == 0 ) {
					// prepare and bind
					$stmt = $conn->prepare("INSERT INTO client (nom_client, prenom_client, tel_client, email_client, password_client) VALUES (?, ?, ?, ?, ?)");
					$stmt->bind_param("sssss", $nom,$prenom,$tel,$email,$password);
					$stmt->execute();
					die(json_encode(['success'=>true,'message'=>'identifiants enregistrés']));
				} else {					
					die(json_encode(['success'=>false,'message'=>'L\'adresse E-Mail est déjà utilisée.']));
				}
			}
        }
    }
?>