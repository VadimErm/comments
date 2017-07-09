function showEditForm(id) {

  $("#comment-input-"+id).slideToggle('slow');
}

function insertComment(comment, user_id, position) {
    var time;
    var likes;
    if(comment.updated_at == null){
      time = moment.unix(comment.created_at).format('DD.MM.YYYY-HH:mm:ss');
    } else  {
      time = moment.unix(comment.updated_at).format('DD.MM.YYYY-HH:mm:ss');
    }

    if(comment.likes == undefined){

      likes = 0;
    } else  {
      likes = comment.likes;
    }
    var html =  "<li class='comment' id='"+comment.id+"'>"+
                "<div class='panel panel-default'>"+
                    " <div class='panel-heading'>"+
                        "<span class='user'>"+comment.user+"</span>"+
                        "<span class='time'>"+time+"</span>"+
                    "<a class='likes' href='javascript:void(0);' onclick='like("+comment.id+")'>Likes <span class='badge' id='comment-likes-"+comment.id+"'> "+likes+"</span></a>"+
                    "</div>"+
                    "<div class='panel-body'>"+
                        "<p class='text'  id='text-"+comment.id+"'>"+comment.text+"</p>"+
                    "</div>"+
                    "<div class='panel-footer'>";
    if(user_id == comment.user_id || user_id == null){
        html += "<a class='edit' href='javascript:void(0);' onclick='showEditForm("+comment.id+")' >Редактировать</a>"+
              "<a class='delete' href='javascript:void(0);' onclick='deleteComment("+comment.id+")'>Удалить</a>";
    }
    html +=     "</div>"+
                "</div>"+
                "<div class='comment-input' id='comment-input-"+comment.id+"'>"+
                  "<form  method='post' enctype='multipart/form-data'>"+
                  "<div class='form-group'>"+
                      "<label for='comment-"+comment.id+"'>Обновить комментарий</label>"+
                      "<textarea class='form-control' rows='3' name='text' id='comment-"+comment.id+"' required >"+comment.text+"</textarea>"+
                  "</div>"+

                    "<a class='btn btn-default' href='javascript:void(0);' role='button' onclick='updateComment("+comment.id+")'>Обновить</a>"+
                  "</form>"+
                "</div>"+
              "</li>";
    if(position == 'prepend'){
      $(".comments").prepend(html);
    } else if(position == 'append'){
      $(".comments").append(html);
    }

  
}

function createComment() {

  var comment = $("#comment-form textarea").val();
  var access_token = $("#access_token").val();
  var formData = new FormData();
  formData.append('text', comment);
  formData.append('access_token', access_token);
  $.ajax({
    url: '/index.php?c=comment&a=create',
    type: 'POST',
    data: formData,
    dataType: 'json',
    cache: false,
    enctype: 'multipart/form-data',
    processData: false,
    contentType: false,
    success: function (response) {

      if(response.status == 'success'){
        $("#error").hide();
        insertComment(response.data, null, 'prepend');
        $('#comment-form textarea').val('');
      } else if(response.status =='fail'){
        $("#error").html(response.error);
        $("#error").show();
      } else if(response.status =='login'){
        window.location='/index.php?c=site&a=login';
      }

    },

    error: function (response) {

      console.log('Error!')

    }
  });
  
}

function deleteComment(id) {

  var access_token = $("#access_token").val();
  var formData = new FormData();
  formData.append('id', id);
  formData.append('access_token', access_token);
  $.ajax({
    url: '/index.php?c=comment&a=delete',
    type: 'POST',
    data: formData,
    dataType: 'json',
    cache: false,
    enctype: 'multipart/form-data',
    processData: false,
    contentType: false,
    success: function (response) {

      if(response.status == 'success'){
        $("#"+id).remove();
      } else if(response.status == 'fail'){
        $("#error-"+id).html(response.error);
        $("#error-"+id).show();
      } else if(response.status =='login'){
        window.location='/index.php?c=site&a=login';
      }

    },

    error: function (response) {

      console.log('Error!')

    }
  });
}

function updateComment(id) {

  var text = $("#comment-"+id).val();
  var access_token = $("#access_token").val();
  var formData = new FormData();
  formData.append('id', id);
  formData.append('text', text);
  formData.append('access_token', access_token);
  $.ajax({
    url: '/index.php?c=comment&a=update',
    type: 'POST',
    data: formData,
    dataType: 'json',
    cache: false,
    enctype: 'multipart/form-data',
    processData: false,
    contentType: false,
    success: function (response) {

      if(response.status == 'success'){
        $("#error-"+id).hide();
          $("#text-"+id).html(text);
      } else if(response.status == 'fail'){
        $("#error-"+id).html(response.error);
        $("#error-"+id).show();
      }else if(response.status =='login'){
        window.location='/index.php?c=site&a=login';
      }
    },

    error: function (response) {

      console.log('Error!')

    }
  });
}

function loadComments(page, pageCount, user_id) {
  var access_token = $("#access_token").val();
  var formData = new FormData();
  formData.append('access_token', access_token);
  formData.append('page', page);
  $.ajax({
    url: '/index.php?c=comment&a=index',
    type: 'POST',
    data: formData,
    dataType: 'json',
    cache: false,
    enctype: 'multipart/form-data',
    processData: false,
    contentType: false,
    success: function (response) {

      if(response.status == 'success'){
        if(response.page <= pageCount){
            response.comments.forEach(function (comment, i, arr) {
              insertComment(comment, user_id, 'append');
            });

            $("#load-comments")[0].onclick = null;

            $("#load-comments")[0].addEventListener('click', function () {
              loadComments(page+1, pageCount, user_id);
            }, false);
            if(response.page == pageCount) {
              $("#load-comments").remove();
            }

        }


      }

    },

    error: function (response) {

      console.log('Error!')

    }
  });
}

function like(comment_id) {
  var access_token = $("#access_token").val();
  var formData = new FormData();
  formData.append('access_token', access_token);
  formData.append('comment_id', comment_id);

  $.ajax({
    url: '/index.php?c=comment&a=like',
    type: 'POST',
    data: formData,
    dataType: 'json',
    cache: false,
    enctype: 'multipart/form-data',
    processData: false,
    contentType: false,
    success: function (response) {
      if(response.status == 'success'){
        var likes = Number($("#comment-likes-"+comment_id).html());
        if(response.dislike){
          $("#comment-likes-"+comment_id).html(likes-1);
        } else {
          $("#comment-likes-"+comment_id).html(likes+1);
        }
      } else if(response.status == 'login'){
        window.location='/index.php?c=site&a=login';
      }

    },

    error: function (response) {

      console.log('Error!')

    }
  });

}

$(document).ready(function () {
  $("#create-comment").on("click", function (e) {
    e.preventDefault();
    createComment();
  });

});


