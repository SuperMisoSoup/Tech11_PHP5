<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="home.php">Avails登録</a>
            <?php if($_SESSION["UserRoleId"]=="1"){ ?>
                <a class="navbar-brand" href="user_add.php">ユーザー登録</a>
            <?php } ?>
            <a class="navbar-brand" href="logout.php">ログアウト</a>
        </div>
    </div>
</nav>