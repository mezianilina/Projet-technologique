<?php 
require_once __DIR__ . '/../quiz-3d-pin/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$error = '';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titre = trim($_POST['titre']);
  $pin = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6);

  if (!$titre) {
    $error = "Le titre est requis.";
  } else {
    $stmt = $pdo->prepare("INSERT INTO quiz (titre, created_by, pin_code, is_active) VALUES (?, ?, ?, 0)");
    $stmt->execute([$titre, $_SESSION['admin_id'], $pin]);
    $msg = "Quiz créé avec succès. Code PIN: $pin";
  }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Créer Quiz</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background: linear-gradient(135deg, #0f0c29, #302b63, #e94560);
      color: #f0f0f0;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      min-height: 100vh;
      padding: 50px 20px;
      perspective: 1000px;
    }

    h2 {
      font-size: 3rem;
      margin-bottom: 40px;
      color: #ff4b7d;
      text-shadow: 0 0 12px rgba(255, 75, 125, 0.7);
      transform: translateZ(30px);
    }

    form {
      background: rgba(255, 255, 255, 0.07);
      padding: 40px 50px;
      border-radius: 20px;
      box-shadow:
        0 4px 30px rgba(255, 75, 125, 0.3),
        inset 0 0 60px rgba(255, 255, 255, 0.08);
      width: 320px;
      display: flex;
      flex-direction: column;
      gap: 30px;
      transform: translateZ(20px);
      transition: box-shadow 0.3s ease;
    }

    form:hover {
      box-shadow:
        0 8px 60px rgba(255, 75, 125, 0.5),
        inset 0 0 80px rgba(255, 255, 255, 0.15);
    }

    input[type="text"] {
      padding: 15px 20px;
      border-radius: 12px;
      border: none;
      outline: none;
      font-size: 1.1rem;
      font-weight: 500;
      background: rgba(255, 255, 255, 0.15);
      color: #fff;
      box-shadow: 0 4px 15px rgba(255, 111, 145, 0.4);
      transition: background 0.3s ease;
    }

    input[type="text"]::placeholder {
      color: #ffb6c1;
      opacity: 0.8;
    }

    input[type="text"]:focus {
      background: rgba(255, 255, 255, 0.3);
      box-shadow: 0 0 12px 4px #ff4b7d;
      color: #fff;
    }

    button {
      padding: 15px 25px;
      border-radius: 15px;
      border: none;
      font-size: 1.2rem;
      font-weight: 700;
      color: #fff;
      background: linear-gradient(45deg, #ff4b7d, #e94560);
      cursor: pointer;
      box-shadow: 0 6px 15px rgba(233, 69, 96, 0.5);
      transition: background 0.3s ease, box-shadow 0.3s ease;
    }

    button:hover {
      background: linear-gradient(45deg, #e94560, #ff4b7d);
      box-shadow: 0 10px 25px rgba(233, 69, 96, 0.9);
    }

    p {
      margin-top: 20px;
      font-size: 1rem;
      color: #ffbb33;
      text-align: center;
      text-shadow: 0 0 5px #ffbb33;
    }

    p a {
      color: #ff4b7d;
      font-weight: 600;
      text-decoration: none;
      text-shadow: 0 0 8px #ff4b7d;
      transition: color 0.3s ease;
    }

    p a:hover {
      color: #ffd4e2;
      text-shadow: 0 0 12px #ffd4e2;
    }

    p[style*="color:red"] {
      color: #e74c3c !important;
      text-shadow: 0 0 8px #e74c3c;
    }

    p[style*="color:green"] {
      color: #2ecc71 !important;
      text-shadow: 0 0 8px #2ecc71;
    }
  </style>
</head>
<body>
<h2>Créer un Quiz</h2>
<form method="post" novalidate>
  <input type="text" name="titre" placeholder="Titre du quiz" required>
  <button type="submit">Créer</button>
</form>

<?php if ($error): ?><p style="color:red;"><?=$error?></p><?php endif; ?>
<?php if ($msg): ?><p style="color:green;"><?=$msg?></p><?php endif; ?>

<p><a href="add_question.php">Ajouter des questions</a></p>
<p><a href="logout.php">Déconnexion</a></p>
</body>
</html>
