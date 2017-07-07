<?php


namespace App\Controller;


use App\Models\Comment;

class CommentController extends BaseController
{



    public function indexAction()
    {
        if($user = $this->_security->auth()){
            $model = new Comment();

            $comments = $model->getAll();

            $this->_view->render('home', ['comments' => $comments, 'user' => $user]);

        } else {
            $this->_view->render('login');
        }



    }

    public function createAction()
    {
        $access_token = null;
        if(isset($_POST['access_token'])){
            $access_token = $_POST['access_token'];
        }
        if($user = $this->_security->auth($access_token)){
            if (isset($_POST['text']) && !empty($_POST['text'])) {

                $model = new Comment();

                $data['user_id'] = $user->id;
                $data['text'] = trim($_POST['text']);
                $data['created_at'] = time();

                if ($comment_id =$model->add($data)) {


                        $data['user_name'] = $user->name;
                        $data['created_at'] = date('d.m.Y-H:m:s',$data['created_at']);
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

    public function updateAction()
    {
        $access_token = null;
        if(isset($_POST['access_token'])){
            $access_token = $_POST['access_token'];
        }
        if($user = $this->_security->auth($access_token)){
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

    public function deleteAction()
    {
        $access_token = null;
        if(isset($_POST['access_token'])){
            $access_token = $_POST['access_token'];
        }
        if($user = $this->_security->auth($access_token)) {
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


}