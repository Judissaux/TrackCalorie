<?php
session_start();
if ($_SESSION["user"]) {
    header("location: profil.php");
    exit;
}
$title = "Inscription";
//   on verifie que la variable post existe
if (!empty($_POST)){
    // on vérifie que les données existent et que les champs ne sont pas vide
    if (
        isset($_POST["pseudo"], $_POST["email"], $_POST["password"], $_POST["confpass"], $_POST["sexe"], $_POST["taille"], $_POST["poids"]) &&
        !empty($_POST["pseudo"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["confpass"]) && !empty($_POST["sexe"]) && !empty($_POST["taille"]) && !empty($_POST["poids"])
    ) {
        // on protége les données
        // on enleve les codes html ou script pouvant être mis dans le pseudo
        $pseudo = strip_tags($_POST["pseudo"]);
        $_SESSION["error"] = [];
        // on vérifie si on a bien un email
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $_SESSION["error"][] = "L'adresse email n'est pas au bon format";
        }

        $email = strtolower(($_POST["email"]));
        // on vérifie que les passwords sont identiques
        if ($_POST["password"] != $_POST["confpass"]) {
            $_SESSION["error"][] = "Les mots de passe sont différents";
        }
        // on vérifie que l'email n'existe pas encore dans la BDD
        require_once "bddConnect.php";
        $sql = "SELECT * FROM `tcusers` WHERE `email` = :email";
        $requete = $connexion->prepare($sql);
        $requete->bindValue(":email", $email);
        $requete->execute();
        $emailUsers = $requete->fetch();
        if($emailUsers["email"] === $email ){
            $_SESSION["error"] = ["Cet utilisateur existe déjà."];
        }

        // on hash le password
        $password = password_hash($_POST["password"], PASSWORD_ARGON2ID);

        //on protége le sexe
        if ($_POST["sexe"] != 'femme' && $_POST["sexe"] != 'homme') {
            $_SESSION["error"][] = "Erreur au niveau du sexe";
        }
        // on récupére le sexe
        $sexe = strip_tags($_POST["sexe"]);

        //on protége la taille en appliquant une valeur min et max
        if ($_POST["taille"] < 80 || $_POST["taille"] > 250) {
            $_SESSION["error"][] = "Taille incorrecte";
        }
        // on enleve les potentiels balises et on vérifie que c'est réellement un entier
        $taille = strip_tags(filter_var($_POST["taille"], FILTER_VALIDATE_INT));

        // on protége le poid en appliquant une valeur min et max
        if ($_POST["poids"] < 40 || $_POST["poids"] > 200) {
            $_SESSION["error"][] = "Poids incorrect";
        }

        // on enleve les potentiels balises et on vérifie que c'est réellement un entier
        $poids = strip_tags(filter_var($_POST["poids"], FILTER_VALIDATE_INT));

        if ($_SESSION["error"] === []) {

            //on se connecte à la base de données
            require_once "bddConnect.php";

            // on écrit la requête
            $sql = "INSERT INTO `tcusers`(`pseudo`,`email`,`password`,`sexe`,`taille`,`poids`) VALUES(:pseudo,:email,'$password',:sexe,:taille,:poids)";
            // on prepare la requête
            $requete = $connexion->prepare($sql);

            // on injecte les valeurs

            $requete->bindValue(":pseudo", $pseudo);
            $requete->bindValue(":email", $email);
            $requete->bindValue(":sexe", $sexe);
            $requete->bindValue(":taille", $taille);
            $requete->bindValue(":poids", $poids);

            // on exécute la requete

            $requete->execute();
            $id = $connexion->lastInsertId();
            // on ouvre la session
            $_SESSION["user"] = [
                "id" => $id,
                "pseudo" => $pseudo,
                "email" => $_POST["email"],
                "sexe" => $sexe,
                "taille" => $taille,
                "poids" => $poids
            ];

            // on redirige le nouvel utilisateur vers son profil

            header("location: profil.php");
        }
    } else {
        $_SESSION["error"] = ["Les champs ne sont pas tous remplis"];
    }
}

?>

<?php include "header.php" ?>

<div class="titleInscription">
    <h1>S'inscrire</h1>
    <p>Vous avez déjà un compte ? <a href="connexion.php">Se connecter</a></p>


    <div class="text-danger" style="text-align:center">
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
<div class="formInscription">
    <input type="text"  name="pseudo" id="pseudo" placeholder="Pseudo">
    
    <input type="email"  name="email" id="email" placeholder="Adresse email">
   
    <input type="password"  name="password" id="pass" placeholder="Mot de passe">
   
    <input type="password"  name="confpass" id="confpass" placeholder="Confirmer mdp">
</div>
    <input type="radio"  name="sexe" value="homme"> <label class="radio"> Homme </label> 
    <input type="radio"  name="sexe" value="femme"> <label class="radio"> Femme </label> 
    <div class="formInscription">
    <input type="number"  name="taille" id="taille" min="80" max="250" 
    placeholder="Taille (en cm)">
   
    <input type="number" name="poids" id="poids" min="40" max="200" placeholder="Poids (en kg)">
  
    <button type="submit" class="btn btn-primary btn-sm">S'enregistrer</button>
  </div>

</form>
</div>
<script>
     let pass = document.getElementById("pass");
     let confpass = document.getElementById("confpass");
     confpass.addEventListener("keyup" , ()=>{
        if(pass.value === confpass.value){
            confpass.style.border = "2px solid green"
     }else if(pass.value != confpass.value){
            confpass.style.border = "2px solid red"
            if(confpass.value === ""){
                confpass.style.border = "2px solid black";
            }
     }})
     
        
     
</script>
<?php include "footer.php" ?>