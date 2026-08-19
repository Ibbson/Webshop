<?php
require_once 'vendor/autoload.php';
require_once 'models/Database.php';
require_once 'Logger.php';

$logger = new Logger('info.log', 'error.log');

$lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    [$key, $value] = explode('=', $line, 2);
    $_ENV[trim($key)] = trim($value);
}

$pdo = new PDO(
    "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8",
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);

$auth = new \Delight\Auth\Auth($pdo);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $auth->login($_POST['email'], $_POST['password']);
        $logger->info("User logged in: " . $_POST['email']);
        header('Location: index.php');
        exit;
    } catch (\Delight\Auth\InvalidEmailException $e) {
        $error = 'Invalid email address';
        $logger->error("Login failed - invalid email: " . $_POST['email']);
    } catch (\Delight\Auth\InvalidPasswordException $e) {
        $error = 'Invalid password';
        $logger->error("Login failed - invalid password for: " . $_POST['email']);
    } catch (\Delight\Auth\EmailNotVerifiedException $e) {
        $error = 'Email not verified';
    } catch (\Delight\Auth\TooManyRequestsException $e) {
        $error = 'Too many attempts, please try again later';
        $logger->error("Login failed - too many attempts for: " . $_POST['email']);
    }
}

require 'components/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="mb-4 text-center">Login</h2>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-dark w-100">Login</button>
                    </form>
                    <p class="text-center mt-3">No account? <a href="register.php">Create one</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'components/footer.php'; ?>