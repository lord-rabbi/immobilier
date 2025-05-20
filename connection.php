<?php
include_once "connexion.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['connexion'])) {

    if (!empty($_POST['email']) && !empty($_POST['pass'])) {

        $email = mysqli_real_escape_string($con, $_POST['email']);
        $pass = mysqli_real_escape_string($con, $_POST['pass']);

        $test_connexion = mysqli_query($con, "SELECT * FROM utilisateurs WHERE email = '$email'");

        if ($test_connexion && mysqli_num_rows($test_connexion) > 0) {

            $user = mysqli_fetch_assoc($test_connexion);

            if (password_verify($pass   , $user['pass'])) {

                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                header("Location:index.php");

                exit();

            } else {
                header("Location:index.php");

            }
        } else {
            $message = "<p class='error'>Aucun compte trouvé avec cet email.</p>";
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
    <title>connexion</title>
</head>
<body class="body">

    <div class="pane">
        <?php 
            if (!empty($message)) echo $message;
        ?>
    </div>
    <form action="" method="POST">
        <h2>CONNEXION</h2>

        <label>Email</label>
        <input type="email" name="email" placeholder="Email">

        <label>Mot de passe</label>
        <input type="password" name="pass" placeholder="Mot de passe">

        <button type="submit" name="connexion">Se connecter</button>
        <a class="btn" href="inscription.php">S'inscrire</a>   
    </form>

</body>
</html>