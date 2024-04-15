<?php
$columns_chunked = array_chunk(CHECK_BOXES_LABELS, ceil(count(CHECK_BOXES_LABELS) / 2), true);
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
                print_checkboxes($column_group);
              }
              ?>
            </div>
          </div>
        </div>
      </div>
      <div class="mt-3 form-group">
        <div><label for="id_active">Show Active only?</label></div>
        <input id="id_active" name="active" type="checkbox" />
      </div>
      <div class="d-flex justify-content-between mt-3">
        <button type="button" class="clearBtn btn btn-secondary">Clear</button>
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </form>
  </div>
</div>