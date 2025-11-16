<?php
session_start();

function user_storage_path(): string
{
    return __DIR__ . '/../data/users.json';
}

function seed_users(): array
{
    $phpSeed = __DIR__ . '/../data/users.php';
    if (!file_exists($phpSeed)) {
        return [];
    }

    $seed = include $phpSeed;
    $users = array_map(function ($user) {
        return [
            'email' => strtolower($user['email']),
            'password_hash' => password_hash($user['password'], PASSWORD_DEFAULT),
            'name' => $user['name'],
            'role' => $user['role'] ?? 'member',
            'title' => $user['title'] ?? '',
            'bio' => $user['bio'] ?? '',
        ];
    }, $seed);

    save_users($users);
    return $users;
}

function load_users(): array
{
    $path = user_storage_path();
    if (!file_exists($path)) {
        return seed_users();
    }

    $json = file_get_contents($path);
    $users = json_decode($json, true);
    if (!is_array($users)) {
        return seed_users();
    }

    return $users;
}

function save_users(array $users): void
{
    $payload = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents(user_storage_path(), $payload);
}

function find_user_by_email(string $email): ?array
{
    $email = strtolower(trim($email));
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

    $hash = $user['password_hash'] ?? null;
    if (!$hash && isset($user['password'])) {
        $hash = password_hash($user['password'], PASSWORD_DEFAULT);
        $user['password_hash'] = $hash;

        $users = load_users();
        foreach ($users as &$storedUser) {
            if (strcasecmp($storedUser['email'], $user['email']) === 0) {
                $storedUser['password_hash'] = $hash;
                unset($storedUser['password']);
                break;
            }
        }
        save_users($users);
    }

    if ($hash && password_verify($password, $hash)) {
        return safe_user($user);
    }

    return null;
}

function safe_user(array $user): array
{
    unset($user['password_hash'], $user['password']);
    return $user;
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

function register_user(array $data, string $role = 'member'): array
{
    $email = strtolower(trim($data['email'] ?? ''));
    $name = trim($data['name'] ?? '');
    $password = $data['password'] ?? '';
    $title = trim($data['title'] ?? '');
    $bio = trim($data['bio'] ?? '');

    if (!$email || !$name || !$password) {
        return ['success' => false, 'message' => 'E-Mail, Name und Passwort sind erforderlich.'];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Bitte eine gültige E-Mail-Adresse angeben.'];
    }

    if (find_user_by_email($email)) {
        return ['success' => false, 'message' => 'Es existiert bereits ein Account mit dieser E-Mail.'];
    }

    $newUser = [
        'email' => $email,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'name' => $name,
        'role' => $role,
        'title' => $title,
        'bio' => $bio,
    ];

    $users = load_users();
    $users[] = $newUser;
    save_users($users);

    return ['success' => true, 'user' => safe_user($newUser)];
}
?>
