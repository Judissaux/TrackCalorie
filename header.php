
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "accueil" ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" >
    <link rel="stylesheet" href="style.css">    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>    
  
</head>
<body>
    <div class="centerHeader">
    <h1 >Calories Tracker</h1>
    <?php if($_SESSION["user"]){ ?>
    <a href="deconnexion.php">Se deconnecter</a>
        <?php } ?>   
    </div>

