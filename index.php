<?php

  require_once('db/connection.php');

  define('INTERVALO',250);

  // Retrieve the selected artists from the URL parameters
  $selectedArtists = isset($_GET['artists']) ? json_decode($_GET['artists']) : [];

  // Retrieve the selected albums from the URL parameters
  $selectedAlbums = isset($_GET['albums']) ? json_decode($_GET['albums']) : [];


  $qMusics = "SELECT artist_name,album_name,GROUP_CONCAT(DISTINCT genre_name SEPARATOR ', '),
  music_name,GROUP_CONCAT(DISTINCT style_name SEPARATOR ', '),music_bpm,music_note 
  FROM musics 
  INNER JOIN artists USING(artist_id)
  INNER JOIN albuns USING(album_id)
  INNER JOIN n_genres ON(albuns.album_id = n_genres.album_id) 
  INNER JOIN genre ON(genre.genre_id = n_genres.genre_id)
  INNER JOIN n_styles ON(albuns.album_id = n_styles.album_id)
  INNER JOIN styles ON(styles.style_id = n_styles.style_id)";
  
  if (!empty($selectedArtists) || !empty($selectedAlbums)) {
    $qMusics .= " WHERE ";
    
    if (!empty($selectedArtists)) {
      $qMusics .= "artist_name IN ('" . implode("','", $selectedArtists) . "')";

      if (!empty($selectedAlbums)) {
        $qMusics .= " AND ";
      }
    }

    if (!empty($selectedAlbums)) {
      $qMusics .= "album_name IN ('" . implode("','", $selectedAlbums) . "')";
    }
  }

  $qMusics .= " GROUP BY music_id
  ORDER BY music_bpm ASC LIMIT ?";

  $stmtMusics = $conn->prepare($qMusics);

  if (!$stmtMusics) {
    die("Invalid query: " . $qMusics . "Error: " . $conn->error);
  }

  if (isset($_GET['numMusics']) && $_GET['numMusics'] != "") {
    $numMusics = $_GET['numMusics'];
  } else {
    $numMusics = INTERVALO;
  }

  $stmtMusics->bind_param('i',$numMusics);
  $stmtMusics->execute();
  $stmtMusics->store_result();
  $totalMusics = $stmtMusics->num_rows;

?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles/main.css" /> 
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
  <title>Musics</title>
</head>
<body>
  <h1>Music table</h1>
  <?php include 'filter.php';?>
  <div id="filterOptionsContainer"></div>
    <table id="musicTable" class="music_table">
      <thead>
        <tr>
          <th>Artist</th>
          <th>Album</th>
          <th>Genre</th>
          <th>Music Name</th>
          <th>Style</th>
          <th>Bpm</th>
          <th>Note</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $stmtMusics->bind_result($artistName,$albumName,$genres,$musicName,$styles,$musicBpm,$musicNote);
          while ($stmtMusics->fetch()) {
        ?>
        <tr>
          <td><?= $artistName ?></td>
          <td><?= $albumName ?></td>
          <td><?= $genres ?></td>
          <td><?= $musicName ?></td>
          <td><?= $styles ?></td>
          <td><?= $musicBpm ?></td>
          <td><?= $musicNote ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  <div class="load-btn-container">
    <?php
      if ($numMusics <= $totalMusics) {
    ?>

    <a href="index.php?numMusics=<?= $numMusics+INTERVALO ?>" class="btn-load-music">Load +</i></a>

    <?php } else { echo "<h3>End</h3>"; } ?>

  </div>
        
  <?php
    $stmtMusics->close();
    $conn->close();
    ?>
</body>
</html>

