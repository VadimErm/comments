<div class="row">
    <div class="center-block">

        <form action="/index.php?c=site&a=login" method="post" enctype="multipart/form-data" class="form-horizontal" >
            <div class="form-group">
                <label for="login" class="col-sm-2 control-label">Логин</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control"  name="login" id="login" placeholder="Login">
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-sm-2 control-label">Пароль</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember_me">Запомнить меня?
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" class="btn btn-default">
                </div>
            </div>
        </form>

    </div>
</div>