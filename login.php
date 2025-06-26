<?php 
require_once __DIR__ . '/../quiz-3d-pin/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';

  $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ? AND type = 'admin'");
  $stmt->execute([$email]);
  $admin = $stmt->fetch();

  if ($admin && password_verify($password, $admin['mot_de_passe'])) {
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_name'] = $admin['nom'];
    header('Location: manage_quizzes.php');
    exit;
  } else {
    $error = "Email ou mot de passe invalide";
  }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion Admin</title>
  <style>
    /* Corps de page avec dégradé et perspective 3D */
    body {
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, #1a1a2e, #16213e);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #fff;
      perspective: 1200px;
    }

    h2 {
      color: #e94560;
      font-size: 2.5rem;
      margin-bottom: 30px;
      text-align: center;
      text-shadow: 0 4px 10px rgba(233, 69, 96, 0.8);
      transform-style: preserve-3d;
      transform: translateZ(40px);
    }

    form {
      background: rgba(255, 255, 255, 0.05);
      border-radius: 20px;
      padding: 40px 50px;
      box-shadow:
        0 10px 20px rgba(0, 0, 0, 0.8),
        0 0 40px #e94560 inset;
      width: 320px;
      transform-style: preserve-3d;
      transform: rotateX(5deg) rotateY(-10deg);
      transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1),
                  box-shadow 0.5s;
    }

    form:hover {
      transform: rotateX(0deg) rotateY(0deg) scale(1.05);
      box-shadow:
        0 15px 35px rgba(233, 69, 96, 0.8),
        0 0 60px #e94560 inset;
    }

    input[type="email"],
    input[type="password"] {
      display: block;
      width: 100%;
      padding: 15px 18px;
      margin-bottom: 20px;
      border-radius: 15px;
      border: none;
      background: rgba(255, 255, 255, 0.12);
      color: white;
      font-size: 1.1rem;
      box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.2);
      transition: background 0.3s ease, box-shadow 0.3s ease;
      outline: none;
      font-weight: 600;
    }

    input[type="email"]:focus,
    input[type="password"]:focus {
      background: rgba(255, 255, 255, 0.25);
      box-shadow: 0 0 10px 3px #e94560;
    }

    button {
      width: 100%;
      padding: 15px 0;
      border: none;
      border-radius: 15px;
      background: linear-gradient(90deg, #e94560, #d72f43);
      color: white;
      font-weight: 700;
      font-size: 1.2rem;
      cursor: pointer;
      box-shadow: 0 8px 20px rgba(233, 69, 96, 0.6);
      transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
      user-select: none;
    }

    button:hover {
      background: linear-gradient(90deg, #ff4b5c, #e94560);
      transform: scale(1.08);
      box-shadow: 0 12px 30px rgba(255, 75, 92, 0.9);
    }

    .error {
      margin-top: 20px;
      background: rgba(255, 0, 0, 0.15);
      color: #ff4b5c;
      padding: 15px;
      border-radius: 15px;
      font-weight: 700;
      text-align: center;
      box-shadow: 0 0 15px #ff4b5c;
      user-select: none;
      max-width: 320px;
      margin-left: auto;
      margin-right: auto;
    }

  </style>
</head>
<body>

  <div>
    <h2>Connexion Admin</h2>
    <form method="post" autocomplete="off" spellcheck="false" novalidate>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Mot de passe" required>
      <button type="submit">Se connecter</button>
    </form>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
  </div>

</body>
</html>
