<?php
require_once __DIR__ . '/inc/layout.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = authenticate($email, $password);
    if ($user) {
        $_SESSION['user'] = $user;
        header('Location: /admin.php');
        exit;
    }
    $error = 'Login fehlgeschlagen. Bitte prüfe deine Zugangsdaten.';
}

render_header('Login');
?>
<section class="panel narrow">
    <div class="panel-header">
        <div>
            <p class="eyebrow">Zugang</p>
            <h1>Anmelden</h1>
            <p class="lede">Nutze deine ZilanGroup-Credentials, um das Dashboard zu betreten. Demo-Zugang: <strong>admin@zilandev.com / admin123</strong>.</p>
        </div>
    </div>
    <?php if ($error): ?>
        <div class="alert"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form class="form" method="post" action="/login.php">
        <label>
            <span>E-Mail</span>
            <input type="email" name="email" required placeholder="admin@zilandev.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </label>
        <label>
            <span>Passwort</span>
            <input type="password" name="password" required placeholder="Passwort eingeben">
        </label>
        <button class="button primary" type="submit">Login</button>
    </form>
</section>
<?php
render_footer();
