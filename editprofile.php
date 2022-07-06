<?php
    require_once("templates/header.php");
    require_once("dao/UserDAO.php");

    $user = new User();
    $userDao = new UserDAO($conn, $BASE_URL);

    $userData = $userDao->verifyToken(true);

    $fullName = $user->getFullName($userData);

    if($userData->image == "") {
        $userData->image = "user.png";
    }
    
?>

    <div id="main-container" class="container-fluid edit-profile-page">
        <div class="col-md-12">
            <form action="<?= $BASE_URL ?>user_process.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="type" value="update" >
                <div class="row">
                    <div class="col-md-4">
                        <h1><?= $fullName ?></h1>
                        <p class="page-description">Altere seus dados no formulário abaixo:</p>
                        <div class="form-group">
                            <label for="name">Nome:</label>
                            <input class="form-control" type="text" name="name" id="name" placeholder="Digite seu nome" value="<?= $userData->name ?>">
                        </div>
                        <div class="form-group">
                            <label for="lastname">Sobrenome:</label>
                            <input class="form-control" type="text" name="lastname" id="lastname" placeholder="Digite seu nome" value="<?= $userData->lastname ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail:</label>
                            <input class="form-control disabled" readonly type="text" name="email" id="email" placeholder="Digite seu novo e-mail" value="<?= $userData->email ?>">
                            <p class="email">*E-mail não pode ser alterado.</p>
                        </div>
                        <input type="submit" class="botao" value="Alterar">
                    </div>
                    <div class="col-md-4">
                        <div id="profile-image-container" style="background-image: url('<?= $BASE_URL ?>img/users/<?= $userData->image ?>')"></div>
                        <div class="form-group">
                            <label for="image">Foto: </label>
                            <br>
                            <input type="file" class="form-control-file" name="image">
                        </div>
                        <div class="form-group">
                            <label for="bio">Sobre você:</label>
                            <textarea class="form-control" name="bio" id="bio" rows="5" placeholder="Conte quem você é, o que faz..."><?= $userData->bio ?></textarea>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row" id="change-password-container">
                <div class="col-md-4">
                    <h2>Alterar a senha:</h2>
                    <p class="page-description">Digite a nova senha e confirme, para alterar sua senha:</p>
                    <form action="<?= $BASE_URL ?>user_process.php" method="POST">
                        <input type="hidden" name="type" value="changepassword">
                        <div class="form-group">
                            <label for="password">Nova Senha:</label>
                            <input class="form-control" type="password" name="password" id="password" placeholder="Digite sua nova senha">
                        </div>
                        <div class="form-group">
                            <label for="confirmpassword">Confirmação de senha:</label>
                            <input class="form-control" type="password" name="confirmpassword" id="confirmpassword" placeholder="Confirme sua nova senha">
                            <input type="submit" class="botao" value="Alterar Senha">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
    require_once("templates/footer.php");
?>
