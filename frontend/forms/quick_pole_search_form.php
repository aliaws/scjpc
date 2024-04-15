<div class="card p-4">
  <form class="needs-validation" id="quick_pole_search" action="<?php echo get_permalink(get_the_ID()); ?>" method="get"
        novalidate>
    <div class="mb-3">
      <label for="pole_number" class="form-label">Enter Pole Number</label>
      <input type="text" name="pole_number" class="form-control" id="jpa_number"
             value="<?php echo $_GET['pole_number'] ?? ''; ?>" required />
      <input type="hidden" id="action" name="action" value="quick_pole_search" />
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
      Example: Pole: 123456E (instead of 123456-E).
    </small>
  </p>
</div>

