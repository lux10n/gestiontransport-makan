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
		<title><?php echo(customname('Commander une Livraison')) ?></title>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
		<link rel="stylesheet" href="../assets/css/dashboard.css">
    </head>
    <body>
        <div class="d-flex" id="wrapper">
            <div class="border-end bg-white" id="sidebar-wrapper">
                <div class="sidebar-heading border-bottom bg-light"><?php echo APP_NAME; ?></div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/gestiontransport/client/" >Tableau de bord</a>
                    <a class="list-group-item list-group-item-action list-group-item-primary p-3" href="/gestiontransport/client/commander.php">Commander une livraison</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/gestiontransport/client/commandes.php">Mes commandes</a>
                </div>
            </div>
            <div id="page-content-wrapper">
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
                <div class="container-fluid px-5">
                    <h1 class="mt-4">Commander une livraison</h1>
                    <div class="row gx-5 mt-5">
                        <div class="col-xl-6 p-5">
                            <form id="estimate" method="POST">
                                <p class="mb-3"><b>1. Obtenez une estimation du prix avant de commander.</b></p>
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="quantitesable" type="number" placeholder="Ex: 50" required/>
                                    <label for="quantitesable">Quantité de sable (en KG)</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="datelivraison" type="datetime-local" min="<?=date('Y-m-d\Th:i')?>" placeholder="Ex: 16/08/23 7:30" required/>
                                    <label for="datelivraison">Date de livraison du sable</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="lieulivraison" required>
                                        <option value="">Sélectionnez un lieu</option>
                                        <option value="abobo">Abobo</option>
                                        <option value="anyama">Anyama</option>
                                        <option value="adjame">Adjamé</option>
                                        <option value="attecoube">Attécoubé</option>
                                        <option value="bingerville">Bingerville</option>
                                        <option value="cocody">Cocody</option>
                                        <option value="koumassi">Koumassi</option>
                                        <option value="marcory">Marcory</option>
                                        <option value="plateau">Le Plateau</option>
                                        <option value="portbouet">Port-Bouët</option>
                                        <option value="treichville">Treichville</option>
                                        <option value="yopougon">Yopougon</option>
                                    </select>
                                    <label for="lieulivraison">Lieu de livraison du sable</label>
                                </div>
                                <div class="d-grid"><button class="btn btn-primary btn-lg" type="submit">Estimer le prix</button></div>
                            </form>
                        </div>
                        <div class="col-xl-6 rounded border p-5">
                            <p class="mb-3"><b>Informations de votre commande</b></p>
                            <div class="col d-flex justify-content-between">
                                <p>Quantité de sable</p>
                                <p id="confirm-quantitesable">N/A</p>
                            </div>
                            <div class="col d-flex justify-content-between">
                                <p>Date de livraison</p>
                                <p id="confirm-datelivraison">N/A</p>
                            </div>
                            <div class="col d-flex justify-content-between">
                                <p>Lieu de livraison</p>
                                <p id="confirm-lieulivraison">N/A</p>
                            </div>
                            <div class="col d-flex justify-content-between">
                                <p>Prix total</p>
                                <p id="confirm-prixtotal">N/A</p>
                            </div>
                            <div class="col d-flex justify-content-between">
                                <p>Livraison possible</p>
                                <p id="confirm-livraisonpossible" class="text-success"><b>Oui</b></p>
                            </div>
                            <div class="d-grid"><button id="orderbutton" class="btn btn-success btn-lg" type="submit" disabled>Commander</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
        <script>
            var commmunes={
                "abobo":"Abobo",
                "anyama":"Anyama",
                "adjame":"Adjamé",
                "attecoube":"Attécoubé",
                "bingerville":"Bingerville",
                "cocody":"Cocody",
                "koumassi":"Koumassi",
                "marcory":"Marcory",
                "plateau":"Le Plateau",
                "portbouet":"Port-Bouët",
                "treichville":"Treichville",
                "yopougon":"Yopougon",
            };
            $(document).ready(function(){
                $('#estimate').submit(function(e){
                    e.preventDefault();
                    var detailscommande="quantitesable="+$('#quantitesable').val()+"&datelivraison="+$('#datelivraison').val()+"&lieulivraison="+$('#lieulivraison').val();
                    $.ajax({
                        method:'POST',
                        url:'../functions/client.php',
                        data:"action=estimer&"+detailscommande,
                        success:function(data){
                            response=data;
                            console.log(response);
                            $('#confirm-quantitesable').html(response.data.quantitesable+' KG');
                            $('#confirm-datelivraison').html(response.data.datelivraison);
                            $('#confirm-lieulivraison').html(commmunes[response.data.lieulivraison]);
                            if(response.success){
                                $('#confirm-prixtotal').html(response.data.prixtotal+" FCFA");
                                $('#confirm-livraisonpossible').removeClass("text-danger");
                                $('#confirm-livraisonpossible').addClass("text-success");
                                $('#confirm-livraisonpossible').html("<b>Oui</b>");
                                
                                $('#orderbutton').attr("disabled",!1);
                                $('#orderbutton').removeClass("btn-danger");
                                $('#orderbutton').addClass("btn-success");
                                $('#orderbutton').html("Commander");
                            }else{
                                $('#confirm-livraisonpossible').removeClass("text-success");
                                $('#confirm-livraisonpossible').addClass("text-danger");
                                $('#confirm-livraisonpossible').html("<b>Non - "+response.data.message+"</b>");
                                
                                $('#orderbutton').attr("disabled",1);
                                $('#orderbutton').removeClass("btn-success");
                                $('#orderbutton').addClass("btn-danger");
                                $('#orderbutton').html(response.data.message);
                            }
                        },error:function(error){
                            alert('Une erreur est survenue, veuillez rééssayer.');
                            console.log(error)
                        }
                    })
                });
                $('#orderbutton').click(function(){
                    var detailscommande="quantitesable="+$('#quantitesable').val()+"&datelivraison="+$('#datelivraison').val()+"&lieulivraison="+$('#lieulivraison').val();
                    $.ajax({
                        method:'POST',
                        url:'../functions/client.php',
                        data:"action=commander&"+detailscommande,
                        success:function(data){
                            response=data;
                            console.log(response);
                            if(response.success){
                                alert('Votre commande a été effectuée, vous recevrez votre facture par E-Mail.');
                            }else{
                                alert('Une erreur est survenue, veuillez recommencer le processus.');
                                location.reload();
                            }
                        },error:function(error){
                            alert('Une erreur est survenue, veuillez rééssayer.');
                            console.log(error)
                        }
                    })
                });
            });
        </script>
    </body>
</html>