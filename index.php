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
        <h1>Digitale Lösungen mit Charakter</h1>
        <p class="lede">Strategische Plattformen, sichere Logins und präzise Nutzersteuerung in einem eleganten Interface.</p>
        <div class="cta-row">
            <a class="button primary" href="/register.php">Jetzt registrieren</a>
            <a class="button ghost" href="/login.php">Einloggen</a>
            <a class="button ghost" href="/admin.php">Admin-Dashboard</a>
        </div>
        <div class="meta">Brand Insights inspiriert von <a href="https://zilan.dev" target="_blank" rel="noreferrer">zilan.dev</a></div>
    </div>
    <div class="hero-card">
        <div class="stat">
            <span class="label">Aktive Sessions</span>
            <strong>24</strong>
        </div>
        <div class="stat">
            <span class="label">SLA</span>
            <strong>99.9%</strong>
        </div>
        <div class="stat">
            <span class="label">Integrationen</span>
            <strong>12</strong>
        </div>
        <p class="small">Vertrauenswürdige Identitäten &mdash; gestützt von smarter Governance.</p>
    </div>
</section>

<section class="panel">
    <div class="panel-header">
        <div>
            <p class="eyebrow">Featured Users</p>
            <h2>Kompetenz auf der Startseite</h2>
            <p class="lede">Präsentiere wichtige Teammitglieder oder Ansprechpartner direkt im Home-Hero. Admins verwalten die Auswahl mit wenigen Klicks.</p>
        </div>
        <a class="text-link" href="/admin.php">Im Admin-Dashboard pflegen →</a>
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
            <div class="empty">Noch keine hervorgehobenen Nutzer. Im Admin-Bereich auswählen.</div>
        <?php endif; ?>
    </div>
</section>

<section class="panel grid">
    <div>
        <p class="eyebrow">Security</p>
        <h3>Login ohne kompliziertes Backend</h3>
        <p>Sessions, Rollen und Featured-Listen laufen dateibasiert. Ideal für Demos, interne Piloten oder als stilvoller Prototyp.</p>
    </div>
    <div>
        <p class="eyebrow">Admin-Tools</p>
        <h3>Nutzerverwaltung mit Klarheit</h3>
        <p>Admins sehen alle Accounts, setzen Feature-Toggles und halten die Startseite aktuell.</p>
    </div>
    <div>
        <p class="eyebrow">Branding</p>
        <h3>ZilanGroup Identität</h3>
        <p>Logo und Copy sind auf die Marke abgestimmt. Weitere Informationen stammen von <a href="https://zilan.dev" target="_blank" rel="noreferrer">zilan.dev</a>.</p>
    </div>
</section>
<?php
render_footer();
