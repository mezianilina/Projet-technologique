<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Quiz</title>
    <style>
        body {
            background: 
                linear-gradient(to right, rgba(15, 15, 46, 0.85), rgba(26, 26, 64, 0.85)),
                url('https://images.unsplash.com/photo-1581093458790-4e27b48ff24e') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
            color: white;
            text-align: center;
            padding-top: 100px;
        }

        h1 {
            font-size: 48px;
            margin-bottom: 50px;
        }

        .btn {
            background: linear-gradient(to right, #e94560, #d72f43);
            border: none;
            border-radius: 10px;
            padding: 15px 30px;
            margin: 15px;
            font-size: 18px;
            cursor: pointer;
            color: white;
            transition: 0.3s;
        }

        .btn:hover {
            transform: scale(1.05);
            background: #fff;
            color: #e94560;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .footer {
            position: absolute;
            bottom: 10px;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #aaa;
        }
    </style>
</head>
<body>
    <h1>Bienvenue au Quiz PIN</h1>

    <div class="container">
        <a href="admin/login.php"><button class="btn">👨‍💼 Espace Admin</button></a>
        <a href="quiz-3d-pin/join_quiz.php"><button class="btn">🎮 Rejoindre un Quiz</button></a>
    </div>

    <div class="footer">
        &copy; <?= date('Y') ?> - Projet de Quiz Dynamique
    </div>
</body>
</html>
