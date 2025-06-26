<?php
require 'db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pin = trim($_POST['pin'] ?? '');
    if (!$pin) {
        $error = "Veuillez entrer un code PIN.";
    } else {
        $stmt = $pdo->prepare("SELECT id, title FROM quiz WHERE pin_code = ? AND is_active = 1");
        $stmt->execute([$pin]);
        $quiz = $stmt->fetch();
        if ($quiz) {
            $_SESSION['quiz_id'] = $quiz['id'];
            $_SESSION['quiz_title'] = $quiz['title'];
            $_SESSION['question_index'] = 0;
            $_SESSION['score'] = 0;
            header('Location: quiz.php');
            exit;
        } else {
            $error = "Code PIN invalide ou quiz inactif.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Rejoindre un Quiz</title>
  <style>
    body {
      background: linear-gradient(135deg, #1a1a2e, #16213e);
      font-family: 'Segoe UI', sans-serif;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }
    .box {
      background: rgba(255,255,255,0.05);
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.5);
      text-align: center;
      width: 320px;
    }
    h2 {
      margin-bottom: 20px;
    }
    input[type="text"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: none;
      border-radius: 10px;
      background: rgba(255,255,255,0.1);
      color: #fff;
      font-size: 16px;
    }
    button {
      width: 100%;
      padding: 12px;
      background: #e94560;
      color: white;
      border: none;
      border-radius: 10px;
      font-weight: bold;
      cursor: pointer;
      font-size: 16px;
    }
    button:hover {
      background: #c0392b;
    }
    .error {
      background-color: #ffbaba;
      color: #a94442;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <div class="box">
    <h2>Entrer le code PIN</h2>
    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" id="joinForm">
      <input type="text" name="pin" maxlength="6" placeholder="Code PIN" required autofocus>
      <button type="submit">Rejoindre</button>
    </form>
  </div>
  
</body>
</html>