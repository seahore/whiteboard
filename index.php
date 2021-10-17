<?php require_once("defs.php"); ?>
<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/5.0.2/css/bootstrap.css" rel="stylesheet">
        <link href="./main.css" rel="stylesheet">
        <script src="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/5.0.2/js/bootstrap.js"></script>
        <title>A PHP Blog</title>
    </head>
    <body>
        <?php
            try {
                $poster = new poster();
                echo "<p>连接成功</p>"; 
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        ?>
        <div class="container">
            <div class="row">
                <div class="col-9"><?php 
                    $poster->showPost(1);
                    echo "<hr>";
                    $poster->showNotice(2);
                    echo "<hr>";
                    $poster->showPost(3);
                ?></div>
                <div class="col-3"><?php 
                    $poster->showPost(1, true);
                    $poster->showNotice(2, true);
                ?></div>
            </div>
        </div>
    </body>
</html>