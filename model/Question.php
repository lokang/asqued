<?php


class Question extends Database {
    public function createQuestion($userId, $question){
        $prepare = $this->prepare("INSERT INTO question (userId, question) VALUES (?, ?)");
        $prepare->execute([$userId, $question]);
    }

    public function getAll(){
        $prepare = $this->prepare("SELECT question.*, user.firstName, user.lastName FROM question JOIN user ON user.id = question.userId");
        $prepare->execute();
        return $prepare->fetchAll();
    }

    public function get($id){
        $prepare = $this->prepare("SELECT questionComment.*, question.id, user.id FROM questionComment JOIN question ON questionComment.questionId = question.id JOIN user ON questionComment.userId = user.id WHERE question.id = ?");
        $prepare->execute([$id]);
        return $prepare->fetch();
    }

    public function createComment($questionId, $userId, $description){
        $prepare = $this->prepare("INSERT INTO questionComment (questionId, userId, description) VALUES (?, ?, ?)");
        $prepare->execute([$questionId, $userId, $description]);
    }
}