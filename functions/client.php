<?php 
    require('../config.php');
    header('Content-Type: application/json; charset=utf-8');
    if(isset($_POST['action'])){
		$action=$_POST['action'];
        if($action=='estimer'){
            $livraisonpossible=true;
			if(isset($_POST['quantitesable'],$_POST['datelivraison'],$_POST['lieulivraison'])){
                $quantitesable=$_POST['quantitesable'];
                $datelivraison=date("Y-m-d H:i", strtotime($_POST['datelivraison']));
                $datelivraison_m90=date_sub(date_create($datelivraison),date_interval_create_from_date_string("90 minutes"))->format('Y-m-d H:i');
                $datelivraison_p90=date_add(date_create($datelivraison),date_interval_create_from_date_string("90 minutes"))->format('Y-m-d H:i');
                $lieulivraison=$_POST['lieulivraison'];
                $heurelivraison = intval(date("H", strtotime($_POST['datelivraison'])));
                $jourlivraison = intval(date("w", strtotime($_POST['datelivraison'])));
                if($jourlivraison == 0 or ($heurelivraison <=7 or $heurelivraison >= 17)){
                    $livraisonpossible=false;
                }
                if($livraisonpossible){
                    // chercher les camions dispo
                    $camions_dispo_sql="SELECT id_camion
                    FROM camion c
                    WHERE NOT EXISTS (
                        SELECT * FROM panne p 
                        WHERE p.id_camion = c.id_camion 
                        AND (p.datefin_panne IS NULL OR p.datefin_panne >= STR_TO_DATE('".$datelivraison."','%Y-%m-%d %H:%i') ) ORDER BY id_panne LIMIT 1
                    ) AND NOT EXISTS(
                        SELECT * FROM commande com 
                        WHERE com.id_camion = c.id_camion 
                        AND (com.datelivraison_commande >= STR_TO_DATE('".$datelivraison_m90."','%Y-%m-%d %H:%i') AND com.datelivraison_commande <= STR_TO_DATE('".$datelivraison_p90."','%Y-%m-%d %H:%i') )
                    );";
                    $camions_dispo=$conn->query($camions_dispo_sql);
                    if ($camions_dispo->num_rows == 0 ) { 
                        $livraisonpossible=false;
                    }
                }
                // calculer prix
                $kilosable=250;
                $prixgonflé = 300; // 1h avant de fermer, samedis soirs
                if($heurelivraison==16 or ($jourlivraison==6 and $heurelivraison > 12)){
                    $kilosable=$prixgonflé;
                }
                $prixlivraison=0;

                $prixcommune = [

                    "cocody"=>1.1,

                    "abobo"=>1.1,
                    "adjame"=>1.1,
                    "plateau"=>1.1,
                    "marcory"=>1.1,
                    "koumassi"=>1.1,
                    
                    "attecoube"=>1.2,
                    "portbouet"=>1.2,
                    "treichville"=>1.2,

                    "yopougon"=>1.3,
                    "anyama"=>1.3,
                    "bingerville"=>1.3,

                ];

                $prixsable=$quantitesable*$kilosable;
                $prixlivraison=intval($prixsable*$prixcommune[$lieulivraison] / 1000) * 1000;

                die(json_encode(['success'=>$livraisonpossible,'data'=>[
                    'quantitesable'=>$quantitesable,
                    'datelivraison'=>$datelivraison,
                    'heurelivraison'=>$heurelivraison,
                    'jourlivraison'=>$jourlivraison,
                    'lieulivraison'=>$lieulivraison,
                    'prixtotal'=>$prixlivraison,
                    'message'=>(!$livraisonpossible) ? 'Aucun camion disponible' : null,
                ]]));
			}
        }elseif($action=='commander'){
            $livraisonpossible=true;
			if(isset($_POST['quantitesable'],$_POST['datelivraison'],$_POST['lieulivraison'])){
                $quantitesable=$_POST['quantitesable'];
                $datelivraison=date("Y-m-d H:i", strtotime($_POST['datelivraison']));
                $datelivraison_m90=date_sub(date_create($datelivraison),date_interval_create_from_date_string("90 minutes"))->format('Y-m-d H:i');
                $datelivraison_p90=date_add(date_create($datelivraison),date_interval_create_from_date_string("90 minutes"))->format('Y-m-d H:i');
                $lieulivraison=$_POST['lieulivraison'];
                $heurelivraison = intval(date("H", strtotime($_POST['datelivraison'])));
                $jourlivraison = intval(date("w", strtotime($_POST['datelivraison'])));
                if($jourlivraison == 0 or ($heurelivraison <=7 or $heurelivraison >= 17)){
                    $livraisonpossible=false;
                }
                if($livraisonpossible){
                    // chercher les camions dispo
                    $camions_dispo_sql="SELECT id_camion
                    FROM camion c
                    WHERE NOT EXISTS (
                        SELECT * FROM panne p 
                        WHERE p.id_camion = c.id_camion 
                        AND (p.datefin_panne IS NULL OR p.datefin_panne >= STR_TO_DATE('".$datelivraison."','%Y-%m-%d %H:%i') ) ORDER BY id_panne LIMIT 1
                    ) AND NOT EXISTS(
                        SELECT * FROM commande com 
                        WHERE com.id_camion = c.id_camion 
                        AND (com.datelivraison_commande >= STR_TO_DATE('".$datelivraison_m90."','%Y-%m-%d %H:%i') AND com.datelivraison_commande <= STR_TO_DATE('".$datelivraison_p90."','%Y-%m-%d %H:%i') )
                    ) ORDER BY RAND() LIMIT 1;";
                    $camions_dispo=$conn->query($camions_dispo_sql);
                    if ($camions_dispo->num_rows == 0 ) { 
                        $livraisonpossible=false;
                    }else{
                        while($row = $camions_dispo->fetch_assoc()) {
                            $id_camion=$row["id_camion"];
                        }
                    }
                }
                // calculer prix
                $kilosable=250;
                $prixgonflé = 300; // 1h avant de fermer, samedis soirs
                if($heurelivraison==16 or ($jourlivraison==6 and $heurelivraison > 12)){
                    $kilosable=$prixgonflé;
                }
                $prixlivraison=0;

                $prixcommune = [

                    "cocody"=>1.1,

                    "abobo"=>1.1,
                    "adjame"=>1.1,
                    "plateau"=>1.1,
                    "marcory"=>1.1,
                    "koumassi"=>1.1,
                    
                    "attecoube"=>1.2,
                    "portbouet"=>1.2,
                    "treichville"=>1.2,

                    "yopougon"=>1.3,
                    "anyama"=>1.3,
                    "bingerville"=>1.3,

                ];

                $prixsable=$quantitesable*$kilosable;
                $prixlivraison=intval($prixsable*$prixcommune[$lieulivraison] / 1000) * 1000;
                if($livraisonpossible){
                    $stmt = $conn->prepare("INSERT INTO commande (
                        id_client,
                        id_camion,
                        datelivraison_commande,
                        lieulivraison_commande,
                        quantitesable_commande,
                        prix_commande,
                        date_commande
                    ) VALUES (?, ?, ?, ?, ?, ?, NOW())");
					$stmt->bind_param("iissii", $_SESSION['id_client'],$id_camion,$datelivraison,$lieulivraison,$quantitesable,$prixlivraison);
					$stmt->execute();
                }
                die(json_encode(['success'=>$livraisonpossible,'data'=>[
                    'message'=>(!$livraisonpossible) ? 'Aucun camion disponible' : 'Commande effectuée avec succès',
                ]]));
			}
        }
    }
?>