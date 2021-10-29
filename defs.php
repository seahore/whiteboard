<?php
abstract class showable{
    var $id;
    var $content;

    function __construct($id, $content) {
        $this->id = $id;
        $this->content = $content;
    }

    abstract function show();
}

class post extends showable{
    var $title;
    var $catagory;
    var $authorID;

    function __construct($id, $title, $catagory, $authorID, $content) {
        parent::__construct($id, $content);
        $this->title = $title;
        $this->catagory = $catagory;
        $this->authorID = $authorID;
    }

    function show(){
        echo "<article><h2>".$this->title."</h2><p><b>作者: ".dataManager::getUsernameByID($this->authorID)." | 分类：".$this->catagory."</b></p><p>".$this->content."</p></article>";
    }
}

class notice extends showable{
    function __construct($id, $content) {
        parent::__construct($id, $content);
    }

    function show(){
        echo "<article><p>".$this->content."</p></article>";
    }
}

abstract class showableDecorator {
    var $showable;

    function __construct($showable) {
        $this->showable = $showable;
    }
    abstract function show();
}

class card extends showableDecorator{

    function __construct($showable) {
        parent::__construct($showable);
    }

    function show(){
        echo "<div class=\"card\">";
        $this->showable->show();
        echo "</div>";
    }
}

class user {
    var $name;
    var $postCount;

    function __construct($name, $postCount) {
        $this->name = $name;
        $this->postCount = $postCount;
    }
}

class dataManager {
    static $conn;

    static function start($servername, $dbname, $username, $password) {
        self::$conn = new PDO("mysql:host=".$servername.";dbname=".$dbname.";", $username, $password);
        self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    static function getUsernameByID($id) {
        $stmt = self::$conn->prepare("SELECT name FROM user WHERE id = " . $id); 
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)["name"];
    }

    static function getUser($id) {
        $name = self::getUsernameByID($id);
        $stmt = self::$conn->prepare("SELECT COUNT(*) AS count FROM post WHERE author_id = " . $id); 
        $stmt->execute();
        $postCount = $stmt->fetch(PDO::FETCH_ASSOC)["count"];
        return new user($name, $postCount);
    }

    private static function selectByID($id){
        $stmt = self::$conn->prepare("SELECT * FROM post WHERE id = " . $id); 
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    static function readPost($id) {
        $result = self::selectByID($id);
        return new post($id, $result["title"], $result["catagory"], $result["author_id"], $result["content"]);
    }

    static function readNotice($id) {
        $result = self::selectByID($id);
        return new notice($id, $result["content"]);
    }

    static function createPost($post) {
        $stmt = self::$conn->prepare("INSERT INTO post (id, title, author_id, catagory, content)
                                      VALUES (". $post->id. ",". $post->title .",". $post->author_id. ",". $post->catagory.",". $post->content .")"); 
        $stmt->execute();
    }

    static function createNotice($notice) {
        $stmt = self::$conn->prepare("INSERT INTO post (id, title, author_id, catagory, content)
                                      VALUES (". $notice->id. ", \"\", 0, null,".$notice->content.")"); 
        $stmt->execute();
    }

}



class poster {
    function __construct() {
        dataManager::start("localhost","blog", "blog", "ABCabc1232123");
    }

    function showPost($id, $asCard = false) {
        if($asCard) {
            (new card(dataManager::readPost($id)))->show();
        } else {
            dataManager::readPost($id)->show();
        }
    }

    function showNotice($id, $asCard = false) {
        if($asCard) {
            (new card(dataManager::readNotice($id)))->show();
        } else {
            dataManager::readNotice($id)->show();
        }
    }
}

?>