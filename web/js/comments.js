function showEditForm(id) {

  $("#comment-input-"+id).slideToggle('slow');
}

function insertComment(comment) {

    var html =  "<li class='comment' id='"+comment.id+"'>"+
                "<div class='panel panel-default'>"+
                    " <div class='panel-heading'>"+
                        "<span class='user'>"+comment.user_name+"</span>"+
                        "<span class='time'>"+comment.created_at+"</span>"+
                    "</div>"+
                    "<div class='panel-body'>"+
                        "<p class='text'  id='text-"+comment.id+"'>"+comment.text+"</p>"+
                    "</div>"+
                    "<div class='panel-footer'>"+
                        "<a class='edit' href='javascript:void(0);' onclick='showEditForm("+comment.id+")' >Редактировать</a>"+
                        "<a class='delete' href='javascript:void(0);' onclick='deleteComment("+comment.id+")'>Удалить</a>"+
                    "</div>"+
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
    $(".comments").append(html);
  
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
      console.log(response);
      if(response.status == 'success'){
        $("#error").hide();
        insertComment(response.data);
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

$(document).ready(function () {
  $("#create-comment").on("click", function (e) {
    e.preventDefault();
    createComment();
  });



});


