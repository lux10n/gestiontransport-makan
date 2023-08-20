<?php 
    require('../config.php');
    if(isset($_POST['action'])){
		$action=$_POST['action'];
        if($action=='login'){
			if(isset($_POST['email'],$_POST['password'])){
				// check for user, create session
				$email=$_POST['email'];
				$password=md5($_POST['password']);
				$sql = "SELECT * FROM client WHERE email_client='$email' AND password_client='$password' LIMIT 1";
				$result = $conn->query($sql);
				if ($result->num_rows == 1 ) {
					while($row = $result->fetch_assoc()) {
						$_SESSION['role']='client';
						$_SESSION['id_client']=$row["id_client"];
						$_SESSION['nom_client']=$row["nom_client"];
						$_SESSION['prenom_client']=$row["prenom_client"];
						$_SESSION['tel_client']=$row["tel_client"];
						$_SESSION['email_client']=$row["email_client"];
					}
					die(json_encode(['success'=>true,'message'=>'identifiants valides']));
				} else {					
					die(json_encode(['success'=>false,'message'=>'identifiants non valides']));
				}
			}
        }elseif($action=='register'){
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