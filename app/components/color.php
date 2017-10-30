<form class="form-inline" method="get" action="">

  <label class="sr-only" for="colorName">Color Name</label>
  <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="colorName" name="colorName" placeHolder="The deep dark void...">

  <label class="sr-only" for="hexCode">Hex Code</label>
  <div class="input-group mb-2 mr-sm-2 mb-sm-0">
    <div class="input-group-addon">#</div>
    <input type="text" class="form-control" id="hexCode" name="hexCode" placeholder="a1b2c3" maxlength="6" size="6">
  </div>

  <button type="submit" class="btn btn-secondary">Add Color</button>

</form>

<?php
  
  require_once('database.php');

  if (isset($_GET['colorName']) && isset($_GET['hexCode'])) {
    $safeName = htmlentities($_GET['colorName']);
    $safeHex = htmlentities($_GET['hexCode']);
    addColor($safeName, $safeHex);
  }

  if (isset($_GET['removeColorId'])) {
    $safeId = htmlentities($_GET['removeColorId']);
    deleteColor($safeId);
  }

  function getColors() {
    $sql = "SELECT * FROM colors ORDER BY id desc;";
    $request = pg_query(getDb(), $sql);
    return pg_fetch_all($request);
  }

  function addColor($name, $hex) {
    global $error;
    global $info;
    $sql = "INSERT INTO colors (color_name, hex_code) VALUES ('" . $name . "', '" . $hex . "');";
    $request = pg_query(getDb(), $sql);
    if ($request) {
      $info = "Color added successfully!";
    }
    else {
      $error = "Could not add color!";
    }
  }

  function deleteColor($id) {
    global $error;
    global $info;
    $sql = "DELETE FROM colors WHERE id = " . $id;
    $request = pg_query(getDb(), $sql);
    if ($request) {
      $info = "Color deleted successfully!";
    }
    else {
      $error = "Could not delete color! (Is it used in a palette somewhere?)";
    }
  }

  function displayColor($idToDelete, $name, $hex, $inputNameToDelete) {
    return '
      <div class="row mb-2">

        <div class="col-2 text-right">
          <form method="get" action="">
            <input name="' . $inputNameToDelete . '" value="' . $idToDelete . '" type="hidden">
            <button class="btn btn-sm btn-outline-secondary" type="submit"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
          </form>
        </div>

        <div class="col-6 btn btn-sm" style="min-height: 25px; max-width: 100px; background-color: #' . $hex . ';">&nbsp;
        </div>

        <div class="col-4">' . $name . '</div>

      </div>
    ';
  }

?>