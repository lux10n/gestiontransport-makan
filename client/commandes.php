<?php
    require('../config.php');
    if(!isset($_SESSION['role'])){
        header('Location: ../login.php');
    }
	if($_SESSION['role']!='client'){
        header('Location: ../login.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<meta name="description" content=""/>
		<meta name="author" content=""/>
		<title><?php echo(customname('Mes Commandes')) ?></title>
		<!-- Core theme CSS (includes Bootstrap)-->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
		<link rel="stylesheet" href="../assets/css/dashboard.css">
    </head>
    <body>
        <div class="d-flex" id="wrapper">
            <!-- Sidebar-->
            <div class="border-end bg-white" id="sidebar-wrapper">
                <div class="sidebar-heading border-bottom bg-light"><?php echo APP_NAME; ?></div>
                <div class="list-group list-group-flush">
					<a class="list-group-item list-group-item-action list-group-item-light p-3" href="/gestiontransport/client/" >Tableau de bord</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/gestiontransport/client/commander.php">Commander une livraison</a>
                    <a class="list-group-item list-group-item-action list-group-item-primary p-3" href="/gestiontransport/client/commandes.php">Mes commandes</a>
                </div>
            </div>
            <!-- Page content wrapper-->
            <div id="page-content-wrapper">
                <!-- Top navigation-->
                <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['nom_client'].' '.$_SESSION['prenom_client']; ?></a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="commander.php">Commander une livraison</a>
                                        <a class="dropdown-item" href="commandes.php">Mes commandes</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="../logout.php">Déconnexion</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <!-- Page content-->
                <div class="container-fluid">
                    <h1 class="my-4">Commandes de <?php echo $_SESSION['nom_client'].' '.$_SESSION['prenom_client']; ?></h1>
					<div class="row">
						<div class="col-11">
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<td>#</td>
										<td>Date de la commande</td>
										<td>Quantité de sable</td>
										<td>Date de livraison</td>
										<td>Lieu de livraison</td>
										<td>Véhicule de livraison</td>
										<td>Coût total</td>
										<td>Statut</td>
									</tr>
								</thead>
								<tbody>
									<?php 
										$sql = "SELECT * FROM commande WHERE id_client='".$_SESSION['id_client']."' ORDER BY id_commande DESC";
										$result = $conn->query($sql);
										if ($result->num_rows > 0 ) {
											while($row = $result->fetch_assoc()) {
												$id_camion=$row['id_camion'];
												$camionsql="SELECT * FROM camion where id_camion = '$id_camion'";
												$camioninfo=$conn->query($camionsql);
												while ($camion=$camioninfo->fetch_assoc()){
													echo "<tr>";
													echo "<td>".$row['id_commande']."</td>";
													echo "<td>".$row['date_commande']."</td>";
													echo "<td>".$row['quantitesable_commande']." KG</td>";
													echo "<td>".$row['datelivraison_commande']."</td>";
													echo "<td>".$commmunes[$row['lieulivraison_commande']]."</td>";
													echo "<td>Camion ".$camion['marque_camion']." ".$camion['couleur_camion']." (".$camion['numplaque_camion'].")</td>";
													echo "<td>".$row['prix_commande']."</td>";
													switch ($row['statut_commande']) {
														case 'validated':
															echo "<td><span class='text-success'>Validé</span></td>";
															break;
														default:
															echo "<td><span class='text-warning'>En Attente</span></td>";
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
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
        <!-- Core theme JS-->
        <script>

        </script>
    </body>
</html>