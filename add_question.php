<?php
require_once '../quiz-3d-pin/db.php';

$error = '';
$msg = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quiz_id = $_POST['quiz_id'] ?? '';
    $question = $_POST['question'] ?? '';
    $reponses = [
        $_POST['reponse1'] ?? '',
        $_POST['reponse2'] ?? '',
        $_POST['reponse3'] ?? '',
        $_POST['reponse4'] ?? ''
    ];
    $correct = $_POST['correct'] ?? '';

    if (empty($quiz_id) || empty($question) || empty($reponses[0]) || empty($reponses[1]) || empty($correct)) {
        $error = "Tous les champs obligatoires doivent être remplis.";
    } elseif ($correct < 1 || $correct > 4 || empty($reponses[$correct - 1])) {
        $error = "Le numéro de la réponse correcte est invalide.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO questions (quiz_id, question, correct_answer) VALUES (?, ?, ?)");
        $stmt->execute([$quiz_id, $question, $correct]);

        $question_id = $pdo->lastInsertId();

        foreach ($reponses as $index => $text) {
            if (!empty($text)) {
                $stmt = $pdo->prepare("INSERT INTO reponses (question_id, reponse_num, texte) VALUES (?, ?, ?)");
                $stmt->execute([$question_id, $index + 1, $text]);
            }
        }

        $msg = "La question a été ajoutée avec succès.";
    }
}

$stmt = $pdo->query("SELECT id, titre FROM quiz");
$quizzes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une question</title>
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
        padding: 40px 20px;
        min-height: 100vh;
        perspective: 1000px;
      }

      h2 {
        font-size: 3rem;
        margin-bottom: 30px;
        color: #ff4b7d;
        text-shadow: 0 0 10px #ff4b7d, 0 0 20px #ff7c9c;
        transform: translateZ(30px);
      }

      form {
        background: rgba(255, 255, 255, 0.08);
        padding: 35px 40px;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(255, 75, 125, 0.4),
                    inset 0 0 60px rgba(255, 255, 255, 0.1);
        width: 360px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        transform: translateZ(20px);
        transition: box-shadow 0.3s ease;
      }

      form:hover {
        box-shadow: 0 8px 40px rgba(255, 75, 125, 0.7),
                    inset 0 0 70px rgba(255, 255, 255, 0.15);
      }

      label {
        font-weight: 600;
        font-size: 1.1rem;
        color: #ffcce0;
        text-shadow: 0 0 6px #ff4b7d;
      }

      select, textarea, input[type="text"], input[type="number"] {
        border: none;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 1rem;
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        box-shadow: 0 3px 10px rgba(255, 75, 125, 0.4);
        outline: none;
        resize: vertical;
        transition: background 0.3s ease, box-shadow 0.3s ease;
      }

      select:focus, textarea:focus, input[type="text"]:focus, input[type="number"]:focus {
        background: rgba(255, 255, 255, 0.25);
        box-shadow: 0 0 15px 4px #ff4b7d;
        color: #fff;
      }

      textarea {
        font-family: inherit;
      }

      ::placeholder {
        color: #ffd2e0;
        opacity: 0.85;
      }

      button {
        background: linear-gradient(45deg, #ff4b7d, #e94560);
        border: none;
        border-radius: 20px;
        padding: 15px 0;
        color: #fff;
        font-weight: 700;
        font-size: 1.2rem;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(255, 75, 125, 0.6);
        transition: background 0.3s ease, box-shadow 0.3s ease;
        transform: translateZ(10px);
      }

      button:hover {
        background: linear-gradient(45deg, #e94560, #ff4b7d);
        box-shadow: 0 10px 30px rgba(255, 75, 125, 0.9);
      }

      p {
        margin-top: 25px;
        font-size: 1.1rem;
        text-align: center;
        text-shadow: 0 0 8px #ff7c9c;
        transform: translateZ(10px);
      }

      p[style*="color:#ff4d4d"] {
        color: #e74c3c !important;
        text-shadow: 0 0 10px #e74c3c;
      }

      p[style*="color:#4CAF50"] {
        color: #2ecc71 !important;
        text-shadow: 0 0 10px #2ecc71;
      }

      a {
        color: #ff94b8;
        text-decoration: none;
        font-weight: 600;
        text-shadow: 0 0 6px #ff4b7d;
        transition: color 0.3s ease;
      }

      a:hover {
        color: #ffe2ed;
        text-shadow: 0 0 12px #ffe2ed;
      }
    </style>
</head>
<body>

<h2>Ajouter une question</h2>

<form method="post">
    <label>Quiz :</label>
    <select name="quiz_id" required>
        <option value="">--Choisir--</option>
        <?php foreach ($quizzes as $q): ?>
            <option value="<?= $q['id'] ?>"><?= htmlspecialchars($q['titre']) ?></option>
        <?php endforeach; ?>
    </select>

    <textarea name="question" placeholder="Texte de la question" required rows="3" cols="50"></textarea>

    <input type="text" name="reponse1" placeholder="Réponse 1 (obligatoire)" required>
    <input type="text" name="reponse2" placeholder="Réponse 2 (obligatoire)" required>
    <input type="text" name="reponse3" placeholder="Réponse 3 (optionnelle)">
    <input type="text" name="reponse4" placeholder="Réponse 4 (optionnelle)">

    <label>Numéro de la réponse correcte (1-4) :</label>
    <input type="number" name="correct" min="1" max="4" required>

    <button type="submit">Ajouter</button>
</form>

<?php if (!empty($error)): ?>
    <p style="color: #ff4d4d;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($msg)): ?>
    <p style="color: #4CAF50;"><?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

<p><a href="create_quiz.php">← Retour</a></p>

</body>
</html>

