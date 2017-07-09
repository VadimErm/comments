<?php


namespace App\Controller;


use App\Models\Comment;
use App\Models\Like;

class CommentController extends BaseController
{

    /**
     * Get comments
     */
    public function indexAction()
    {

        if($user = $this->_security->auth()){
            $page = 0;
            if(isset($_POST['page'])){
                $page = (int) $_POST['page'];
            }

            $model = new Comment();
            $rawCount = $model->getRawCount();
            $limit = COMMENTS_LIMIT;
            $pageCount = (int)ceil($rawCount  / $limit);
            if($page == 0){
                $comments = $model->getAll(0, $limit);
                $this->_view->render('home', [
                    'comments' => $comments,
                    'user' => $user[0],
                    'page' => $page+1,
                    'pageCount'=> $pageCount
                ]);
            } else{
                $offset = $limit*$page;
                $comments = $model->getAll($offset, $limit);
                echo json_encode([
                    'status' => 'success',
                    'comments' => $comments,
                    'user' => $user,
                    'page' => $page+1,
                    'pageCount'=> $pageCount]);
            }

        } else {
            $this->_view->render('login');
        }



    }

    /**
     * Add comment
     */
    public function createAction()
    {

        if($user = $this->_security->auth()){
            if (isset($_POST['text']) && !empty($_POST['text'])) {

                $model = new Comment();

                $data['user_id'] = $user[0]->id;
                $data['text'] = trim($_POST['text']);
                $data['created_at'] = time();

                if ($comment_id =$model->add($data)) {

                        $data['user'] = $user[0]->name;
                        $data['id'] = $comment_id;
                        echo json_encode([
                            'status' => 'success',
                            'data' => $data
                        ]);

                } else {
                    echo json_encode([
                        'status' =>'fail',
                        'error' => "Something wrong! Comment not added." ,

                    ]);
                }
            } else {
                echo json_encode([
                    'status' =>'fail',
                    'error' => "Comment is empty" ,

                ]);

            }

        } else {
            echo json_encode([
                'status' =>'login',

            ]);

        }

    }

    /**
     * Update comment
     */
    public function updateAction()
    {

        if($user = $this->_security->auth()){
            if (!empty($_POST['text']) && !empty($_POST['id'])) {
                $commentId = (int)$_POST['id'];

                $model = new Comment();
                $comment = $model->findById($commentId);
                $comment->text = strip_tags(trim($_POST['text']));
                $comment->updated_at = time();
                if ($comment->update()) {
                    echo json_encode(['status'=>'success']);
                } else {

                    echo json_encode(['status'=>'fail', 'error'=>'Something wrong! Not updated']);
                }

            } else {
                echo json_encode(['status'=>'fail', 'error'=>'Text or comment id is empty']);

            }

        } else {
            echo json_encode([
                'status' =>'login',

            ]);
        }

    }

    /**
     * Delete comment
     */
    public function deleteAction()
    {

        if($user = $this->_security->auth()) {
            if (isset($_POST['id']) && isset($_POST['access_token'])) {
                $model = new Comment();
                $comment = $model->findById($_POST['id']);
                if (!$comment) {
                    $this->_view->render('error', ['error' => "Something wrong! Comment not exist."]);
                } elseif ($comment->delete()) {

                    echo json_encode(['status' => 'success']);


                } else {
                    echo json_encode(['status' => 'fail', 'error' => 'Something wrong! Comment not deleted.']);

                }
            }
        } else {
            echo json_encode([
                'status' =>'login',

            ]);
        }
    }

    /**
     * Add like to comment. Like can add only not an author of comment.
     */
    public function likeAction()
    {
        if($user = $this->_security->auth()){
            if(isset($_POST['comment_id'])){
                $model = new Comment();
                $comment = $model->findById($_POST['comment_id']);
                if($user[0]->id !==$comment->user_id){
                    $like = new Like($_POST['comment_id'], $user[0]->id);
                    if($like->isLiked()){
                        if($like->dislike()){
                            echo json_encode(['status'=>'success', 'dislike' => true]);
                        } else {
                            echo json_encode(['status'=>'fail']);
                        }

                    } else {
                        if($like->like()){
                            echo json_encode(['status'=>'success', 'dislike' => false]);
                        }else {
                            echo json_encode(['status'=>'fail']);
                        }

                    }
                } else {
                    echo json_encode(['status'=>'fail']);
                }


            } else {
                echo json_encode(['status'=>'fail']);
            }

        } else {
            echo json_encode([
                'status' =>'login',

            ]);
        }

    }


}