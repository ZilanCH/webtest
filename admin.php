<?php
require_once __DIR__ . '/inc/layout.php';
require_admin();

$users = load_users();
$featured = load_featured();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $featured = array_keys($_POST['featured'] ?? []);
    save_featured($featured);
    $message = 'Featured-Liste aktualisiert.';
}

render_header('Admin');
?>
<section class="panel">
    <div class="panel-header">
        <div>
            <p class="eyebrow">Dashboard</p>
            <h1>Admin-Bereich</h1>
            <p class="lede">Verwalte Accounts und steuere, wer auf der Startseite erscheint.</p>
        </div>
        <div class="badge">Admin</div>
    </div>
    <?php if ($message): ?>
        <div class="alert success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form method="post" class="admin-grid">
        <div>
            <h3>Accounts</h3>
            <p class="muted">Login-Daten sind dateibasiert hinterlegt. Für eine Demo genügt ein Reload.</p>
            <ul class="list">
                <?php foreach ($users as $user): ?>
                    <li class="list-item">
                        <div>
                            <strong><?php echo htmlspecialchars($user['name']); ?></strong>
                            <p class="muted"><?php echo htmlspecialchars($user['email']); ?> &mdash; <?php echo htmlspecialchars($user['title']); ?></p>
                        </div>
                        <div class="tags">
                            <span class="badge subtle"><?php echo htmlspecialchars($user['role']); ?></span>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div>
            <h3>Featured Nutzer</h3>
            <p class="muted">Wähle bis zu drei Profile für die Home-Page aus.</p>
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
        </div>
    </form>
</section>
<?php
render_footer();
