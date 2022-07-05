<?php
  require_once("templates/header.php");
  require_once("dao/UserDAO.php");
  require_once("models/User.php");

    $user = new User();
    $userDao = new UserDAO($conn, $BASE_URL);

    $userData = $userDao->verifyToken(true);
?>

  <div id="main-container" class="container-fluid">
    <div class="offset-md-4 col-md-4 new-movie-container">
      <h1 class="page-title">Adicionar Filme:</h1>
      <p class="page-description">Adicione sua crítica e compartilhe com o mundo!</p>
      <form action="<?= $BASE_URL ?>movie_process.php" id="add-movie-form" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="type" value="create">
        <div class="form-group">
          <label for="title">Título:</label>
          <input class="form-control" type="text" name="title" id="title" placeholder="Digite o nome do filme">
        </div>
        <div class="form-group">
          <label for="image">Imagem:</label>
          <br>
          <input type="file" class="form-control-file" name="image" id="image">
        </div>
        <div class="form-group">
          <label for="length">Duração:</label>
          <input class="form-control" type="text" name="length" id="length" placeholder="Qual a duração do filme?">
        </div>
        <div class="form-group">
          <label for="category">Categoria:</label>
          <select name="category" id="category" class="form-control">
            <option value="">Selecione</option>  
            <option value="Ação">Ação</option>
            <option value="Aventura">Aventura</option>
            <option value="Drama">Drama</option>
            <option value="Comédia">Comédia</option>
            <option value="Fantasia/Ficção">Fantasia/Ficção</option>
            <option value="Romance">Romance</option>
          </select>
        </div>
        <div class="form-group">
          <label for="trailer">Trailer:</label>
          <input class="form-control" type="text" name="trailer" id="trailer" placeholder="Insira o link do trailer">
        </div>
        <div class="form-group">
          <label for="description">Descrição:</label>
          <textarea class="form-control" name="description" id="description" rows="5" placeholder="Descreva o filme..."></textarea>
        </div>
        <input type="submit" class="botao" value="Adicionar Filme">
      </form>
    </div>
    
  </div>

<?php
  require_once("templates/footer.php");
?>
