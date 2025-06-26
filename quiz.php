<?php
require 'db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['quiz_id'])) {
    header('Location: join_quiz.php');
    exit;
}

$quiz_id = $_SESSION['quiz_id'];
$index = $_SESSION['question_index'];
$score = $_SESSION['score'];

$stmt = $pdo->prepare("SELECT q.id as question_id, q.question, r.id as rep_id, r.reponse FROM questions q JOIN reponses r ON q.id = r.question_id WHERE q.quiz_id = ? ORDER BY q.id, r.id");
$stmt->execute([$quiz_id]);
$rows = $stmt->fetchAll();

$questions = [];
foreach ($rows as $row) {
    $qid = $row['question_id'];
    if (!isset($questions[$qid])) {
        $questions[$qid] = [
            'question' => $row['question'],
            'answers' => []
        ];
    }
    $questions[$qid]['answers'][$row['rep_id']] = $row['reponse'];
}

$questions = array_values($questions);

if ($index >= count($questions)) {
    header('Location: result.php');
    exit;
}

$current_question = $questions[$index];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rep_id = $_POST['rep_id'] ?? null;

    $stmt = $pdo->prepare("SELECT est_correct FROM reponses WHERE id = ?");
    $stmt->execute([$rep_id]);
    $rep = $stmt->fetch();

    if ($rep && $rep['est_correct']) {
        $_SESSION['score']++;
    }

    $_SESSION['question_index']++;
    header('Location: quiz.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Quiz : Question <?= ($index + 1) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #222;
            color: #eee;
            padding: 20px;
        }
        .question-box {
            background: linear-gradient(45deg,rgb(128, 28, 84),rgb(96, 48, 78));
            padding: 20px;
            border-radius: 15px;
            max-width: 700px;
            margin: auto;
            perspective: 800px;
        }
        .question-box:hover {
            transform: rotateY(15deg) rotateX(10deg);
            transition: transform 0.5s;
        }
        .answers button {
            margin: 8px 0;
            width: 100%;
            padding: 12px;
            font-size: 18px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            background:rgb(22, 11, 82);
            color: white;
            transition: background 0.3s;
        }
        .answers button:hover {
            background:rgb(45, 71, 143);
        }
        .timer {
            font-weight: bold;
            font-size: 22px;
            text-align: center;
            margin-bottom: 15px;
            color:rgb(143, 31, 123);
        }
        .answers button.selected {
            background-color: #22c55e !important;
            transform: scale(1.05);
            box-shadow: 0 0 12px #22c55e;
        }

        /* ✅ Loader style */
        .loader {
            border: 3px solid #fff;
            border-top: 3px solid transparent;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            display: inline-block;
            animation: spin 0.7s linear infinite;
            vertical-align: middle;
            margin-right: 8px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .progress-container {
            height: 8px;
            background: #444;
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 15px;
        }
        .progress-bar {
            height: 100%;
            width: 0%;
            background: #22c55e;
            transition: width 0.3s ease-in-out;
        }
    </style>
</head>
<body>

<h2><?= htmlspecialchars($_SESSION['quiz_title']) ?></h2>

<div class="question-box">
    <div class="progress-container">
        <div class="progress-bar" id="progress-bar"></div>
    </div>
    <div class="timer" id="timer">15 secondes restantes</div>
    <form method="post" id="quiz-form">
        <p><strong>Question <?= ($index + 1) ?> / <?= count($questions) ?></strong></p>
        <p><?= htmlspecialchars($current_question['question']) ?></p>
        <div class="answers">
            <?php foreach ($current_question['answers'] as $rid => $text): ?>
                <button type="submit" name="rep_id" value="<?= $rid ?>" tabindex="0"><?= htmlspecialchars($text) ?></button>
            <?php endforeach; ?>
        </div>
    </form>
</div>

<audio id="click-sound" src="assets/sounds/click.mp3" preload="auto"></audio>

<script>
let timeLeft = 15;

function updateTimer() {
    if (timeLeft <= 0) {
        document.getElementById('quiz-form').submit();
    } else {
        document.getElementById('timer').textContent = timeLeft + " secondes restantes";
        timeLeft--;
        setTimeout(updateTimer, 1000);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    updateTimer();

    const buttons = document.querySelectorAll('.answers button');
    const form = document.getElementById('quiz-form');
    const sound = document.getElementById('click-sound');

    const progressBar = document.getElementById('progress-bar');
    const total = <?= count($questions) ?>;
    const current = <?= $index + 1 ?>;
    const percent = Math.round((current / total) * 100);
    progressBar.style.width = percent + '%';

    buttons.forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();

            try {
                sound.currentTime = 0;
                sound.play();
            } catch (e) {}

            buttons.forEach(b => {
                b.disabled = true;
                b.style.opacity = 0.5;
            });

            btn.classList.add('selected');
            btn.innerHTML = `<span class="loader"></span> Vérification...`;

            setTimeout(() => {
                btn.click();
            }, 3000);
        });
    });
});
</script>


</body>
</html>
