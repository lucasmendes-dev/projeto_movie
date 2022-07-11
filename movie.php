<?php
  require_once("templates/header.php");
  require_once("dao/MovieDAO.php");
  require_once("dao/ReviewDAO.php");
  require_once("models/Movie.php");

  $id = filter_input(INPUT_GET, "id");

  $movie;

  $movieDao = new MovieDAO($conn, $BASE_URL);
  $reviewDao = new ReviewDAO($conn, $BASE_URL);

  $user = new User();

  if(empty($id)) {

    $message->setMessage("O filme não foi encontrado.", "error", "index.php");

  } else {

    $movie = $movieDao->findById($id);

    //verifica se o filme existe
    if(!$movie) {

      $message->setMessage("O filme não foi encontrado.", "error", "index.php");

    } 

  }

  //checar se o filme tem imagem
  if($movie->image == "") {
    $movie->image = "movie_cover.jpg";
  }




  //checar se o filme é do usuário

  $userOwnsMovie = false;

  if(!empty($userData)) {

    if($userData->id == $movie->users_id) {
      $userOwnsMovie = true;
    }

  }

  //resgatar as reviews do filme
  $moviesReviews = $reviewDao->getMoviesReview($id);
  


  //resgatar as reviews do filme
  $alreadyReviewd = false;

?>

<div id="main-container" class="container-fluid">
  <div class="row">
    <div class="offset-md-1 col-md-6 movie-container">
      <h1 class="page-title"><?= $movie->title ?></h1>
      <p class="movie-details">
        <span>Duração: <?= $movie->length ?></span>
        <span class="pipe"></span>
        <span><?= $movie->category ?></span>
        <span class="pipe"></span>
        <span><i class="fas fa-star"></i> 9</span>
      </p>
      <iframe src="<?= $movie->trailer ?>" width="560" height="315" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
      <p><?= $movie->description ?></p>
    </div>
    <div class="col-md-4">
      <div class="movie-image-container" style="background-image: url('<?= $BASE_URL ?>img/movies/<?= $movie->image ?>')"></div>
    </div>
    <div class="offset-md-1 col-md-10" id="reviews-container">
      <h3 id="reviews-title">Avaliações</h3>
      <!--Verifica se habilita o review para o usuário ou não-->
      <?php if(!empty($userData) && !$userOwnsMovie && !$alreadyReviewd): ?>
      <div class="col-md-12" id="review-form-container">
        <h4>Envie sua avaliação:</h4>
        <p class="page-description">Preencha o formulário com a nota e comentário sobre o filme</p>
        <form action="<?= $BASE_URL ?>review_process.php" method="POST" id="review-form">
          <input type="hidden" name="type" value="create">
          <input type="hidden" name="movies_id" value="<?= $movie->id ?>">
          <div class="form-group">
            <label for="rating">Nota do filme</label>
            <select name="rating" id="rating" class="form-control">
              <option value="">Selecione</option>
              <option value="10">10</option>
              <option value="9">9</option>
              <option value="8">8</option>
              <option value="7">7</option>
              <option value="6">6</option>
              <option value="5">5</option>
              <option value="4">4</option>
              <option value="3">3</option>
              <option value="2">2</option>
              <option value="1">1</option>
            </select>
          </div>
          <div class="form-group">
            <label for="review">Seu comentário:</label>
            <textarea name="review" id="review" rows="3" class="form-control" placeholder="O que você achou do filme?"></textarea>
            <input type="submit" class="botao" value="Enviar Comentário">
          </div>
        </form>
      </div>
      <?php endif; ?>
      <!--Comentários-->
      <?php foreach($moviesReviews as $review): ?>
        <?php require("templates/user_review.php"); ?>
      <?php endforeach; ?>
      <?php if(count($moviesReviews) == 0): ?>
        <p class="empty-list">Ainda não há cometários para este filme.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php
  require_once("templates/footer.php");
?>
