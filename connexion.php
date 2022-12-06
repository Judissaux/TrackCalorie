<?php
session_start();
if ($_SESSION["user"]) {
    header("location: profil.php");
    exit;
}

$title = "Connexion";
if (!empty($_POST)) {
   
    // on vérifie que les données existent et que les champs ne sont pas vides
    if (
        isset($_POST["email"], $_POST["password"]) &&
        !empty($_POST["email"]) && !empty($_POST["password"])
    ) {
        //on vérifie que l'adresse mail
        $_SESSION["error"] = [];
        
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
             "Erreur adresse e-mail";
        }
        if ($_SESSION["error"] === []) {

            // on vérifie que l'adresse mail existe
            // on se connecte à la base de donnée
            require_once "bddConnect.php";

            //on regarde si l'adresse mail est présente

            $sql = "SELECT * FROM `tcusers` WHERE email = :email";

            //on prepare la requete

            $requete = $connexion->prepare($sql);

            //on injecte les valeurs

            $requete->bindValue(":email", $_POST["email"]);

            // on execute la requete

            $requete->execute();

            // on récupére les données

            $user = $requete->fetch();

            // on vérifie si l'utilateur existe et on vérifie le mot de passe
                                          
           
            if (!$user || !password_verify($_POST["password"], $user["password"])) {
               $_SESSION["error"][] = "L'utilisateur et/ou le mot de passe incorrect";
                          
            }
                       
            if ($_SESSION["error"] === []) {
                
                header("location: profil.php");

                $_SESSION["user"] = [
                    "id" => $user["id"],
                    "pseudo" => $user["pseudo"],
                    "email" => $user["email"],
                    "sexe" => $user["sexe"],
                    "taille" => $user["taille"],
                    "poids" => $user["poids"]
                ];
            }              
    
}
    } else {
        $_SESSION["error"] = ["Les champs ne sont pas tous remplis"];
    }
    
}
?>

<?php include "header.php" ?>

<div class="titleConnexion">
    <h1>Se connecter</h1>
    <p>Vous n'avez pas de compte ? <a href="inscription.php">S'inscrire</a></p>



<div class="text-danger">
    <?php
    if (isset($_SESSION["error"])) {
          foreach ($_SESSION["error"] as $message) {
            echo $message;
            
        }
        unset($_SESSION["error"]);
    }
    ?>
</div>


<form method="post">
<div class="formConnexion">
<input  type="email"  name="email" id="email" placeholder="E-mail">
<input  type="password"  name="password" id="password" placeholder="Password">
<button type="submit" class=" btn btn-primary btn-sm ">Connexion </button>
</div>
</form>
</div>


<?php include "footer.php" ?>