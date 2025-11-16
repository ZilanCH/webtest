<?php
require_once __DIR__ . '/inc/layout.php';
require_admin();

$users = load_users();
$featured = load_featured();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create_user') {
        $result = register_user(
            [
                'email' => $_POST['email'] ?? '',
                'name' => $_POST['name'] ?? '',
                'password' => $_POST['password'] ?? '',
                'title' => $_POST['title'] ?? '',
                'bio' => $_POST['bio'] ?? '',
            ],
            $_POST['role'] ?? 'member'
        );

        if ($result['success']) {
            $users = load_users();
            $message = 'Neuer Nutzer angelegt: ' . htmlspecialchars($_POST['email']);
        } else {
            $error = $result['message'];
        }
    } elseif ($action === 'update_roles') {
        $roles = $_POST['roles'] ?? [];
        $updated = 0;
        foreach ($roles as $email => $role) {
            $role = in_array($role, ['admin', 'member'], true) ? $role : 'member';
            if (update_user_fields($email, ['role' => $role])) {
                $updated++;
            }
        }
        $users = load_users();
        $message = 'Rollen aktualisiert für ' . $updated . ' Account(s).';
    } elseif ($action === 'update_featured') {
        $featured = array_keys($_POST['featured'] ?? []);
        save_featured($featured);
        $message = 'Featured-Liste aktualisiert.';
    }
}

render_header('Admin');
?>
<section class="panel">
    <div class="panel-header">
        <div>
            <p class="eyebrow">Dashboard</p>
            <h1>Admin-Bereich</h1>
            <p class="lede">Rollen verwalten, neue Accounts anlegen und gezielt bestimmen, wer auf der Startseite erscheint.</p>
        </div>
        <div class="badge">Admin</div>
    </div>
    <?php if ($message): ?>
        <div class="alert success"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="admin-grid">
        <form method="post" class="list-card">
            <input type="hidden" name="action" value="update_roles">
            <h3>Accounts &amp; Rollen</h3>
            <p class="muted">Passe Rollen für bestehende Nutzer an. Änderungen gelten sofort.</p>
            <ul class="list">
                <?php foreach ($users as $user): ?>
                    <li class="list-item">
                        <div>
                            <strong><?php echo htmlspecialchars($user['name']); ?></strong>
                            <p class="muted"><?php echo htmlspecialchars($user['email']); ?> &mdash; <?php echo htmlspecialchars($user['title']); ?></p>
                        </div>
                        <div class="tags">
                            <select name="roles[<?php echo htmlspecialchars($user['email']); ?>]">
                                <option value="member" <?php echo ($user['role'] ?? 'member') === 'member' ? 'selected' : ''; ?>>member</option>
                                <option value="admin" <?php echo ($user['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>admin</option>
                            </select>
                            <span class="badge subtle"><?php echo htmlspecialchars($user['role'] ?? 'member'); ?></span>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button class="button primary" type="submit">Rollen speichern</button>
        </form>

        <form method="post">
            <input type="hidden" name="action" value="update_featured">
            <h3>Featured Nutzer</h3>
            <p class="muted">Wähle bis zu drei Profile für die Startseite aus.</p>
            <div class="toggles">
                <?php foreach ($users as $user): ?>
                    <label class="toggle">
                        <input type="checkbox" name="featured[<?php echo htmlspecialchars($user['email']); ?>]" value="1" <?php echo in_array($user['email'], $featured, true) ? 'checked' : ''; ?>>
                        <span>
                            <strong><?php echo htmlspecialchars($user['name']); ?></strong>
                            <small><?php echo htmlspecialchars($user['title']); ?></small>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>
            <button class="button primary" type="submit">Featured aktualisieren</button>
        </form>
    </div>
</section>

<section class="panel narrow">
    <div class="panel-header">
        <div>
            <p class="eyebrow">Account anlegen</p>
            <h2>Neuen Nutzer erstellen</h2>
            <p class="lede">Erfasse manuell neue Team-Mitglieder mit Rolle und Profiltext.</p>
        </div>
    </div>
    <form class="form" method="post">
        <input type="hidden" name="action" value="create_user">
        <div class="grid-two">
            <label>
                <span>Name</span>
                <input type="text" name="name" required placeholder="Vor- und Nachname" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            </label>
            <label>
                <span>E-Mail</span>
                <input type="email" name="email" required placeholder="person@zilandev.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </label>
        </div>
        <div class="grid-two">
            <label>
                <span>Passwort</span>
                <input type="password" name="password" required placeholder="Sicheres Passwort wählen">
            </label>
            <label>
                <span>Rolle</span>
                <select name="role">
                    <option value="member" <?php echo (($_POST['role'] ?? '') === 'admin') ? '' : 'selected'; ?>>member</option>
                    <option value="admin" <?php echo (($_POST['role'] ?? '') === 'admin') ? 'selected' : ''; ?>>admin</option>
                </select>
            </label>
        </div>
        <label>
            <span>Funktion</span>
            <input type="text" name="title" placeholder="Product Manager" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
        </label>
        <label>
            <span>Bio</span>
            <textarea name="bio" rows="3" placeholder="Kurzbeschreibung"><?php echo htmlspecialchars($_POST['bio'] ?? ''); ?></textarea>
        </label>
        <button class="button primary" type="submit">Nutzer speichern</button>
    </form>
</section>
<?php
render_footer();
