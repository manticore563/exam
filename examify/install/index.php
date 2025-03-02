<?php

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Check if already installed
if (file_exists(BASE_PATH . '/.env')) {
    die('Examify is already installed. Delete .env to reinstall.');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbHost = $_POST['db_host'] ?? '';
    $dbName = $_POST['db_name'] ?? '';
    $dbUser = $_POST['db_user'] ?? '';
    $dbPass = $_POST['db_pass'] ?? '';
    $appUrl = $_POST['app_url'] ?? '';

    // Validate inputs
    if (empty($dbHost) || empty($dbName) || empty($dbUser) || empty($appUrl)) {
        $error = 'All fields are required.';
    } else {
        // Test database connection
        try {
            $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Import SQL schema
            $sql = file_get_contents(BASE_PATH . '/database/examify.sql');
            $pdo->exec($sql);

            // Generate .env file
            $envContent = "APP_NAME=Examify
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=$appUrl

DB_CONNECTION=mysql
DB_HOST=$dbHost
DB_PORT=3306
DB_DATABASE=$dbName
DB_USERNAME=$dbUser
DB_PASSWORD=$dbPass";

            file_put_contents(BASE_PATH . '/.env', $envContent);

            // Generate app key
            require_once BASE_PATH . '/vendor/autoload.php';
            $app = require_once BASE_PATH . '/bootstrap/app.php';
            $app->make('Illuminate\Contracts\Console\Kernel')->call('key:generate');

            header('Location: /');
            exit;
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

// Render installer form
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Examify Installer</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-4">Examify Installer</h1>
        <?php if (isset($error)): ?>
            <p class="text-red-500 mb-4"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-4">
                <label class="block text-gray-700">App URL</label>
                <input type="text" name="app_url" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Database Host</label>
                <input type="text" name="db_host" value="localhost" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Database Name</label>
                <input type="text" name="db_name" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Database Username</label>
                <input type="text" name="db_user" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Database Password</label>
                <input type="password" name="db_pass" class="w-full p-2 border rounded">
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full">Install</button>
        </form>
    </div>
</body>
</html>