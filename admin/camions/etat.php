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
		<title><?php echo(customname('Etat des véhicules')) ?></title>
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
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/gestiontransport/admin/camions/">Gestion des véhicules</a>
                    <a class="list-group-item list-group-item-action list-group-item-primary p-3" href="/gestiontransport/admin/camions/etat.php">Etat des véhicules</a>
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
                    <h1 class="my-4">Etat des véhicules - <?php echo APP_NAME; ?> <a class="btn btn-warning mx-3" href="/gestiontransport/admin/pannes/enregistrer.php">Déclarer une panne</a></h1>
                    <h4 class="text-danger"><b>Véhicules en panne</b></h4>
					<div class="row mb-4">
						<div class="col">
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<td>#</td>
										<td>Marque du véhicule</td>
										<td>Couleur du véhicule</td>
										<td>Num. d'immatriculation</td>
										<td>Date de début</td>
										<td>Date de fin</td>
										<td>Durée de la panne</td>
										<td>Coût de la panne</td>
										<td>Actions</td>
									</tr>
								</thead>
								<tbody>
									<?php 
										$sql = "SELECT * FROM panne WHERE (datefin_panne IS NULL) OR (datefin_panne >= NOW()) ORDER BY id_panne DESC";
										$result = $conn->query($sql);
										if ($result->num_rows > 0 ) {
											while($row = $result->fetch_assoc()) {
												$id_camion=$row['id_camion'];
												$camionsql="SELECT * FROM camion where id_camion = '$id_camion'";
												$camioninfo=$conn->query($camionsql);
												while ($camion=$camioninfo->fetch_assoc()){
                                                    echo "<tr>";
                                                    echo "<td>".$row['id_panne']."</td>";
                                                    echo "<td>".strtoupper($camion['marque_camion'])."</td>";
                                                    echo "<td>".ucfirst($camion['couleur_camion'])."</td>";
                                                    echo "<td>".$camion['numplaque_camion']."</td>";
                                                    echo "<td>".$row['datedebut_panne']."</td>";
                                                    echo "<td>".(($row['datefin_panne']) ? $row['datefin_panne'] : "Date inconnue")."</td>";
                                                    echo "<td>".(($row['datefin_panne']) ? round((strtotime($row['datefin_panne']) - strtotime($row['datedebut_panne'])) / (60 * 60 * 24)) : round((time() - strtotime($row['datedebut_panne'])) / (60 * 60 * 24)))." Jours".(($row['datefin_panne']) ? "" : " (en cours)")."</td>";
                                                    echo "<td>".$row['cout_panne']." FCFA</td>";
                                                    echo "<td><a class='btn btn-primary' href='/gestiontransport/admin/pannes/modifier.php?id=".$row['id_panne']."'>Modifier</a></td>";
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
                    <h4><b>Historique des pannes</b></h4>
					<div class="row mb-4">
						<div class="col">
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<td>#</td>
										<td>Marque du véhicule</td>
										<td>Couleur du véhicule</td>
										<td>Num. d'immatriculation</td>
										<td>Date de début</td>
										<td>Date de fin</td>
										<td>Durée de la panne</td>
										<td>Coût de la panne</td>
										<td>Actions</td>
									</tr>
								</thead>
								<tbody>
									<?php 
										$sql = "SELECT * FROM panne ORDER BY id_panne DESC";
										$result = $conn->query($sql);
										if ($result->num_rows > 0 ) {
											while($row = $result->fetch_assoc()) {
												$id_camion=$row['id_camion'];
												$camionsql="SELECT * FROM camion where id_camion = '$id_camion'";
												$camioninfo=$conn->query($camionsql);
												while ($camion=$camioninfo->fetch_assoc()){
                                                    echo "<tr>";
                                                    echo "<td>".$row['id_panne']."</td>";
                                                    echo "<td>".strtoupper($camion['marque_camion'])."</td>";
                                                    echo "<td>".ucfirst($camion['couleur_camion'])."</td>";
                                                    echo "<td>".$camion['numplaque_camion']."</td>";
                                                    echo "<td>".$row['datedebut_panne']."</td>";
                                                    echo "<td>".(($row['datefin_panne']) ? $row['datefin_panne'] : "Date inconnue")."</td>";
                                                    echo "<td>".(($row['datefin_panne']) ? round((strtotime($row['datefin_panne']) - strtotime($row['datedebut_panne'])) / (60 * 60 * 24)) : round((time() - strtotime($row['datedebut_panne'])) / (60 * 60 * 24)))." Jours".(($row['datefin_panne']) ? "" : " (en cours)")."</td>";
                                                    echo "<td>".$row['cout_panne']." FCFA</td>";
                                                    echo "<td><a class='btn btn-primary' href='/gestiontransport/admin/pannes/modifier.php?id=".$row['id_panne']."'>Modifier</a></td>";
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
                    <!-- <h4 class="text-primary"><b>Statistiques des pannes</b></h4>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card bg-info text-white my-4">
                                <div class="card-header">Fréquence des pannes</div>
                                <div class="card-body"> XX pannes par année</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card bg-warning my-4">
                                <div class="card-header">Durée moyenne de maintenance</div>
                                <div class="card-body"> XX Jours </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card bg-success text-white my-4">
                                <div class="card-header">Coût moyen de chaque panne</div>
                                <div class="card-body">XX FCFA</div>
                            </div>
                        </div>
                    </div> -->
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