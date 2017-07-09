<?php


namespace App\Models;


class Like extends Model
{
    public $comment_id;
    public $user_id;

    public function __construct($comment_id = null, $user_id = null)
    {
        parent::__construct();

        $this->comment_id = $comment_id;
        $this->user_id = $user_id;

    }

    /**
     * @inheritdoc
     * @return string
     */
    public function label()
    {
        return 'likes';
    }

    /**
     * Add like
     * @return bool
     */
    public function like()
    {
        $query = $this->_db->prepare("INSERT INTO `likes`(`user_id`, `comment_id`) VALUES (:user_id, :comment_id)");
        $query->bindParam(':user_id', $this->user_id);
        $query->bindParam(':comment_id', $this->comment_id);
        try{
            return $query->execute();
        } catch (\Exception $e) {
            return false;
        }


    }

    /**
     * Delete like
     * @return bool
     */
    public function dislike()
    {
        $query = $this->_db->prepare("DELETE FROM `likes` WHERE user_id= :user_id AND comment_id = :comment_id");
        $query->bindParam(':user_id', $this->user_id);
        $query->bindParam(':comment_id', $this->comment_id);
        try{
            return $query->execute();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if user add like to comment
     * @return mixed
     */
    public function isLiked()
    {
        $query = $this->_db->prepare("SELECT * FROM `likes` WHERE user_id = :user_id AND comment_id = :comment_id");
        $query->bindParam(':user_id', $this->user_id, \PDO::PARAM_INT);
        $query->bindParam(':comment_id', $this->comment_id, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(\PDO::FETCH_OBJ);


    }



}