<?php 

    include_once "connexion.php";
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['inscription'])) {

        if (!empty($_POST['email']) && !empty($_POST['pass'])) {

            $email = mysqli_real_escape_string($con, $_POST['email']);
            $pass = mysqli_real_escape_string($con, $_POST['pass']);

            $test_email = mysqli_query($con, "SELECT * FROM utilisateurs WHERE email = '$email'");
        
            if ($test_email && mysqli_num_rows($test_email) > 0) {
                $message = "<p class='error'>Cet email est déjà utilisé. Veuillez en choisir un autre.</p>";
            } else {
                $mask_pass = password_hash($pass, PASSWORD_DEFAULT);
                $req = mysqli_query($con, "INSERT INTO utilisateurs (email, pass) VALUES ('$email', '$mask_pass')");
            
                if ($req) {
                    $message = "<p class='success'>Inscription réussie!.</p>";
                } else {
                    $message = "<p class='error'>Erreur Veuillez réessayer.</p>";
                }
            }
        } else {
            $message = "<p class='error'>Veuillez remplir tous les champs !</p>";
        }
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/connection.css">
    <title>inscription</title>
</head>
<body>  

    <div class="pane">
        <?php 
            if (!empty($message)) echo $message;
        ?>
    </div>


    <form method="POST">
        <h2>INSCRIPTION</h2>

        <label>Email</label>
        <input type="email" name="email" placeholder="Email">

        <label>Mot de passe</label>
        <input type="password" name="pass" placeholder="Mot de passe">

        <button type="submit" name="inscription">S'inscrire</button>
        <a class="btn" href="connection.php">Se connecter</a>
            
    </form>


    <!--ajout adm :UPDATE utilisateurs SET role = 'admin' WHERE email = 'admin@example.com';-->
    
</body>
</html>