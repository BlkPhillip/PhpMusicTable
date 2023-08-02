<?php
  require_once('db/connection.php');

  $selectedArtists = isset($_GET['artists']) ? json_decode($_GET['artists']) : [];
  $selectedAlbums = isset($_GET['albums']) ? json_decode($_GET['albums']) : [];

  $qfilterArtist = "SELECT DISTINCT artists.artist_id, artists.artist_name 
                    FROM artists
                    INNER JOIN albuns ON artists.artist_id = albuns.artist_id";

  if (!empty($selectedAlbums)) {
    $qfilterArtist .= " WHERE albuns.album_name IN (";
    $qfilterArtist .= "'" . implode("','", $selectedAlbums) . "')";
  }

  $stmtFilterArtist = $conn->prepare($qfilterArtist);

  if (!$stmtFilterArtist) {
    die("Invalid query: " . $qMusics . "Error: " . $conn->error);
  }

  $qfilterAlbum = "SELECT album_name FROM albuns";

  if (!empty($selectedArtists)) {
    $qfilterAlbum .= " WHERE artist_id IN (SELECT artist_id FROM artists WHERE artist_name IN (";
    $qfilterAlbum .= "'" . implode("','", $selectedArtists) . "'))";
  }

  $stmtFilterAlbum = $conn->prepare($qfilterAlbum);

  if (!$stmtFilterAlbum) {
    die("Invalid query: " . $qMusics . "Error: " . $conn->error);
  }

  $qfilterGenre = "SELECT genre_name From genre";

  $stmtFilterGenre = $conn->prepare($qfilterGenre);

  if (!$stmtFilterGenre) {
    die("Invalid query: " . $qMusics . "Error: " . $conn->error);
  }

  $stmtFilterArtist->execute();
  $stmtFilterArtist->store_result();

  $stmtFilterAlbum->execute();
  $stmtFilterAlbum->store_result();
  
  $stmtFilterGenre->execute();
  $stmtFilterGenre->store_result();

?>

<div class="filter-container">
  <div class="filter-line">
    <h3>Filter</h3>
    <button onclick="myDrop(event)" id="dropsign" class="dropbtn">+</button>
  </div>
  <div id="myDropdown" class="dropdown-content">
    <div class="wrapper">
      <div class="select-btn">
        <span>Select Artist</span>
        <i class="uil uil-angle-down"></i>
      </div>
      <div class="content">
        <div class="search">
          <i class="uil uil-search"></i>
          <input type="text" placeholder="Search" id="searchArtistFilter">
        </div>
        <div class="options">
          <?php
            $stmtFilterArtist->bind_result($artistId,$artistName);
            while ($stmtFilterArtist->fetch()) {
          ?>
            <label>
              <input type="checkbox" name="artistCheckbox[]" value="<?= $artistName ?>" class="artist-check">
              <span><?= $artistName ?></span>
            </label>
          <?php } ?>
        </div>
      </div>
    </div>
    <div class="wrapper2">
      <div class="select-btn2">
        <span>Select Album</span>
        <i class="uil uil-angle-down"></i>
      </div>
      <div class="content2">
        <div class="search2">
          <i class="uil uil-search"></i>
          <input type="text" placeholder="Search" id="searchAlbumFilter">
        </div>
        <div class="options2" id="options2">
          <?php
            $stmtFilterAlbum->bind_result($albumName);
            while ($stmtFilterAlbum->fetch()) {
          ?>
          <label>
            <input type="checkbox" name="albumCheckbox[]" value="<?= $albumName ?>" class="album-check">
            <span><?= $albumName ?></span>
          </label>
          
          <?php 
              }
            $stmtFilterArtist->close();
            $stmtFilterAlbum->close();
          ?>
        </div>
      </div>
    </div>  
    <div class="wrapper3">
      <div class="select-btn3">
        <span>Select Genre</span>
        <i class="uil uil-angle-down"></i>
      </div>
      <div class="content3">
        <div class="search3">
          <i class="uil uil-search"></i>
          <input type="text" placeholder="Search" id="searchGenreFilter">
        </div>
        <div class="options3" id="options3">
          <?php
            $stmtFilterGenre->bind_result($genreName);
            while ($stmtFilterGenre->fetch()) {
          ?>
          <label>
            <input type="checkbox" name="genreCheckbox[]" value="<?= $genreName ?>" class="genre-check">
            <span><?= $genreName ?></span>
          </label>
          <?php 
              }
            $stmtFilterGenre->close();
          ?>
        </div>
      </div>
    </div>

  </div>
</div>
<script src="scripts/dropdown.js"></script>
<script src="scripts/wrapper.js"></script>
<script src="scripts/wrapper2.js"></script>
<script src="scripts/wrapper3.js"></script>
<script src="scripts/searchArtistFilter.js"></script>
<script src="scripts/searchAlbumFilter.js"></script>
<script src="scripts/artistsCheckBox.js"></script>