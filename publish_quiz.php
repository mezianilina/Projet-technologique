<?php 
require_once __DIR__ . '/../quiz-3d-pin/db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$admin_id = $_SESSION['admin_id'];

if (isset($_GET['quiz_id']) && isset($_GET['action'])) {
    $quiz_id = intval($_GET['quiz_id']);
    $action = $_GET['action'];

    $stmt = $pdo->prepare("SELECT * FROM quiz WHERE id = ? AND created_by = ?");
    $stmt->execute([$quiz_id, $admin_id]);
    $quiz = $stmt->fetch();

    if (!$quiz) {
        die('
        <html>
        <head><title>Erreur</title>
        <style>
            body {
                background: linear-gradient(135deg, #1a1a2e, #16213e);
                color: #e94560;
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .box {
                background: rgba(255,255,255,0.05);
                padding: 30px;
                border-radius: 15px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.5);
                transform: rotateX(5deg);
            }
        </style>
        </head>
        <body>
            <div class="box"><h2>❌ Quiz non trouvé ou accès refusé.</h2></div>
        </body>
        </html>
        ');
    }

    if ($action === 'activate') {
        $stmt = $pdo->prepare("UPDATE quiz SET is_active = 1 WHERE id = ?");
        $stmt->execute([$quiz_id]);
    } elseif ($action === 'deactivate') {
        $stmt = $pdo->prepare("UPDATE quiz SET is_active = 0 WHERE id = ?");
        $stmt->execute([$quiz_id]);
    }

    // Message de confirmation (optionnel)
    echo '
    <html>
    <head><title>Succès</title>
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            color: #fff;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .confirmation {
            background: rgba(255,255,255,0.05);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            transform: rotateY(3deg);
            text-align: center;
        }
        .confirmation a {
            color: #e94560;
            font-weight: bold;
            text-decoration: none;
            display: block;
            margin-top: 20px;
        }
    </style>
    </head>
    <body>
        <div class="confirmation">
            <h2>✅ Action "' . htmlspecialchars($action) . '" appliquée avec succès</h2>
            <a href="manage_quizzes.php">⬅ Retour à la gestion des quiz</a>
        </div>
    </body>
    </html>
    ';
    exit;
} else {
    echo '
    <html>
    <head><title>Erreur</title>
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            color: #ff4b5c;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background: rgba(255,255,255,0.05);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            transform: rotateX(5deg);
        }
    </style>
    </head>
    <body>
        <div class="box"><h2>⚠️ Paramètres invalides.</h2></div>
    </body>
    </html>
    ';
}
