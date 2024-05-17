<div class="card p-4">
  <form class="needs-validation" id="advanced_pole_search" action="<?php echo get_permalink(get_the_ID()); ?>"
        method="get" novalidate>
    <div class="mb-3">
      <label class="form-label">Pole Number</label>
      <div class="d-flex border p-2 rounded input-bg">
        <p class="col-3 m-0">
          <select name="pole_number_filter" class="form-select">
            <?php foreach (STRING_FILTER as $key => $label) { ?>
              <option <?php echo isset($_GET['pole_number_filter']) && $_GET['pole_number_filter'] == $key ? 'selected' : '' ?>
                value="<?php echo $key; ?>">
                <?php echo $label; ?>
              </option>
            <?php } ?>
          </select>
        </p>
        <p class="col-9 ps-3 m-0">
          <input type="text" name="pole_number" class="form-control" id="pole"
                 value="<?php echo $_GET['pole_number'] ?? ''; ?>"/>
        </p>
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label">Location</label>
      <div class="d-flex border p-2 rounded input-bg">
        <p class="col-3 m-0">
          <select name="location_filter" class="form-select " aria-label=".form-select-lg example">
            <?php foreach (STRING_FILTER as $key => $label) { ?>
              <option <?php echo isset($_GET['location_filter']) && $_GET['location_filter'] == $key ? 'selected' : '' ?>
                value="<?php echo $key; ?>">
                <?php echo $label; ?>
              </option>
            <?php } ?>
          </select>
        </p>
        <p class="col-9 ps-3 m-0">
          <input type="text" name="location" class="form-control" id="location"
                 value="<?php echo $_GET['location'] ?? ''; ?>"/>
        </p>
      </div>
    </div>
    <div class="mb-3">
      <label for="id_latitude" class="form-label">Latitude</label>
      <input type="text" name="latitude" class="form-control" id="id_latitude" aria-describedby="emailHelp"
             value="<?php echo $_GET['latitude'] ?? ''; ?>"/>
    </div>
    <div class="mb-3">
      <label for="id_longitude" class="form-label">Longitude</label>
      <input type="text" name="longitude" class="form-control" id="id_longitude" aria-describedby="emailHelp"
             value="<?php echo $_GET['longitude'] ?? ''; ?>"/>
    </div>
    <div class="mb-4">
      <label for="id_distance" class="form-label">Distance</label>
      <select class="form-select" id="id_distance" name="distance" aria-label="Default select example">
        <?php foreach (FEET as $key => $value) { ?>
          <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
        <?php } ?>
      </select>
    </div>

    <div class="d-flex justify-content-between">
      <button type="button" class="clearBtn btn btn-secondary">Clear</button>
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
    <input type="hidden" id="action" name="action" value="advanced_pole_search"/>
    <input type="hidden" id="per_page" name="per_page" value="<?php echo $_GET['per_page'] ?? '50'; ?>"/>
    <input type="hidden" id="page_number" name="page_number" value="<?php echo $_GET['page_number'] ?? '1'; ?>"/>
    <input type="hidden" id="last_id" name="last_id" value="<?php echo $_GET['last_id'] ?? ''; ?>"/>
  </form>

  <div class="accordion mt-5" id="accordionPanelsStayOpenExample">
    <div class="accordion-item">
      <p class="accordion-header" id="panelsStayOpen-headingThree">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false"
                aria-controls="panelsStayOpen-collapseThree">
          Search Instructions
        </button>
      </p>
      <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse"
           aria-labelledby="panelsStayOpen-headingThree">
        <div class="accordion-body input-bg">
          <small>Use this search when pole numbers or locations are partially known. Search will return a list of all
            records found in the database, dead or active. O<span style="line-height: 20.8px;">mit space, slash, hyphen, and other special characters.&nbsp;</span><br
              style="line-height: 20.8px;">
            To search:&nbsp;<br>
            1. Select a category from&nbsp;the drop-down list.&nbsp;<br>
            2. Type partial pole number and/or location in the respective search boxes. If location is unknown (left
            blank), you must enter at least 3 characters in the&nbsp;pole number search box.<br>
            3. Click Search.&nbsp;<br>
            4. Click on column heading to sort the list.&nbsp;<br>
            5. Click the unique ID to view the record.<br>
            Tips&nbsp;<br>
            Users can use the percent symbol (%) as a wildcard to replace missing numbers (e.g. 456%17E) or locations
            (e.g. LA BREA%FAIRVIEW).&nbsp;<br>
            The search will return all records (dead or active records). If the complete <strong>pole number is known
              and only the current active record is needed</strong>, use the Quick Pole Search page for faster search
            performance.</small>
        </div>
      </div>
    </div>
  </div>
</div>

