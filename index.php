<?php
require_once __DIR__ . '/inc/layout.php';

$featuredEmails = load_featured();
$users = load_users();
$featuredUsers = array_values(array_filter($users, fn($u) => in_array($u['email'], $featuredEmails, true)));

render_header('Home');
?>
<section class="hero">
    <div>
        <p class="eyebrow">ZilanGroup</p>
        <h1>Digitale Lösungen mit Haltung</h1>
        <p class="lede">Strategie, Design und Technologie greifen bei ZilanGroup ineinander. Inspiriert von zilan.dev setzen wir auf klare Journeys, sichere Accounts und Rollen, die Teams wirklich steuern.</p>
        <div class="cta-row">
            <a class="button primary" href="/register.php">Jetzt Account anlegen</a>
            <a class="button ghost" href="/login.php">Bestehenden Zugang öffnen</a>
        </div>
        <div class="meta">Produktideen, Markenführung und Umsetzung aus einem Guss &mdash; präzise orchestriert.</div>
    </div>
    <div class="hero-card">
        <div class="stat">
            <span class="label">Expertenkontakte</span>
            <strong>18</strong>
        </div>
        <div class="stat">
            <span class="label">Projekte in Arbeit</span>
            <strong>7</strong>
        </div>
        <div class="stat">
            <span class="label">Antwortzeit</span>
            <strong>&lt;24h</strong>
        </div>
        <p class="small">ZilanGroup bündelt Beratung, Identity und Interface in einer ruhigen, fokussierten Oberfläche.</p>
    </div>
</section>

<section class="panel">
    <div class="panel-header">
        <div>
            <p class="eyebrow">Im Rampenlicht</p>
            <h2>Menschen, die ZilanGroup prägen</h2>
            <p class="lede">Admins wählen hier gezielt, wer auf der Homepage erscheint. Rollen, Titel und Bios werden aus dem Dashboard gespeist.</p>
        </div>
        <a class="text-link" href="/admin.php" aria-label="Adminzugang">Verwalten</a>
    </div>
    <div class="cards">
        <?php foreach ($featuredUsers as $user): ?>
            <article class="card">
                <div class="avatar-placeholder"><?php echo strtoupper($user['name'][0]); ?></div>
                <div>
                    <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                    <p class="muted"><?php echo htmlspecialchars($user['title']); ?></p>
                    <p><?php echo htmlspecialchars($user['bio']); ?></p>
                </div>
            </article>
        <?php endforeach; ?>
        <?php if (empty($featuredUsers)): ?>
            <div class="empty">Noch keine Gesichter ausgewählt. Im Admin-Bereich festlegen, wer präsentiert wird.</div>
        <?php endif; ?>
    </div>
</section>

<section class="panel grid">
    <div>
        <p class="eyebrow">Identität</p>
        <h3>Marke &amp; Story aus zilan.dev</h3>
        <p>Wir übernehmen die ruhige, klare Tonalität von zilan.dev und übersetzen sie in ein fokussiertes Erlebnis mit tiefem Violett als Leitfarbe.</p>
    </div>
    <div>
        <p class="eyebrow">Kontrolle</p>
        <h3>Rollen und Sichtbarkeit</h3>
        <p>Admins verwalten Accounts, setzen Rollen und bestimmen, welche Personen prominent auf der Startseite erscheinen.</p>
    </div>
    <div>
        <p class="eyebrow">Onboarding</p>
        <h3>Ohne externes Backend</h3>
        <p>Registrierung, Login und Session-Handling laufen dateibasiert. Ideal für Demos, Showcases oder interne Reviews.</p>
    </div>
</section>
<?php
render_footer();
