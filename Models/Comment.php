<?php


namespace App\Models;



class Comment extends Model
{



    public $text;
    public $user_id;
    public $created_at;
    public $updated_at;
    public $user;



    public function getAll()
    {
        $query = $this->_db->query('SELECT * FROM comments ORDER BY created_at ASC');
        $raws = $query->fetchAll(\PDO::FETCH_OBJ);

        $models = $this->load($raws);

        return $models;

    }

    public function add(array $data)
    {
        $query = $this->_db->prepare('INSERT INTO comments (user_id, text, created_at) VALUES(:user_id, :text, :created_at)');
        if($query->execute($data)){
            return $this->_db->lastInsertId();
        } else {
            return false;
        }


    }

    public function update()
    {
        $query = $this->_db->prepare('UPDATE comments SET text = :text, updated_at = :updated_at WHERE id = :id LIMIT 1');
        $query->bindValue(':id', $this->id, \PDO::PARAM_INT);
        $query->bindValue(':text', $this->text);
        $query->bindValue(':updated_at', $this->updated_at);
        return $query->execute();
    }



    public function label()
    {
        return 'comments';
    }

    public function getUser()
    {
        $model = new User();

        return $model->findById($this->user_id);
    }


}