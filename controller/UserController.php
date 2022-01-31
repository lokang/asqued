<?php
/**
 * Created by PhpStorm.
 * User: lokang
 * Date: 24/5/20
 * Time: 7:43 PM
 */
class UserController extends Controller{
    public function __construct(){
        parent::__construct();
        if(!$this->auth){
            $this->redirect('home/login');
        }
    }

    public function profile(){
        $user = new User();
//        $profile = $user->
        if($_POST){
            $newPassword = false;
            if(!empty($_POST['newPassword'])){
                $newPassword = hash('sha512', $_POST['newPassword']);
            }
            $user->update($this->auth['id'], $_POST['firstName'], $_POST['middleName'], $_POST['lastName'], $_POST['email'], $newPassword);
            $this->redirect('user/profile/');
        }
        $this->view('profile', 'profile', [

        ]);
    }

    public function destroy(){
        $user = new User();
        $user->destroy($this->auth['id']);
        setcookie('id', false, -1, '/');
        setcookie('key', false, -1, '/');
        $this->redirect('home/login/');
    }

    public function logout(){
        setcookie('id', false, -1, '/');
        setcookie('key', false, -1, '/');
        $this->redirect('home/login/');
    }

    public function destroyUser($userId){
        if(!$this->auth['id'] == 1){
            $this->redirect('/');
        }
        $user = new User();
        $user->destroy($userId);
        $this->redirect('user/users/');
    }
    public function users(){
        $user = new User();
        $users = $user->getAll();
        if(!$this->auth['id'] == 1){
            $this->redirect('/');
        }
        $this->view('users', 'List of users', [
            'users' => $users
        ]);
    }

    public function account($id){
        $this->view('userAccount', $this->auth['firstName']);
    }
}