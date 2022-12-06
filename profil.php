<?php
//______________________________GENERAL___________________________________//
// On démarre la session
session_start();
// on ecrit le titre du fichier pour qu'il apparaisse dans l'onglet
$title = "Profil";
// Redirection  dans la page connexion si pas de variable "user"
if (!$_SESSION["user"]) {
    header("location: connexion.php");
    exit;
}
// ________RECUPERATION DU NBR DE CALORIES MAX_____// 
if ($_SESSION["user"]["sexe"] === "homme") {
    $maxCal = 2500;
} else {
    $maxCal = 2000;
}

// Récupération du poids et de la taille 
$poids = $_SESSION["user"]["poids"];
$taille = POW($_SESSION["user"]["taille"], 2) / 100;
$imc = round($poids / $taille * 100, 1);
//_________________________FIN GENERAL______________________________________//
?>

<?php
//___________________________Code permettant le changement du poids en direct et dans la base de données__________//
$id = $_SESSION["user"]["id"];
$_SESSION["error"] = [];

if (isset($_POST["poids"])) {
    if (!empty($_POST["poids"])) {

        if ($_POST["poids"] < 40 || $_POST["poids"] > 200) {
            $_SESSION["error"][] = "Poids incorrecte";
        }

        if ($_SESSION["error"] === []) {

            $poidsModif = strip_tags(filter_var($_POST["poids"], FILTER_VALIDATE_INT));

            $_SESSION["user"]["poids"] = $poidsModif;

            require_once "bddConnect.php";

            $sql = "UPDATE `tcusers` SET `poids`=:poids WHERE `id`=:id ";

            $requete = $connexion->prepare($sql);

            $requete->bindValue(":poids", $poidsModif);
            $requete->bindValue(":id", $id);

            $requete->execute();

            header("location: profil.php");
        }
    } else {
        $_SESSION["error"] = ["Pas de valeur dans le champs Poids !"];
    }
}
//_______________________FIN DU__CODE ___________________________________//
?>

<?php include "header.php" ?>

<section class="sectionHeader">
 <h1>Bienvenue <?= ucfirst(strtolower($_SESSION["user"]["pseudo"])) ?>, </h1>
 <h3>nous sommes heureux de te compter parmi nous !!</h3>
 </section>

 <section class="sectionPresentation">
       <p> Sur cette page, tu vas connaître ton indice de masse corporelle ou IMC (beaucoup moins long et plus simple à retenir &#128521). </p>
    <p> Grâce à un calcul scientifique qui ferait rougir notre ami Albert Einstein, tu vas découvrir ton IMC dans un instant, ce n'est pas fantastique?? </p>
    <p> À la suite de cela, tu vas pouvoir modifier ton poids (si modification et oui ça peut nous aider à changer ton IMC) et tu pourras également ajouter les calories (en kcal) que tu ingères  jour par jour et voir si tu ne dépasses pas ta limite journalière. </p> 
    <p> Voilà, nous avons fait le tour, es-tu prêt à découvrir ton IMC ?? <button class=" btn btn-danger" id="btn_imc" style="--bs-btn-padding-y: .15rem; --bs-btn-padding-x: .4rem; --bs-btn-font-size: .65rem;">Clique ici!</button> pour la découvrir. </p>  
</section> 
<div class="containImc">
    <div class="imc">Ton IMC est de <?= $imc ?> </div>
    </div>
<div class="messageImcContainer">
<?php 
    if($imc < 18.5){ ?>
        <div class="messageImc"> Tu es maigre, <br> tu peux aller te faire deux ou trois MacDo par mois sans problème !!</div>
    
       <?php }else if($imc>=18.5 && $imc<=24.9){?>
        <div class="messageImc">Tu es dans la moyenne, ne change rien </div>
    
    <?php }else if($imc>=25 && $imc<=29.9){?>
        <div class="messageImc">Tu es en surpoids, <br> il est temps de te mettre au sport avant d'atteindre l'obésité !!</div>
    
  
 <?php }else if($imc>=30 && $imc<=34.9){?>
        <div class="messageImc"> Tu es obèse, <br> tu as trop abusé des bonnes choses de la vie, un peu de sport et un régime adéquat et tu retrouveras ton corps d'Apollon </div>
    
    <?php  }else if($imc>35){ ?>
        <div class="messageImc">Tu es en obésité sévère et ça devient très inquiétant, <br> consulte vite un médecin, un coach sportif et un nutritionniste pour te sortir de cette impasse! </div>
    <?php }?>
</div>

<div class="tabImc">
<img src="img/tableau imc.jpg" alt="Tableau IMC">
</div>

<div class="text-danger">
    <?php
    //______________________________________Code affichage message d'erreur _____________________________//

    if (isset($_SESSION["error"])) {
        foreach ($_SESSION["error"] as $message) {
            echo $message;
        }
        unset($_SESSION["error"]);
    }
    //_____________________________________FIN Code affichage message d'erreur _____________________________//
    ?>
</div>
<div class="changementPoids">
    Ton poids est différent ?
    <button class=" btn btn-primary" id="btn_changement_poids" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" data-bs-toggle="modal" data-bs-target="#modal1">Clique ici!</button>
    <div class="modal fade" id="modal1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header row" aria-labelledby="modification du poids">
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close" ></button>
                    <form method="post" action="#">
                        <p style="text-align:center">Modification du poids</p>
                        <div class="modal-body row g-3 ">
                            <input class="form-control col-auto" type="number" name="poids" id="poids" min="40" max="200" placeholder="min40kg/max200kg ">
                            <div class="modal-footer">
                                <button data-bs-dismiss="modal" aria-label="close" type="submit" class="btn btn-primary btn-sm ">Valider</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
<?php
//______________________________________Code php traitement de l'ajout des calories _____________________________//
$pseudo = $_SESSION["user"]["pseudo"];
$timestamp = strtotime($_POST["date"]);

if (isset($_POST["date"], $_POST["calorie"])) {
    if (!empty($_POST["date"]) && !empty($_POST["calorie"])) {
        // code pour bloquer un maximum de jour à faire !!!
        $_SESSION["error"] = [];

        if ($timestamp <= time() - 950399 || $timestamp > time()) {
            $_SESSION["error"] = ["La date ne peut pas être inférieur à 10 jour ou supérieur à la date du jour !!"];
        }
        $dates = strip_tags($_POST["date"]);


        //traitement des calories
        if ($_POST["calorie"] < 1 || $_POST["calorie"] > 3000) {
            $_SESSION["error"] = ["Erreur dans le nombre de calorie"];
        }
        $calorie = strip_tags(filter_var($_POST["calorie"], FILTER_VALIDATE_INT));

        // Passage des données dans la BDD
        if ($_SESSION["error"] === []) {
            require_once "bddConnect.php";
            // selection de la date dans la bdd
            $sql = "SELECT * FROM `suivical` WHERE `date` = :dates AND `id_user` = :id ";

            $requete = $connexion->prepare($sql);

            $requete->bindValue(":dates", $dates);
            $requete->bindValue(":id", $id);

            $requete->execute();

            $result = $requete->fetch();
            $ajout = $result["calorie"] + $calorie;

            if($result["calorie"] === 2500){
                $_SESSION["error"] = ["Attention, tu as depassé tes $maxCal kcal journalière"];
            }
            
            if (!$result) {
                
                $sql = "INSERT INTO `suivical`(`id_user`,`pseudo`,`date`,`calorie`) VALUES (:id,:pseudo,:dates,:calorie)";
                $requete = $connexion->prepare($sql);
                $requete->bindValue(":dates", $dates);
                $requete->bindValue(":id", $id);
                $requete->bindValue(":pseudo", $pseudo);
                $requete->bindValue(":calorie", $calorie);
                $requete->execute();
                
            } else if ($result && $ajout <= 5000) {

                $sql = "UPDATE `suivical` set `calorie` = :ajout WHERE `date` = :dates AND `id_user` = :id ";
                $requete = $connexion->prepare($sql);
                $requete->bindValue(":dates", $dates);
                $requete->bindValue(":id", $id);
                $requete->bindValue(":ajout", $ajout);
                $requete->execute();
                
            }
        }
    } else {
        $_SESSION["error"] = ["Les champs ne sont pas remplies !"];
    }
}

//_____________________________________FIN Code php traitement de l'ajout des calories _____________________________//
?>


<hr>

<div class="ajoutCal">
<p> Dans cette partie, tu peux ajouter les calories que tu as ingérée et suivre ton évolution sur 10 jours </p>
<p>Pour connaitre les calories que tu as ingérées, tu peux les calculer <a href="https://www.fourchette-et-bikini.fr/outils/compteur-de-calories-repas.html" target=_blank>ici</a></p>
<form method="post" action="#myChart">
  <!-- <div class="repas">
        <label for="repas">Repas: </label> <br>
        <input type="radio" name="repas" id="repas" value="Petit déjeuner" required>        Petit-déjeuner
        <input type="radio" name="repas" id="repas" value="Déjeuner"> Déjeuner
        <input type="radio" name="repas" id="repas" value="Souper"> Souper
    </div>   -->
    <div class="text-danger">
    <?php
    //_____________________________________Code affichage message d'erreur _________________________________//
    if (isset($_SESSION["error"])) {
        foreach ($_SESSION["error"] as $message) {
            echo $message;
        }
        unset($_SESSION["error"]);
    }
    //______________________________________FIN Code affichage message d'erreur ____________________________//
    
    ?>
</div>

        <div class="formProfil">
            <label for="calorie">Calories du repas </label>
            <input type="number" name="calorie" id="calorie" min="0" max="3000" placeholder="en kcal">
     
            <label  for="date">Date</label>
            <input  type="date" name="date"  min = <?= date("Y-m-d", time() - 777600) ?>  max=<?= date("Y-m-d")?> value= <?= date("Y-m-d")?>>
       
            <button type="submit"  class="btn btn-primary btn-sm" >Valider</button>
        </div>
    </div>
</form>
<div class="chart">
    <canvas id="myChart"></canvas>
</div>
<?php //________________________________SUPPRESION DE LA DATE LA PLUS ANCIENNE __________________________//

require_once "bddConnect.php";
$sql = "SELECT COUNT(`date`) as nombre FROM `suivical` WHERE id_user = $id";
$requete = $connexion->prepare($sql);
// $requete->bindValue(":id",$id);
$requete->execute();
$resultat = $requete->fetch();

if ($resultat["nombre"] > 10) {
    require_once "bddConnect.php";
    $sql = "DELETE FROM `suivical` ORDER BY `date`LIMIT 1";
    $requete = $connexion->prepare($sql);
    $requete->execute();

};

//________________________________SUPPRESION DE LA DATE LA PLUS ANCIENNE __________________________// 
?>
<?php

//______________________RECUPERATION DES DONNEES DE LA BDD POUR FAIRE LE GRAPH ___________________//


$sql1 = "SELECT `date`, `calorie`  FROM `suivical` WHERE `id_user`= :id ORDER BY `date` ASC ";

$query = $connexion->prepare($sql1);
$query->bindValue(":id", $id);
$query->execute();
$donnees = $query->fetchAll();
foreach ($donnees as $donnee) {
    $dateGraph[] = $donnee["date"];
    $calorieGraph[] = $donnee["calorie"];
}
// Boucle pour determiner les couleurs pour le nbr de calorie MAX
foreach ($calorieGraph as $cal) {
    if ($cal > $maxCal) {
        $couleur = "red";
    } else {
        $couleur = "green";
    }
    $color[] = $couleur;
}


//_______________________FIN RECUPERATION DES DONNEES DE LA BDD POUR FAIRE LE GRAPH ____________________//
?>


<script>
    let maxCal = <?= json_encode($maxCal) ?>;
    const labels = <?= json_encode($dateGraph) ?>;
    let color = <?= json_encode($color) ?>;

    const data = {
        labels: labels,

        datasets: [{
            label: 'Mes Kcal ingérées sur 10 jours',
            maxBarThickness: 40,
            backgroundColor: color,
            // borderColor: 'red',
            data: <?= json_encode($calorieGraph) ?>,
        }]
    };

    const config = {
        type: 'bar',
        data: data,
        options: {
            // plugins: {
            //     legend: {
            //         labels: {
            //             color: "white",
            //         }
            //     }
            // },
            scales: {
                y: {
                 
                    suggestedMax: 5000,
                    suggestedMin: 0,
                }
            }
        }
    };
</script>


<script>
    const myChart = new Chart(
        document.getElementById('myChart'),
        config
    );
</script>


<?php include "footer.php" ?>