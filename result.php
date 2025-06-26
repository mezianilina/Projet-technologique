<?php
session_start();

if (!isset($_SESSION['score']) || !isset($_SESSION['quiz_id'])) {
    header('Location: index.php');
    exit;
}

$score = $_SESSION['score'];
$quiz_id = $_SESSION['quiz_id'];
$mention = '';

if ($score >= 80) {
    $mention = "Excellent";
} elseif ($score >= 60) {
    $mention = "Très Bien";
} elseif ($score >= 40) {
    $mention = "Passable";
} else {
    $mention = "Échec";
}

// Reset session pour rejouer
session_destroy();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultat du Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #1a1a2e;
            color: white;
            text-align: center;
            padding-top: 50px;
        }
        .result-box {
            background: #222;
            padding: 30px;
            border-radius: 10px;
            width: 60%;
            margin: auto;
        }
        .btn {
            margin-top: 20px;
            padding: 12px 25px;
            background-color: #e94560;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #d72f43;
        }
    </style>
</head>
<body>
    <div class="result-box">
        <h1>🎉 Résultat du Quiz</h1>
        <p><strong>Score :</strong> <?= $score ?>/100</p>
        <p><strong>Mention :</strong> <?= $mention ?></p>

        <!-- Ajoute ceci si tu veux sauvegarder le score -->
        <!-- <p>Votre score a été enregistré.</p> -->

       <a href="../index.php"><button class="btn">Retour à l'accueil</button></a>
    </div>
</body>
<body>
  <img src="data:image/webp;base64,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" alt="fond" style="position:fixed; top:0; left:0; width:100vw; height:100vh; object-fit:cover; z-index:-1;">
  
  <div>
    <!-- Ton contenu ici -->
    <h1>Bonjour !</h1>
    <p>Contenu par-dessus le fond.</p>
  </div>
</body>

</html>