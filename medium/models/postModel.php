<?php
class Post {
    public $title;
    public $smallDescription;
    public $body;
    public $headerImagePath;
    public $userId;

    // Constructor to initialize properties
    public function __construct(string $title="", string $smallDescription="", string $body="", string $headerImagePath="", int $userId=-1) {
        $this->title = $title;
        $this->smallDescription = $smallDescription;
        $this->body = $body;
        $this->headerImagePath = $headerImagePath;
        $this->userId = $userId;
    }

    public function validate(){
        if(strlen($this->title) < 3 || strlen($this->title) > 100){
            return ["success" => false , "message" => "title length must between 3 and 100 characters"];
        }
        if(strlen(strlen($this->smallDescription) > 500)){
            return ["success" => false , "message" => "small description length must be less than  500 characters"];
        }
        return ["success" => true , "data" => "valid post model"];
    }
}
?>
