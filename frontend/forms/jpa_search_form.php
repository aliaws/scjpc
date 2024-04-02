<?php

?>
<div class="card p-4">
    <form class="needs-validation" action="<?php SCJPC_PLUGIN_BACKEND_BASE . '/data.php' ?>" method="get" novalidate>
        <div class="mb-3">
            <label for="jpa_number" class="form-label">Enter JPA Number</label>
            <input type="text" name="jpa_number" class="form-control" id="jpa_number" required>
          <input type="text" name="call_method" value="jpa_search" id="jpa_number" hidden="hidden">

          <div id="jpa_number_feedback" class="invalid-feedback">
            Please choose a JPA number.
          </div>
        </div>
        <div class="d-flex justify-content-between">
            <button type="button" class="clearBtn btn btn-secondary">Clear</button>
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>
    <p class="text mt-4 fw-light ">
        <small>
            To search: omit space, slash, hyphen, and other special characters.
            <br>
            Example: JPA: ABC1256NN12 (instead of ABC-1256/NN-12).
        </small>
    </p>
</div>

