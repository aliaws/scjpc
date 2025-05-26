<div class="card-wrapper d-flex justify-content-center">
  <div class="card advance-custom-card p-2 p-md-2">
    <form class="needs-validation" id="advanced_pole_search" method="get" novalidate>
      <div class="row">
        <div class="mb-3 col-12 col-md-12">
          <label class="form-label">Pole Number</label>
          <div class="d-flex border p-1 p-md-2 rounded input-bg">
            <p class="col-md-2 col-sm-3 col m-0">
              <select name="pole_number_filter" class="form-select">
                <?php foreach (STRING_FILTER as $key => $label) { ?>
                  <option <?php echo isset($_REQUEST['pole_number_filter']) && $_REQUEST['pole_number_filter'] == $key ? 'selected' : '' ?>
                    value="<?php echo $key; ?>">
                    <?php echo $label; ?>
                  </option>
                <?php } ?>
              </select>
            </p>
            <p class="col-md-10 col-sm-9 col ps-sm-3 ps-2 m-0">
              <input type="text" name="pole_number" class="form-control" id="pole" autofocus
                     value="<?php echo $_REQUEST['pole_number'] ?? ''; ?>"/>
            </p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="mb-3 col-12 col-md-12">
          <label class="form-label">Location</label>
          <div class="d-flex border p-1 p-md-2 rounded input-bg">
            <p class="col-md-2 col-sm-3 col m-0">
              <select name="location_filter" class="form-select " aria-label=".form-select-lg example">
                <?php foreach (STRING_FILTER as $key => $label) { ?>
                  <option <?php echo isset($_REQUEST['location_filter']) && $_REQUEST['location_filter'] == $key ? 'selected' : '' ?>
                    value="<?php echo $key; ?>">
                    <?php echo $label; ?>
                  </option>
                <?php } ?>
              </select>
            </p>
            <p class="col-md-10 col-sm-9 col ps-sm-3 ps-2 m-0">
              <?php $location_str = $_REQUEST['location_encoded'] ?? ''; ?>
              <?php $location_str = base64_decode( $location_str ) ?? $location_str; ?>
              <?php $_REQUEST['location'] = $location_str; ?>
              <input type="text" name="location" class="form-control" id="location" value="<?php echo $location_str; ?>"/>
              <span id="location_feedback_length" class="invalid-feedback">
                  Characters length should be more than 1
              </span>
            </p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="mb-3 col-12 col-sm-6 col-lg-4">
          <label for="id_latitude" class="form-label">Latitude</label>
          <input type="text" name="latitude" class="form-control" id="id_latitude" aria-describedby="emailHelp"
                 value="<?php echo $_REQUEST['latitude'] ?? ''; ?>"/>
          <div id="invalid-feedback" class="invalid-feedback ">
            Latitude must have at least 3 decimal places (Example: 79.123)
          </div>
        </div>
        <div class="mb-3 col-12 col-sm-6 col-lg-4">
          <label for="id_longitude" class="form-label">Longitude</label>
          <input type="text" name="longitude" class="form-control" id="id_longitude" aria-describedby="emailHelp"
                 value="<?php echo $_REQUEST['longitude'] ?? ''; ?>"/>
          <div id="invalid-feedback" class="invalid-feedback ">
            Longitude must have at least 3 decimal places (Example: 79.123)
          </div>
        </div>
        <div class="mb-3 col-12 col-sm-6 col-lg-4">
          <label for="id_distance" class="form-label">Distance</label>
          <select class="form-select" id="id_distance" name="distance" aria-label="Default select example">
            <?php foreach (DISTANCES as $key => $value) { ?>
              <?php $selected_distance = !empty($_REQUEST["distance"]) && $_REQUEST["distance"] == $key ? 'selected="selected"' : ''; ?>
              <option value="<?php echo $key; ?>" <?php echo $selected_distance ?>><?php echo $value; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="d-flex justify-content-between">
        <button type="button" class="clearBtn btn btn-secondary">Clear</button>
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
      <input type="hidden" id="action" name="action" value="advanced_pole_search"/>
      <input type="hidden" id="per_page" name="per_page" value="<?php echo $_REQUEST['per_page'] ?? '50'; ?>"/>
      <input type="hidden" id="page_number" name="page_number" value="<?php echo $_REQUEST['page_number'] ?? '1'; ?>"/>
      <input type="hidden" id="last_id" name="last_id" value="<?php echo $_REQUEST['last_id'] ?? ''; ?>"/>
      <input type="hidden" id="sort_key" name="sort_key" value="<?php echo $_POST['sort_key'] ?? 'unique_id'; ?>"/>
      <input type="hidden" id="sort_order" name="sort_order" value="<?php echo $_POST['sort_order'] ?? 'asc'; ?>"/>
      <input type="hidden" id="admin_ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>"/>
      <input type="hidden" id="page_slug" name="page_slug" value="<?php echo get_post_field('post_name', get_the_ID()); ?>"/>

      <?php $query_id = empty ( $_REQUEST['query_id'] ) ? time() : $_REQUEST['query_id']; ?>
      <input type="hidden" id="query_id" name="query_id" value="<?php echo $query_id; ?>"/>
      <?php if ( ! empty ( $_REQUEST['go_back'] ) ) { ?>
        <input type="hidden" id="go_back" name="go_back" value="<?php echo $_REQUEST['go_back']; ?>"/>
      <?php } ?>

    </form>
    <div class="accordion mt-5" id="accordionPanelsStayOpenExample">
      <div class="accordion-item">
        <p class="accordion-header" id="panelsStayOpen-headingThree">
          <button class="accordion-button collapsed rounded-0" type="button" data-bs-toggle="collapse"
                  data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false"
                  aria-controls="panelsStayOpen-collapseThree">
            Search Instructions
          </button>
        </p>
        <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse"
             aria-labelledby="panelsStayOpen-headingThree">
          <div class="accordion-body input-bg">
            <small>
              Use this search when pole numbers or locations are partially known. Search will return a list of all
              records found in the database, dead or active. Omit space, slash, hyphen, and other special
              characters.<br>
              To search:<br>
              1. Select a category from the drop-down list.<br>
              2. Type partial pole number and/or location in the respective search boxes. If location is unknown (left
              blank), you must enter at least 3&nbsp;characters in the pole number search box.<br>
              3. Click Search.<br>
              4. Click on column heading to sort the list.<br>
              5. Click the unique ID to view the record.<br>
              Tips<br>
              Users can use the percent symbol (%) as a wildcard to replace missing numbers (e.g., 456%17E) or locations
              (e.g., LA BREA%FAIRVIEW).<br>
              The search will return all records (dead or active records). If the complete <strong>pole number is known
                and only the current active record is needed</strong>, use the Quick Pole Search page for faster search
              performance.</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/spinner.php"; ?>
<div class="response-table">
  <?php $make_search = ! empty ( $_REQUEST[ 'pole_number' ] ); ?>
  <?php $make_search = $make_search || ! empty ( $_REQUEST[ 'location' ] ); ?>
  <?php $make_search = $make_search || ( ! empty ( $_REQUEST[ 'latitude' ] ) && ! empty ( $_REQUEST[ 'longitude' ] ) ); ?>
  <?php if ( ! empty ( $_REQUEST ) && $make_search ) {
    $_REQUEST[ 'action' ] = 'advanced_pole_search';
    include_once SCJPC_PLUGIN_FRONTEND_BASE . "results/pole_results.php";
  } ?>
</div>
<div class="database-update-information alert alert-primary mt-4">
  <?php echo scjpc_database_update_information(); ?>
</div>


https://scjpc.net/advanced-pole-search/?pole_number_filter=contains&pole_number=&location_filter=contains&location=568%27+N%2FO+CAV&latitude=&longitude=&distance=75&action=advanced_pole_search&per_page=50&page_number=1&last_id=&sort_key=unique_id&sort_order=asc&page_slug=advanced-pole-search&query_id=1748259675&location_encoded=NTY4JyBOL08gQ0FW