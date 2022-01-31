<?php
/**
 * Created by PhpStorm.
 * User: lokang
 * Date: 4/1/20
 * Time: 1:47 AM
 */
class Controller{
    public $auth = false;
    public function __construct(){
        if(!empty($_COOKIE['id']) && !empty($_COOKIE['key'])){
            $user = new User();
            $userData = $user->get($_COOKIE['id']);
            if($userData['id'] == $_COOKIE['id']){
                if($userData['password'] == $_COOKIE['key']){
                    $this->auth=$userData;
                }
            }
        }
    }

    public function view($fileName, $title=false, $vars=false){
        if(!empty($vars)){
            foreach($vars as $key=>$value){//@todo &
                $$key = $value;
            }
        }


        include("view/header.php");
        include ("view/".$fileName.".php");
        include("view/footer.php");
    }

    public function redirect($location){
        if($location[0] == '/'){
            $location = substr_replace($location, '', 0, 1);
        }
        header("Location:/".$location);
        exit;
    }

    public function form($name, $rules){
        $_SESSION['post'] = $_POST;
        $ruleArray = explode('|', $rules);
        foreach($ruleArray as $rule){
            if($rule == 'required'){
                if(empty($_POST[$name])){
                    $_SESSION['errors'] = 'The field '.$name.' is required';
                    $this->back();
                }
            }elseif(preg_match('/^min:([0-9]+)$/', $rule, $match)){
                if(strlen($_POST[$name]) < $match[1]){
                    $_SESSION['errors'] = 'The minimum length of '.$name .' is '.$match[1];
                    $this->back();
                }
            }elseif(preg_match('/^max:([0-9]+)$/', $rule, $match)){
                if(strlen($_POST[$name]) > $match[1]){
                    $_SESSION['errors'] = 'The maximum length of '.$name .' is '.$match[1];
                    $this->back();
                }
            }
        }

    }

    public function authOnly(){
        if (empty($this->auth)) {
            $this->redirect('home/login');
        }
    }
    public function countTotalArticles($courseId, $userId, $totalRead=false){
        $myCourseModel = new MyCourse();
        $myCourse = $myCourseModel->get($courseId, $userId);
        if($totalRead == true && $myCourse['progress'] == 0){
            return 0;
        }

        $lessonModel = new Lesson();
        $lessons = $lessonModel->getAllActive($courseId);

        $articleModel = new Article();
        $total = 0;
        foreach($lessons as $lesson){
            $articles = $articleModel->getAllActive($lesson['id']);
            foreach($articles as $article){
                $total++;
                if($article['id'] == $myCourse['progress'] && $totalRead == true){
                    return $total;
                }
            }
            //$total += count($articles);
        }
        return $total;
    }

    public function errors(){
        if(!empty($_SESSION['errors'])){
            $errors = '<div class ="alert alert-danger">'. $_SESSION['errors'] .'</div>';
            unset($_SESSION['errors']);
            return $errors;
        }
    }

    protected function setError($message){
        $_SESSION['errors'] = $message;
    }

    private function value($name){
        if(!empty($_SESSION['post'][$name])){
            $post = $_SESSION['post'][$name];
            unset($_SESSION['post'][$name]);
            return $post;
        }
    }

    protected function back(){
        header('Location: '.$_SERVER['HTTP_REFERER']);
        exit();
    }

    protected function sendEmail($to, $subject, $message, $from='info@lokang.com'){
        $headers = 'From: '.$from . "\r\n" .
            'Reply-To: '. $from . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        mail($to, $subject, $message, $headers);
    }

    protected function randomString($length=8){
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $result = false;
        for($i=0; $i<$length; $i++){
            $result .= $characters[rand(0,60)];
        }
        return $result;
    }

    public function replaceTags($text){
        $text =  preg_replace_callback('/\[auth\]\s?(.*)\s?\[\/auth\]/', function($m){
            if($this->auth){
                return $m[1];
            }else{
                return 'You need to login to project the script';
            }
        }, $text);

        $text =  preg_replace_callback('/\[counter\]\s?(.*)\s?\[\/counter\]/', function($m){
            $download = new Download();
            $count = $download->countDownload($m[1]);
            return '('.$count.')';
        }, $text);

        return $text;
    }
}