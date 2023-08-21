<?php
  require('config.php');
  if(isset($_SESSION['role'])){
    header('Location: '.$_SESSION['role']);
  }
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo(customname('Connexion')) ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
  <div class="container">
    <div class="bg-light rounded-3 p-5">
        <div class="text-center mb-5">
          <h1 class="fw-bolder">Connexion</h1>
        </div>
        <div class="row gx-5 justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <form id="login" method="POST">
                    <div class="form-floating mb-3">
                        <input class="form-control" id="email" type="email" placeholder="Ex: johndoe@example.com" required/>
                        <label for="email">Adresse E-Mail</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="password" type="password" placeholder="Ex: 12345" required/>
                        <label for="password">Mot de passe</label>
                    </div>
                    <div class="response"></div>
                    <div class="d-grid"><button class="btn btn-primary btn-lg" type="submit">Valider</button></div>
                    <div class="mt-3">
                      <p>Pas encore inscrit ? <a href="register.php">Inscrivez vous!</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
  </div>
</body>
<script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
<script>
  $(document).ready(function(){
    $('#login').submit(function(e){
      $(".response").html('')
      e.preventDefault();
      $.ajax({
        method:'POST',
        url:'functions/auth.php',
        data:"action=login&email="+$('#email').val()+"&password="+$('#password').val(),
        success:function(data){
          response=data;
          if(response.success){
            $(".response").html('<div class="alert alert-success alert-dismissible">Identifiants Valides...</div>')
            setTimeout(() => {
              window.location.href='/gestiontransport'
            }, 1000);
          }else{
            $(".response").html('<div class="alert alert-danger alert-dismissible">Identifiants Invalides, veuillez rééssayer.</div>')
          }
        },error:function(error){
          alert('Une erreur est survenue, veuillez rééssayer.');
          console.log(error)
        }
      })
    })
  });
</script>
</html>