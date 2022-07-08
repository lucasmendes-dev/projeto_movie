<?php

  require_once("globals.php");
  require_once("db.php");
  require_once("models/Movie.php");
  require_once("models/Message.php");
  require_once("dao/UserDAO.php");
  require_once("dao/MovieDAO.php");

  $message = new Message($BASE_URL);
  $userDao = new UserDAO($conn, $BASE_URL);
  $movieDao = new MovieDAO($conn , $BASE_URL);
  

  //resgata dados do usuário
  $userData = $userDao->verifyToken();
  
  $type = filter_input(INPUT_POST, "type");
  
  if($type == "create") {

    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    $length = filter_input(INPUT_POST, "length");
    

    $movie = new Movie();

    //validação mínima de dados
    if(!empty($title) && !empty($description) && !empty($category)) {

      $movie->title = $title;
      $movie->description = $description;
      $movie->trailer = $trailer;
      $movie->category = $category;
      $movie->length = $length;
      $movie->users_id = $userData->id;

      //upload de imagem do filme
      if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

        $image = $_FILES["image"]; 
        $imageTypes = ["image/jpg", "image/jpeg", "image/png"];
        $jpgArray = ["image/jpg", "image/jpeg"];

        if(in_array($image["type"], $imageTypes)) {

          //checa se é JPEG
          if(in_array($image["type"], $jpgArray)) {

            $imageFile = imagecreatefromjpeg($image["tmp_name"]);

          } else{

            //checa se é PNG
            $imageFile = imagecreatefrompng($image["tmp_name"]);

          }

          $imageName = $movie->generateImageName();

          imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

          $movie->image = $imageName;

        } else {

          $message->setMessage("Tipo de imagem inválida. Insira PNG ou JPG", "error", "back");

        }

      }
      
      $movieDao->create($movie);

    } else {

      $message->setMessage("Você precisa adicionar pelo menos um Título, categoria e descrição!", "error", "back");

    }

  } else if($type == "delete") {

    //recebe os dados do form
    $id = filter_input(INPUT_POST, "id");

    $movie = $movieDao->findById($id);

    if($movie) {

      //verificar se o filme é do usuário
      if($movie->users_id === $userData->id) {

        $movieDao->destroy($movie->id);

      } else {

        $message->setMessage("Informações inválidas.", "error", "index.php");

      }


    } else {

      $message->setMessage("Informações inválidas.", "error", "index.php");

    }

  } else {

    $message->setMessage("Informações inválidas.", "error", "index.php");

  }
