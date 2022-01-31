<?php
class QuestionComment extends Database {
    public function getAllCommentsByQuestion($id){
        $prepare = $this->prepare("SELECT questionComment.*, user.firstName, user.lastName FROM questionComment 
            LEFT JOIN user ON questionComment.userId = user.id WHERE questionComment.questionId = ?");
        $prepare->execute([$id]);
        return $prepare->fetchAll();
    }

    public function get($id){
        $prepare = $this->prepare("SELECT questionComment.*, user.firstName, user.lastName FROM questionComment 
            LEFT JOIN user ON questionComment.userId = user.id WHERE questionComment.id = ?");
        $prepare->execute([$id]);
        return $prepare->fetch();
    }

    public function destroy($id){
        $prepare = $this->prepare("DELETE FROM questionComment WHERE id = ?");
        $prepare->execute([$id]);
    }

    public function updateQuestionComment($description, $id){
        $prepare = $this->prepare("UPDATE questionComment SET description = ? WHERE id = ?");
        $prepare->execute([$description, $id]);
    }
}