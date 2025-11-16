<?php
require_once __DIR__ . '/auth.php';

function render_header(string $title = 'ZilanGroup'): void
{
    $user = current_user();
    ?>
    <!doctype html>
    <html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($title); ?> | ZilanGroup</title>
        <link rel="stylesheet" href="/public/css/style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    </head>
    <body>
    <header class="topbar">
        <div class="brand">
            <div class="logo-mark" aria-hidden="true">Z</div>
            <span class="brand-name">ZilanGroup</span>
        </div>
        <nav class="nav-links">
            <a href="/index.php">Home</a>
            <a href="/login.php">Login</a>
            <a href="/register.php">Registrieren</a>
            <a href="/admin.php" class="nav-cta">Admin</a>
        </nav>
        <div class="user-chip">
            <?php if ($user): ?>
                <span class="user-name"><?php echo htmlspecialchars($user['name']); ?></span>
                <span class="badge"><?php echo htmlspecialchars(ucfirst($user['role'])); ?></span>
                <a class="logout" href="/logout.php">Logout</a>
            <?php else: ?>
                <span class="user-name">Gast</span>
                <a class="login" href="/login.php">Anmelden</a>
            <?php endif; ?>
        </div>
    </header>
    <main class="page">
    <?php
}

function render_footer(): void
{
    ?>
    </main>
    <footer class="footer">
        <div>
            <strong>ZilanGroup</strong> &mdash; Digitale Lösungen inspiriert von <a href="https://zilan.dev" target="_blank" rel="noreferrer">zilan.dev</a>
        </div>
        <div class="footer-links">
            <a href="mailto:hello@zilandev.com">Kontakt</a>
            <a href="https://zilan.dev" target="_blank" rel="noreferrer">Website</a>
        </div>
    </footer>
    </body>
    </html>
    <?php
}
?>
