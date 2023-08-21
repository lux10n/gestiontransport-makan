<?php 
    require('../config.php');
    header('Content-Type: application/json; charset=utf-8');
    if(isset($_POST['action'])){
		$action=$_POST['action'];
        if($action=='validate'){
			if(isset($_POST['id_commande'])){
                $success=true;
                $id_commande=$_POST['id_commande'];
                // chercher les camions dispo
                $commandes_exist_sql="SELECT * FROM commande WHERE id_commande=$id_commande;";
                $commandes_exist=$conn->query($commandes_exist_sql);
                if ($commandes_exist->num_rows == 0 ) { 
                    $success=false;
                }
                if($success){
                    // valider commande
                    $commande_validate_sql="UPDATE commande SET statut_commande = 'validated' WHERE id_commande=$id_commande;";
                    $commande_validate=$conn->query($commande_validate_sql);
                    if (!$commande_validate) { 
                        $success=false;
                    }
                }
                if($success){
                    // envoyer facture
                    while($row = $commandes_exist->fetch_assoc()) {
                        $id_client=$row["id_client"];
                        $clientsql="SELECT * FROM client where id_client = '$id_client'";
                        $clientinfo=$conn->query($clientsql);
                        while ($client=$clientinfo->fetch_assoc()){    
                            $to = $client['email_client'];
                            $id_camion=$row['id_camion'];
                            $camionsql="SELECT * FROM camion where id_camion = '$id_camion'";
                            $camioninfo=$conn->query($camionsql);
                            while ($camion=$camioninfo->fetch_assoc()){
                                $subject = 'Votre Facture - '.APP_NAME;
                                $message = '
                                    <html>
                                        <head>
                                            <title>Votre Facture - '.APP_NAME.'</title>
                                        </head>
                                        <body>
                                            <p>Votre commande de livraison de sable a été validée.</p>
                                            <p><b>Détails de la commande</b></p><br>
                                            <table>
                                                <tr>
                                                    <td>Client</td>
                                                    <td>'.$client['nom_client'].' '.$client['prenom_client'].'</td>
                                                </tr>
                                                <tr>
                                                    <td>Numéro de commande</td>
                                                    <td>'.$row['id_commande'].'</td>
                                                </tr>
                                                <tr>
                                                    <td>Date de commande</td>
                                                    <td>'.$row['date_commande'].'</td>
                                                </tr>
                                                <tr>
                                                    <td>Quantité de sable</td>
                                                    <td>'.$row['quantitesable_commande'].' KG</td>
                                                </tr>
                                                <tr>
                                                    <td>Date et heure de livraison</td>
                                                    <td>'.$row['datelivraison_commande'].'</td>
                                                </tr>
                                                <tr>
                                                    <td>Lieu de livraison</td>
                                                    <td>'.$communes[$row['lieulivraison_commande']].'</td>
                                                </tr>
                                                <tr>
                                                    <td>Véhicule de livraison</td>
                                                    <td>Camion '.strtoupper($camion['marque_camion']).' '.$camion['couleur_camion'].' immatriculé '.$camion['numplaque_camion'].'</td>
                                                </tr>
                                                <tr>
                                                    <td><b>TOTAL</b></td>
                                                    <td><b>'.$row['prix_commande'].' FCFA</b></td>
                                                </tr>
                                            </table>
                                            <p>Vous serez livrés à ladite date <b>après paiement dans les délais.</b></p><br>
                                        </body>
                                    </html>
                                ';
                            }

                            // To send HTML emails, remember to set the Content-type header
                            $headers = "MIME-Version: 1.0" . "\r\n";
                            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                            mail($to, $subject, $message, $headers);
                        }
                    }
                }
                die(json_encode(['success'=>$success,'data'=>[
                    'message'=>(!$success) ? 'impossible de valider' : 'commande validée avec succès',
                ]]));
			}
        }elseif($action=='cancel'){
            $success=true;
            die(json_encode(['success'=>$success,'data'=>[
                'message'=>(!$success) ? 'impossible d\'annuler' : 'commande annulée avec succès',
            ]]));
        }
    }
?>