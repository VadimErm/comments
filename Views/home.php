<h4>Здравствуйте, <?= $user->name; ?>.</h4>
<div class="comment-form-block">
    <div class="alert alert-danger" id='error' style="display: none;" role="alert"></div>
    <form method="post" id="comment-form" enctype="multipart/form-data">
        <div class="form-group">
            <label>Комментарий
                <textarea class="form-control" rows="3" name="text"></textarea>
            </label>
        </div>


        <input class="btn btn-default" type="submit" id="create-comment">
        <a class="btn btn-default" href="/index.php?c=site&a=logout">Выйти</a>
    </form>
</div>
<input type="hidden" id="access_token" value="<?= $user->access_token ?>">
<ul class="comments">
    <?php foreach ($comments as $comment): ?>
    <li class="comment" id="<?= $comment->id?>">

        <div class="panel panel-default">
            <div class="panel-heading"><span class="user"><?= $comment->getUser()->name?>
                    <?php $time =  (!is_null($comment->updated_at)) ? $comment->updated_at : $comment->created_at  ?>
                    <span class="time"><?= date('d.m.Y-H:m:s', $time)?></span>
                    <a class="likes" href="javascript:void(0);" onclick="like(<?= $comment->id ?>)">Likes <span class="badge" id="comment-likes-<?= $comment->id?>"> <?= $comment->likes ?></span></a>
            </div>

            <div class="panel-body">
                <p class="text" id="text-<?= $comment->id?>"><?= $comment->text ?></p>
            </div>
            <div class="panel-footer">
                <?php if($user->id == $comment->getUser()->id):?>
                    <a class="edit" href="javascript:void(0);" onclick="showEditForm(<?= $comment->id?>)" >Редактировать</a>
                    <a class="delete" href="javascript:void(0);" onclick="deleteComment(<?= $comment->id?>)">Удалить</a>
                <?php endif;?>
            </div>
        </div>

        <div class="comment-input" id="comment-input-<?= $comment->id?>">
            <div class="alert alert-danger" id="error-<?= $comment->id?>" style="display: none;" role="alert"></div>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for=comment-<?= $comment->id?>">Обновить комментарий</label>
                    <textarea class="form-control" rows="3" name="text" id="comment-<?= $comment->id?>" ><?= $comment->text ?></textarea>
                </div>
                <a class="btn btn-default" href="javascript:void(0);" role="button" onclick="updateComment(<?= $comment->id?>)">Обновить</a>

            </form>
        </div>
    </li>
    <?php endforeach; ?>
</ul>
<?php if($page < $pageCount): ?>
    <button type="button" class="btn btn-primary" id="load-comments" onclick="loadComments(<?= $page ?>, <?= $pageCount ?>, <?= $user->id ?>)">Загрузить еще</button>
<?php endif; ?>



