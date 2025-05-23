<?php
session_start();
include_once "connexion.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: connection.php");
    exit();
}

$admin = isset($_SESSION['role']) && trim(strtolower($_SESSION['role'])) === 'admin';

$message = "";

if ($admin && isset($_POST['send'])) {
    if (!empty($_FILES['image']) && $_POST['text'] !== "") {
        $img_nom = $_FILES['image']['name'];
        $tmp_nom = $_FILES['image']['tmp_name'];

        if ($_FILES['image']['size'] > 1048576) {
            $message = "Veuillez choisir une image de taille inférieure à 1 Mo";
        } else {
            $time = time();
            $nouveau_nom_img = $time . "_" . basename($img_nom);
            $deplacer_img = move_uploaded_file($tmp_nom, "image/" . $nouveau_nom_img);

            if ($deplacer_img) {
                $lieu = mysqli_real_escape_string($con, $_POST['text']);
                $prix = mysqli_real_escape_string($con, $_POST['text1']);
                $chambre = mysqli_real_escape_string($con, $_POST['text2']);
                $sdb = mysqli_real_escape_string($con, $_POST['text3']);
                $etage = mysqli_real_escape_string($con, $_POST['text4']);

                $req = mysqli_query($con, "INSERT INTO images (image, lieu, prix, chambre, sdb, etage)VALUES ('$nouveau_nom_img', '$lieu', '$prix', '$chambre', '$sdb', '$etage')");

                if ($req) {
                    header("Location: categorie.php");
                    exit;
                } else {
                    $message = "Erreur lors de l'ajout : " . mysqli_error($con);
                }
            } else {
                $message = "Erreur lors du déplacement de l'image.";
            }
        }
    } else {
        $message = "Veuillez remplir tous les champs !";
    }
}

$req = mysqli_query($con, "SELECT * FROM images");
$annonces = [];
while ($row = mysqli_fetch_assoc($req)) {
    $annonces[] = $row;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Catégorie</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <header>
        <div class="logo">Coin <span>Parfait</span></div>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="index.php#ap">À propos</a></li>
                <li><a href="categorie.php">Catégories</a></li>
                <li><a href="#cont">Contact</a></li>
                <li><a href="deconnexion.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <section class="habitas">
        <h1>Location</h1>

        <?php if (count($annonces) === 0): ?>
            <h1 style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;">
                Aucune annonce disponible pour le moment.
            </h1>
        <?php else: ?>
            <?php for ($i = 0; $i < count($annonces); $i += 2): ?>
                <div class="maison">
                    <?php for ($j = $i; $j < $i + 2 && $j < count($annonces); $j++): ?>
                        <?php $a = $annonces[$j]; ?>
                        <div>
                            <img src="image/<?= htmlspecialchars($a['image']) ?>">
                            <div class="mai1">
                                <h2><?= htmlspecialchars($a['lieu']) ?></h2>
                                <p>RDC, Kinshasa</p>
                                <p>Prix : <?= htmlspecialchars($a['prix']) ?> $</p>
                                <div class="rens">
                                    <div>
                                        <p>Chambre</p>
                                        <p><?= htmlspecialchars($a['chambre']) ?></p>
                                    </div>
                                    <div>
                                        <p>Sdb</p>
                                        <p><?= htmlspecialchars($a['sdb']) ?></p>
                                    </div>
                                    <div>
                                        <p>Etage</p>
                                        <p><?= htmlspecialchars($a['etage']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endfor; ?>
        <?php endif; ?>

        <?php if ($admin): ?>
            <div class="maisons">
                <form action="" method="POST" enctype="multipart/form-data">
                    <?php if (!empty($message)) echo "<p class='error'>$message</p>"; ?>
                    <h1>Nouvelle publication</h1>

                    <div class="flo">
                        <label>Ajouter une photo</label>
                        <input type="file" name="image" required>
                    </div>
                    <div class="flo">
                        <label>Lieu</label>
                        <textarea name="text" required></textarea>
                    </div>
                    <div class="flo">
                        <label>Prix</label>
                        <textarea name="text1" required></textarea>
                    </div>
                    <div class="flo">
                        <label>Chambre</label>
                        <textarea name="text2" required></textarea>
                    </div>
                    <div class="flo">
                        <label>Sdb</label>
                        <textarea name="text3" required></textarea>
                    </div>
                    <div class="flo">
                        <label>Étage</label>
                        <textarea name="text4" required></textarea>
                    </div>
                    <input type="submit" value="Ajouter" name="send" class="submit-btn">
                </form>
            </div>
        <?php endif; ?>
    </section>

    <section class="pflut" id="cont">
        <h2>LA MAISON DE VOS RÊVES EST À DEUX PAS !</h2>
        <div class="local">
            <div class="local1">
                <img src="image/icons8-whatsapp.svg">
                <p>+243 893-453-653</p>
                <p>+243 990-562-123</p>
            </div>
            <div class="local1">
                <img src="image/icons8-email-100.png">
                <p>coinparfait@gmail.com</p>
            </div>
            <div class="local1">
                <img src="image/location-dot-solid.svg">
                <p>17 rue des limete</p>
            </div>
        </div>
    </section>

    <footer>
        <div class="logo1">
            <h2>Coin <span>Parfait</span></h2>
            <h4>Vous rêvez de trouver <br> la maison de vos rêves ?<br>
                Laissez-nous vous guider <br>vers votre nouveau coin idéal</h4>
        </div>
        <div class="logo1">
            <h2>Platform</h2>
            <ul class="foot">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="index.php#ap">À propos</a></li>
                <li><a href="categorie.php">Catégories</a></li>
                <li><a href="#cont">Contact</a></li>
            </ul>
        </div>
        <div class="logo1">
            <h2>Contact</h2>
            <h4>+234 853-356-897</h4>
            <h4>+243 990-087-345</h4>
        </div>
    </footer>
</body>
</html>
