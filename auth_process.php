<?php
    require_once("templates/header.php");
    require_once("models/User.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");

    $userDao = new UserDAO($conn , $BASE_URL);
    
    $message = new Message($BASE_URL);

    $type = filter_input(INPUT_POST, "type");

    //Verificação do tipo de formulário
    if($type === "register") {

        $name = filter_input(INPUT_POST, "name");
        $lastname = filter_input(INPUT_POST, "lastname");
        $email = filter_input(INPUT_POST, "email");
        $password = filter_input(INPUT_POST, "password");
        $confirmpassword = filter_input(INPUT_POST, "confirmpassword");
        
        //Verificação de dados mínimos
        if($name && $lastname && $email && $password) {
            
            if($password === $confirmpassword) {

                if($userDao->findByEmail($email) === false) {

                    $user = new User();

                    $userToken = $user->generateToken();
                    $finalPassword = $user->generatePassword($password);

                    $user->name = $name;
                    $user->lastname = $lastname;
                    $user->email = $email;
                    $user->password = $finalPassword;
                    $user->token = $userToken;

                    $auth = true;

                    $userDao->create($user, $auth);
                    

                } else {

                    $message->setMessage("Usuário já cadastrado. Tente outro e-mail", "error", "back");

                }


            } else {

                $message->setMessage("As senhas devem ser iguais.", "error", "back");

            }

 
        } else {

            //Enviar uma mensagem de erro, de dados faltantes
            $message->setMessage("Por favor, preencha todos os campos.", "error", "back");

        }

    } else if($type == "login") {

        $email = filter_input(INPUT_POST, "email");
        $password = filter_input(INPUT_POST, "password");

        if($userDao->authenticateUser($email, $password)) {

            $message->setMessage("Seja bem-vindo", "success", "editprofile.php");

        } else {

            $message->setMessage("Usuário e/ou senha inválidos, tente novamente.", "error", "back");


        }
    }
      

?>  

    

<?php
    require_once("templates/footer.php");
?>