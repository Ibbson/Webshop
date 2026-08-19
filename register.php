<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'vendor/autoload.php';
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
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $auth->register(
            $_POST['email'],
            $_POST['password'],
            $_POST['username']
        );
        $logger->info("New user registered: " . $_POST['email']);
        $success = 'Account created! You can now login.';
    } catch (\Delight\Auth\InvalidEmailException $e) {
        $error = 'Invalid email address';
        $logger->error("Registration failed - invalid email: " . $_POST['email']);
    } catch (\Delight\Auth\InvalidPasswordException $e) {
        $error = 'Invalid password - must be at least 1 character';
        $logger->error("Registration failed - invalid password");
    } catch (\Delight\Auth\UserAlreadyExistsException $e) {
        $error = 'Email already registered';
        $logger->error("Registration failed - user already exists: " . $_POST['email']);
    }
}

require 'components/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="mb-4 text-center">Create Account</h2>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-dark w-100">Create Account</button>
                    </form>
                    <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'components/footer.php'; ?>