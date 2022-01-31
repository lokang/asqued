<?php
/**
 * Created by PhpStorm.
 * User: lokang
 * Date: 4/1/20
 * Time: 1:03 AM
 */
class User extends Database{
    public function get($value, $column = 'id'){
        $prepare = $this->prepare("SELECT * FROM user WHERE $column = ?");
        $prepare->execute([$value]);
        return $prepare->fetch();
    }

    public function getAll(){
        $prepare = $this->prepare("SELECT * FROM user");
        $prepare->execute();
        return $prepare->fetchAll();
    }

    public function destroy($id){
        $prepare = $this->prepare("DELETE FROM user WHERE id = ?");
        $prepare->execute([$id]);
    }

    public function update($id, $firstName, $middleName, $lastName, $email, $password=false){
        $prepare = $this->prepare("UPDATE user SET firstName = ?, middleName = ?, lastName = ?, email = ? WHERE id = ?");
        $prepare->execute([$firstName, $middleName, $lastName, $email, $id]);
        if(!empty($password)){
            $prepare = $this->prepare("Update user SET password = ? WHERE id = ?");
            $prepare->execute([$password, $id]);
        }
    }

    public function register($firstName, $middleName, $lastName, $email, $password){
        $prepare = $this->prepare("INSERT INTO user (firstName, middleName, lastName, email, password) VALUES (?, ?, ?, ?, ?)");
        $prepare->execute([$firstName, $middleName, $lastName, $email, $password]);
        return $this->conn->lastInsertId();
    }
}