<div class="card p-4">
    <form class="needs-validation" action="<?php echo get_permalink( get_the_ID() ); ?>" method="get" novalidate>
        <div class="mb-3">
            <label for="jpa_number" class="form-label">Enter JPA Number</label>
            <input type="text" name="jpa_number" class="form-control" id="jpa_number"  value="<?php echo $_GET['jpa_number'] ?? ''; ?>" required />
            <input type="hidden" id="action" name="action" value="jpa_search" />
            <input type="hidden" id="page" name="page" value="<?php echo $_GET['page'] ?? '1'; ?>" />
            <input type="hidden" id="per_page" name="per_page" value="<?php echo $_GET['per_page'] ?? '50'; ?>" />


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

