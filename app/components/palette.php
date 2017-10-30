<form class="form-inline" method="get" action="">

  <label class="sr-only" for="paletteName">Palette Name</label>
  <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="paletteName"
  name="paletteName" placeholder="The most beautiful...">

  <button type="submit" class="btn btn-secondary">Add Palette</button>

</form>

<?php

  require_once('database.php');

  if (isset($_GET['paletteName'])) {
    $safeName = htmlentities($_GET['paletteName']);
    addPalette($safeName);
  }

  if (isset($_GET['removePaletteId'])) {
    $safeId = htmlentities($_GET['removePaletteId']);
    deletePalette($safeId);
  }

  if (isset($_GET['removeColorFromPaletteId'])) {
    $safeId = htmlentities($_GET['removeColorFromPaletteId']);
    deleteColorFromPalette($safeId);
  }

  if (isset($_GET['paletteIdToAddToRelation']) && isset($_GET['colorIdToAddToRelation'])) {
    $safeColorId = htmlentities($_GET['colorIdToAddToRelation']);
    $safePaletteId = htmlentities($_GET['paletteIdToAddToRelation']);
    addColorToPalette($safeColorId, $safePaletteId);
  }

  function getPalettes() {
    // Return a list of all palettes in the database
    $sql = "SELECT * FROM palettes ORDER BY id desc;";
    $request = pg_query(getDb(), $sql);
    return pg_fetch_all($request);
  }

  function addPalette($name) {
    global $error;
    global $info;
    $sql = "INSERT INTO palettes (palette_name) VALUES ('" . $name . "');";
    $request = pg_query(getDb(), $sql);
    if ($request) {
      $info = "Successfully added a new palette!";
    }
    else {
      $error = "Could not add new palette!";
    }
  }

  function deletePalette($id) {
    global $error;
    global $info;
    $sql1 = "DELETE FROM color_palette WHERE palette_id = " . $id;
    $request1 = pg_query(getDb(), $sql1);
    $sql2 = "DELETE FROM palettes WHERE id = " . $id;
    $request2 = pg_query(getDb(), $sql2);
    if ($request1 && $request2) {
      $info = "Palette successfully delete.";
    }
    else {
      $error = "Unable to properly delete palette!";
    }
  }

  function deleteColorFromPalette($id) {
    global $error;
    global $info;
    $sql = "DELETE FROM color_palette WHERE id=" . $id;
    $request = pg_query(getDb(), $sql);
    if ($request) {
      $info = "Successfully deleted color from palette!";
    }
    else {
      $error = "Could not delete color from palette!";
    }
  }

  function getColorsForPalette($id) {
    $sql = "
      SELECT color_palette.id as delete_id, colors.* FROM color_palette
      JOIN colors ON color_palette.color_id = colors.id
      WHERE palette_id = " . $id . ";
    ";
    $request = pg_query(getDb(), $sql);
    return pg_fetch_all($request);
  }

  function addColorToPalette($color_id, $palette_id) {
    global $error;
    global $info;
    $sql = 'INSERT INTO color_palette (color_id, palette_id) VALUES (' . $color_id . ', ' . $palette_id . ');';
    $request = pg_query(getDb(), $sql);
    if ($request) {
      $info = "Successfully added color to palette!";
    }
    else {
      $error = "Could not add color to palette!";
    }
  }

  function colorOptionForPalette($color) {
    return '<option value="' . $color['id'] . '">' . $color['color_name'] . "</option>\n";
  }

?>
