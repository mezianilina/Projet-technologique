<?php 
require_once __DIR__ . '/../quiz-3d-pin/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$admin_id = $_SESSION['admin_id'];

if (isset($_GET['toggle']) && isset($_GET['id'])) {
    $quiz_id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT is_active FROM quiz WHERE id = ? AND created_by = ?");
    $stmt->execute([$quiz_id, $admin_id]);
    $quiz = $stmt->fetch();
    if ($quiz) {
        $new_state = $quiz['is_active'] ? 0 : 1;
        $stmt = $pdo->prepare("UPDATE quiz SET is_active = ? WHERE id = ?");
        $stmt->execute([$new_state, $quiz_id]);
    }
    header('Location: manage_quizzes.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM quiz WHERE created_by = ?");
$stmt->execute([$admin_id]);
$quizzes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Quizzes</title>
    <style>
        /* Reset simple */
        * {
          box-sizing: border-box;
        }

        body {
          background: linear-gradient(135deg, #1f1c2c,rgb(240, 39, 120));
          color: #eee;
          font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
          padding: 40px;
          min-height: 100vh;
          display: flex;
          flex-direction: column;
          align-items: center;
        }

        h2 {
          font-size: 3rem;
          margin-bottom: 40px;
          text-align: center;
          color:rgb(194, 42, 118);
          text-shadow: 0 0 10px rgba(255, 111, 145, 0.7);
          letter-spacing: 1.2px;
          animation: glow 2.5s ease-in-out infinite alternate;
        }

        @keyframes glow {
          from {
            text-shadow: 0 0 10px rgba(227, 58, 97, 0.7);
          }
          to {
            text-shadow: 0 0 25px rgb(212, 78, 109);
          }
        }

        table {
          width: 90vw;
          max-width: 900px;
          border-collapse: separate;
          border-spacing: 0 15px;
          box-shadow: 0 8px 32px rgba(183, 34, 119, 0.37);
          border-radius: 15px;
          overflow: hidden;
          background: rgba(255, 255, 255, 0.1);
          backdrop-filter: blur(10px);
        }

        thead tr {
          background: linear-gradient(90deg, #ff6f91, #ff9671);
          color: white;
          font-weight: 600;
          text-transform: uppercase;
          letter-spacing: 0.05em;
        }

        thead th {
          padding: 20px 15px;
        }

        tbody tr {
          background: rgba(20, 17, 104, 0.15);
          border-radius: 12px;
          transition: transform 0.3s ease, background-color 0.3s ease;
          cursor: default;
        }

        tbody tr:hover {
          background: rgba(83, 110, 162, 0.3);
          transform: translateY(-5px) scale(1.02);
          box-shadow: 0 10px 20px rgba(255, 111, 145, 0.3);
        }

        tbody td {
          padding: 15px 10px;
          text-align: center;
          color: #fff;
          font-weight: 500;
          font-size: 1rem;
        }

        a {
          color: #ff6f91;
          font-weight: 600;
          text-decoration: none;
          padding: 6px 14px;
          border-radius: 8px;
          transition: background-color 0.3s ease, color 0.3s ease;
          border: 2px solid transparent;
          display: inline-block;
        }

        a:hover {
          background-color: #ff6f91;
          color: #fff;
          box-shadow: 0 0 10px #ff6f91;
        }

        .links {
          margin-top: 40px;
          text-align: center;
        }

        .links a {
          margin: 0 15px;
          background: linear-gradient(135deg, #ff6f91, #ff9671);
          box-shadow: 0 6px 15px rgba(255, 111, 145, 0.5);
          font-size: 1.1rem;
          padding: 14px 30px;
          border-radius: 12px;
          transition: box-shadow 0.4s ease;
        }

        .links a:hover {
          box-shadow: 0 10px 30px rgba(255, 111, 145, 0.9);
        }

        /* Responsive */

        @media (max-width: 768px) {
          table {
            width: 100vw;
            border-spacing: 0 10px;
          }

          thead tr {
            font-size: 0.9rem;
          }

          tbody td {
            font-size: 0.85rem;
            padding: 12px 8px;
          }

          .links a {
            padding: 12px 20px;
            font-size: 1rem;
          }
        }
    </style>
</head>
<body>

<h2>Gestion des Quizzes</h2>

<table>
    <thead>
        <tr>
            <th>Titre</th>
            <th>PIN</th>
            <th>Actif</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($quizzes as $quiz): ?>
        <tr>
            <td><?= htmlspecialchars($quiz['titre']) ?></td>
            <td><?= htmlspecialchars($quiz['pin_code']) ?></td>
            <td><?= $quiz['is_active'] ? 'Oui' : 'Non' ?></td>
            <td>
                <a href="manage_quizzes.php?toggle=1&id=<?= $quiz['id'] ?>">
                    <?= $quiz['is_active'] ? 'Désactiver' : 'Activer' ?>
                </a> |
                <a href="add_question.php?quiz_id=<?= $quiz['id'] ?>">Ajouter questions</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="links">
    <a href="create_quiz.php">Créer un nouveau quiz</a>
    <a href="logout.php">Déconnexion</a>
</div>

</body>
</html>