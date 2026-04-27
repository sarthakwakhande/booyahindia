<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'BooyahIndia', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<header class="topbar">
    <div class="brand">BooyahIndia</div>
    <nav>
        <a href="/">Home</a>
        <a href="/tournaments">Tournaments</a>
        <a href="/leaderboard">Leaderboard</a>
        <a href="/wallet">Wallet</a>
        <a href="/dashboard">Admin</a>
        <a href="/login">Login</a>
    </nav>
</header>
<main class="container">
