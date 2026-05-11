<?php

require_once __DIR__ . "/../models/Database.php";
require_once __DIR__ . "/../models/Category.php";

$db = new Database();
$categories = $db->getAllCategories();

?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <title>Tech It Easy</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

   
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

    <div class="container-fluid">

        
        <a class="navbar-brand" href="index.php">
            Tech It Easy
        </a>

        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

     
        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto">

               
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>

               
                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Categories
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <?php foreach ($categories as $category) { ?>

                            <li>
                                <a class="dropdown-item" href="category.php?id=<?php echo $category->id; ?>">
                                    <?php echo $category->name; ?>
                                </a>
                            </li>

                        <?php } ?>

                    </ul>

                </li>

            </ul>

        </div>

    </div>

</nav>