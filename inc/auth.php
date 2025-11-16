<?php
session_start();

function load_users(): array
{
    $users = include __DIR__ . '/../data/users.php';
    return array_map(function ($user) {
        $user['password_hash'] = password_hash($user['password'], PASSWORD_DEFAULT);
        return $user;
    }, $users);
}

function find_user_by_email(string $email): ?array
{
    foreach (load_users() as $user) {
        if (strcasecmp($user['email'], $email) === 0) {
            return $user;
        }
    }
    return null;
}

function authenticate(string $email, string $password): ?array
{
    $user = find_user_by_email($email);
    if (!$user) {
        return null;
    }

    // We hash on the fly to avoid storing sensitive data in files
    if (password_verify($password, $user['password_hash'])) {
        unset($user['password_hash']);
        unset($user['password']);
        return $user;
    }

    return null;
}

function require_login(): void
{
    if (!isset($_SESSION['user'])) {
        header('Location: /login.php');
        exit;
    }
}

function require_admin(): void
{
    require_login();
    if (($_SESSION['user']['role'] ?? null) !== 'admin') {
        header('HTTP/1.1 403 Forbidden');
        echo '<h1>Access denied</h1><p>You need admin permissions to view this page.</p>';
        exit;
    }
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function load_featured(): array
{
    $path = __DIR__ . '/../data/featured.json';
    if (!file_exists($path)) {
        return [];
    }

    $data = json_decode(file_get_contents($path), true);
    return $data['featured'] ?? [];
}

function save_featured(array $emails): void
{
    $path = __DIR__ . '/../data/featured.json';
    $payload = json_encode(['featured' => $emails], JSON_PRETTY_PRINT);
    file_put_contents($path, $payload);
}
