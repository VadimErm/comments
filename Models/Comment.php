<?php


namespace App\Models;



class Comment extends Model
{



    public $text;
    public $user_id;
    public $created_at;
    public $updated_at;
    public $user;
    public $likes;


    /**
     * Get all comments
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getAll($offset = 0, $limit = 5)
    {

        $query = $this->_db->query('SELECT * FROM comments  ORDER BY created_at DESC LIMIT '.$limit.' OFFSET '.$offset);
        $raws = $query->fetchAll(\PDO::FETCH_OBJ);

        $models = $this->load($raws);

        foreach ($models as $model){
            $model->user = $model->getUser()->name;
            $model->likes = $model->getLikes();
        }

        return $models;

    }

    /**
     * Add comment
     * @param array $data
     * @return bool|string
     */
    public function add(array $data)
    {
        try{
            $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();

            $query = $this->_db->prepare('INSERT INTO comments (user_id, text, created_at) VALUES(:user_id, :text, :created_at)');

            $query->execute($data);
            $comment_id = $this->_db->lastInsertId();
            $this->_db->commit();

            return  $comment_id;

        } catch (\Exception $e) {

            $this->_db->rollBack();

            return false;
        }

    }

    /**
     * Update comment
     * @return bool
     */
    public function update()
    {
        $query = $this->_db->prepare('UPDATE comments SET text = :text, updated_at = :updated_at WHERE id = :id LIMIT 1');
        $query->bindValue(':id', $this->id, \PDO::PARAM_INT);
        $query->bindValue(':text', $this->text);
        $query->bindValue(':updated_at', $this->updated_at);
        return $query->execute();
    }


    /**
     * @inheritdoc
     * @return string
     */
    public function label()
    {
        return 'comments';
    }

    /**
     * Get user, who add comment
     * @return array|null
     */
    public function getUser()
    {
        $model = new User();

        return $model->findById($this->user_id);
    }

    /**
     * Get count of comment's likes
     * @return int
     */
    public function getLikes()
    {
        $model = new Like();

        $likes = $model->findBy(['comment_id' => $this->id]);

        return count($likes);

    }


}