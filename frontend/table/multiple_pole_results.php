<?php
define("CHOICES", !empty($_GET['choices']) ? $_GET['choices'] : '');
function get_active_columns() {
  $active_columns = [];
  foreach (CHOICES as $checked_column) {
    $active_columns[$checked_column] = $checked_column;
  }
  return $active_columns;
}

$columns_chunked = array_chunk(MULTIPLE_POLE_RESULT, ceil(count(MULTIPLE_POLE_RESULT) / 2), true);
function printCheckboxes($group) {
  $active_columns = CHOICES ? get_active_columns() : '';
  foreach ($group as $key => $column) {
    if (CHOICES) {
      $checked = !empty($active_columns[$key]) ? 'checked' : '';
    } else {
      $checked = $column['default'] ? 'checked' : '';

    }
    if ($key != "all") {
      echo '<div  class="col-md-6"><label for="id_choices_' . $key . '"><input id="id_choices_' . $key . '" name="choices[]" type="checkbox" value="' . $key . '" ' . $checked . '> ' . $column['label'] . '</label></div>';
    } else {
      echo '<div class="col-md-6"><label for="id_choices_' . $key . '"><input id="id_choices_' . $key . '"  type="checkbox" value="all"> ' . $column['label'] . '</label></div>';
    }
  }
}

?>
  <div class="d-flex filter-form-wrapper justify-content-center mw-100 text-secondary">
    <div class="col-md-6 col-md-offset-3 well">
      <form class="needs-validation" id="multiple_pole_search" action="<?php echo get_permalink(get_the_ID()); ?>"
            method="get" novalidate>
        <div class="form-group">
          <div><label for="id_choices">Fields</label></div>
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <?php
                foreach ($columns_chunked as $column_group) {
                  printCheckboxes($column_group);
                }
                ?>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-3 form-group">
          <div><label for="id_active">Show Active only?</label></div>
          <input id="id_active" name="active" type="checkbox">
        </div>
        <div class="d-flex justify-content-between mt-3">
          <button type="button" class="clearBtn btn btn-secondary">Clear</button>
          <button type="submit" class="btn btn-primary">Search</button>
        </div>
      </form>
    </div>
  </div>
  <div class="mw-100 mt-5">
    <div class="d-flex justify-content-between">
      <p class="text-scondary">Found 19,787 results. (19,787 records with duplicates)</p>
      <div class="btn-group  btn-group-sm mb-4" role="group" aria-label="Basic outlined example">
        <button type="button" class="btn btn-outline-primary text-uppercase">Expotr as exel</button>
        <button type="button" class="btn btn-outline-primary text-uppercase">Expotr as text</button>
        <button type="button" class="btn btn-outline-primary text-uppercase">print</button>
      </div>
    </div>
  </div>

<?php
if (!empty(CHOICES)) {
  ?>
  <div class="overflow-auto mw-100">.
    <table class="table">
      <thead>
      <tr>
        <?php
        foreach (CHOICES as $column) {
          $value = MULTIPLE_POLE_RESULT[$column];
          if (!empty(EXTRA_COLUMNS[$column])) {
            foreach (EXTRA_COLUMNS[$column] as $extra) {
              echo '<th >' . $extra . '</th>';
            }
          } else {
            echo '<th >' . $value['label'] . '</th>';
          }
        }
        ?>
      </tr>
      </thead>
      <tbody>
      <?php
      foreach (COLUMNS_VALUES as $get_columns_value_data){
      ?>
      <tr>
        <?php
        foreach (CHOICES as $get_columns_keys) {
          if(is_string($get_columns_value_data[$get_columns_keys])){
            echo "<td> $get_columns_value_data[$get_columns_keys] </td>";
          }
          else{
            foreach ($get_columns_value_data[$get_columns_keys] as $get_extra_value){
              echo "<td> $get_extra_value </td>";
            }
          }
        }
        } ?>
      </tr>
      </tbody>
    </table>

  </div>
  <?php
}