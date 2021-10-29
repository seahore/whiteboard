<?php
    require_once("../../defs.php");
    
    header('Content-Type:application/json; charset=utf-8');
    
    dataManager::start("localhost","blog", "blog", "ABCabc1232123");
    $post = dataManager::readPost(intval($_GET["id"]));
    exit(json_encode($post));
?>