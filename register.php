<?php
require_once __DIR__ . '/inc/layout.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = register_user(
        [
            'email' => $_POST['email'] ?? '',
            'name' => $_POST['name'] ?? '',
            'password' => $_POST['password'] ?? '',
            'title' => $_POST['title'] ?? '',
            'bio' => $_POST['bio'] ?? '',
        ]
    );

    if ($result['success']) {
        $_SESSION['user'] = $result['user'];
        $success = 'Account erstellt! Du bist jetzt eingeloggt.';
        header('Location: /index.php');
        exit;
    }

    $error = $result['message'] ?? 'Registrierung fehlgeschlagen.';
}

render_header('Registrieren');
?>
<section class="panel narrow">
    <div class="panel-header">
        <div>
            <p class="eyebrow">Beitreten</p>
            <h1>Neues Konto erstellen</h1>
            <p class="lede">Registriere dich als Mitglied der ZilanGroup-Plattform. Admin-Rechte werden von bestehenden Admins vergeben.</p>
        </div>
    </div>
    <?php if ($error): ?>
        <div class="alert"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <form class="form" method="post" action="/register.php">
        <label>
            <span>Name</span>
            <input type="text" name="name" required placeholder="Dein Name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
        </label>
        <label>
            <span>E-Mail</span>
            <input type="email" name="email" required placeholder="you@zilandev.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </label>
        <label>
            <span>Passwort</span>
            <input type="password" name="password" required placeholder="Sicheres Passwort wählen">
        </label>
        <label>
            <span>Rolle</span>
            <input type="text" value="member" disabled>
            <small class="muted">Öffentliche Registrierungen werden als Member angelegt. Admins können Rollen im Dashboard anpassen.</small>
        </label>
        <label>
            <span>Bio (optional)</span>
            <textarea name="bio" rows="3" placeholder="Was treibst du bei ZilanGroup?"><?php echo htmlspecialchars($_POST['bio'] ?? ''); ?></textarea>
        </label>
        <button class="button primary" type="submit">Registrieren</button>
    </form>
</section>
<?php
render_footer();
