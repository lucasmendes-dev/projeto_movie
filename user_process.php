<?php

require_once("templates/header.php");
require_once("models/User.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");

$userDao = new UserDAO($conn , $BASE_URL);

$message = new Message($BASE_URL);

$type = filter_input(INPUT_POST, "type");

//Verificação do tipo de formulário
if($type === "update") {

  //Resgata dados do usuário
  $userData = $userDao->verifyToken();

  //Receber dados do POST
  $name = filter_input(INPUT_POST, "name");
  $lastname = filter_input(INPUT_POST, "lastname");
  $email = filter_input(INPUT_POST, "email");
  $bio = filter_input(INPUT_POST, "bio");


  $userData ->name = $name;
  $userData ->lastname = $lastname;
  $userData ->email = $email;
  $userData ->bio = $bio;

  //upload da imagem 
  if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
    
    $image = $_FILES["image"];
    $imageTypes = ["image/jpg", "image/jpeg", "image/png"];
    $jpgArray = ["image/jpg", "image/jpeg"];
    $png = "image/png";

    //checagem de tipo de imagem
    if(in_array($image["type"], $imageTypes)) {
      
      //checar se é JPG
      if(in_array($image["type"], $jpgArray)) {
        
        $imageFile = imagecreatefromjpeg($image["tmp_name"]);
      
      //checar se é PNG
      } else if($image["type"] == $png) {
        
        $imageFile = imagecreatefrompng($image["tmp_name"]);

      } else {
      
        $message->setMessage("Tipo de imagem inválida. Insira PNG ou JPG", "error", "back");
  
      }

      
      $user = new User();
      $imageName = $user->imageGenerateName();

      imagejpeg($imageFile, "./img/users/" . $imageName, 100);

      $userData->image = $imageName;
      
      $userDao->update($userData);

    } else {
      
      $message->setMessage("Tipo de imagem inválida. Insira PNG ou JPG", "error", "back");

    }


  } else {

    $userDao->update($userData);
    
  }

  
  

} else if($type === "changepassword") {

  $password = filter_input(INPUT_POST, "password");
  $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

  $userData = $userDao->verifyToken();
  $id = $userData->id;

  if($password == $confirmpassword) {
    
    $user = new User();

    $finalpassword = $user->generatePassword($password);

    $user->password = $finalpassword;
    $user->id = $id;

    $userDao->changePassword($user);

  } else {

    $message->setMessage("As senhas devem ser iguais.", "error", "back");

  }

} else {

  $message->setMessage("Informações inválidas.", "error", "index.php");
}