<div class="card p-4">
  <form class="needs-validation" id="jpa_detail_search" action="<?php echo get_permalink(get_the_ID()); ?>" method="get"
        novalidate>
    <div class="mb-3">
      <!--      <label for="pole_number" class="form-label">Enter Pole Number</label>-->
      <input type="hidden" name="jpa_number" class="form-control" id="jpa_number"
             value="<?php echo $_GET['jpa_number'] ?? ''; ?>" required/>
      <div id="jpa_number_feedback" class="invalid-feedback"> Please choose a Pole number.</div>
    </div>
    <input type="hidden" id="action" name="action" value="jpa_detail_search"/>
    <input type="hidden" id="per_page" name="per_page" value="<?php echo $_GET['per_page'] ?? '50'; ?>"/>
    <input type="hidden" id="page_number" name="page_number" value="<?php echo $_GET['page_number'] ?? '1'; ?>"/>
    <input type="hidden" id="last_id" name="last_id" value="<?php echo $_GET['last_id'] ?? ''; ?>"/>
    <!--    <div class="d-flex justify-content-between">-->
    <!--      <button type="button" class="clearBtn btn btn-secondary">Clear</button>-->
    <!--      <button type="submit" class="btn btn-primary">Search</button>-->
    <!--    </div>-->
  </form>
  <!--  <p class="text mt-4 fw-light ">-->
  <!--    <small>-->
  <!--      To search: omit space, slash, hyphen, and other special characters.-->
  <!--      <br>-->
  <!--      Example: Pole: 123456E (instead of 123456-E).-->
  <!--    </small>-->
  <!--  </p>-->
</div>

