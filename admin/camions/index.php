<?php
    require('../../config.php');
    if(!isset($_SESSION['role'])){
        header('Location: ../../login.php');
    }
	if($_SESSION['role']!='admin'){
        header('Location: ../../login.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<meta name="description" content=""/>
		<meta name="author" content=""/>
		<title><?php echo(customname('Gestion des véhicules')) ?></title>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
		<link rel="stylesheet" href="../../assets/css/dashboard.css">
    </head>
    <body>
        <div class="d-flex" id="wrapper">
            <div class="border-end bg-white" id="sidebar-wrapper">
                <div class="sidebar-heading border-bottom bg-light"><?php echo APP_NAME; ?></div>
                <div class="list-group list-group-flush">
					<a class="list-group-item list-group-item-action list-group-item-light p-3" href="/gestiontransport/admin/" >Tableau de bord</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/gestiontransport/admin/commandes.php">Commandes</a>
                    <a class="list-group-item list-group-item-action list-group-item-primary p-3" href="/gestiontransport/admin/camions/">Gestion des véhicules</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/gestiontransport/admin/camions/etat.php">Etat des véhicules</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/gestiontransport/admin/clients.php">Gestion des clients</a>
                </div>
            </div>
            <div id="page-content-wrapper">
                <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['nom_admin'].' '.$_SESSION['prenom_admin']; ?></a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="/gestiontransport/admin/commandes.php">Commandes</a>
                                        <a class="dropdown-item" href="/gestiontransport/admin/camions/etat.php">Etat des véhicules</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="../../logout.php">Déconnexion</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <div class="container-fluid">
                    <h1 class="my-4">Liste des véhicules - <?php echo APP_NAME; ?> <a class="btn btn-primary mx-3" href="enregistrer.php">Enregistrer un véhicule</a></h1>
                    <div class="row mb-4">
						<div class="col">
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<td>#</td>
										<td>Marque</td>
										<td>Couleur</td>
										<td>Num. d'immmatriculation</td>
										<td>Etat</td>
									</tr>
								</thead>
								<tbody>
									<?php 
										$sql = "SELECT * FROM camion ORDER BY id_camion DESC";
										$result = $conn->query($sql);
										if ($result->num_rows > 0 ) {
											while($row = $result->fetch_assoc()) {
												$id_camion=$row['id_camion'];
                                                $statut_camion='disponible';
                                                $camionsql="SELECT * FROM camion where id_camion = '$id_camion'";
												$camioninfo=$conn->query($camionsql);
												while ($camion=$camioninfo->fetch_assoc()){
                                                    $camions_en_panne_sql="SELECT id_camion FROM camion c
                                                    WHERE id_camion=$id_camion AND NOT EXISTS (
                                                        SELECT * FROM panne p 
                                                        WHERE p.id_camion = c.id_camion 
                                                        AND (p.datefin_panne IS NULL OR p.datefin_panne >= NOW() )
                                                    );";
                                                    $camions_en_panne=$conn->query($camions_en_panne_sql);
                                                    if ($camions_en_panne->num_rows == 0 ) { 
                                                        $statut_camion='panne';
                                                    }
                                                    $camions_en_livraison_sql="SELECT id_camion FROM camion c
                                                    WHERE id_camion=$id_camion AND NOT EXISTS(
                                                        SELECT * FROM commande com 
                                                        WHERE com.id_camion = c.id_camion 
                                                        AND (com.datelivraison_commande >= DATE_SUB(NOW(), INTERVAL 90 MINUTE) AND com.datelivraison_commande <= DATE_ADD(NOW(), INTERVAL 90 MINUTE) )
                                                    );";
                                                    $camions_en_livraison=$conn->query($camions_en_livraison_sql);
                                                    if ($camions_en_livraison->num_rows == 0 ) { 
                                                        $statut_camion='livraison';
                                                    }
                                                    echo "<tr>";
                                                    echo "<td>".$row['id_camion']."</td>";
                                                    echo "<td>".strtoupper($row['marque_camion'])."</td>";
                                                    echo "<td>".ucfirst($row['couleur_camion'])."</td>";
                                                    echo "<td>".$row['numplaque_camion']."</td>";
                                                    switch ($statut_camion) {
                                                        case 'panne':
                                                            echo "<td><span class='text-warning'>En Panne</span></td>";
                                                            break;
                                                        case 'livraison':
                                                            echo "<td><span class='text-primary'>En Livraison</span></td>";
                                                            break;
                                                        default:
                                                            echo "<td><span class='text-success'>Disponible</span></td>";
                                                            break;
                                                    }
                                                    echo "</tr>";
												}
											}
										} else {					
											echo ('<tr><td colspan=7><p class="text-center mt-2">Aucune donnée à afficher.<p></td></tr>');
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
        <script>
            function validate(id){
                $.ajax({
                    url:'../../functions/admin.php',
                    method:'POST',
                    data:'action=validate&id_commande='+id,
                    error:function(){
                        alert('Une erreur est survenue, veuillez rééssayer.')
                    },
                    complete:function(){
                        location.reload();
                    }
                })
            }
            function cancel(id){
                $.ajax({
                    url:'../../functions/admin.php',
                    method:'POST',
                    data:'action=cancel&id_commande='+id,
                    error:function(){
                        alert('Une erreur est survenue, veuillez rééssayer.')
                    },
                    complete:function(){
                        location.reload();
                    }
                })
            }
        </script>
    </body>
</html>