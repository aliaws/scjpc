<?php $prev_search_query = $_REQUEST['search_query'] ?? '';

//if ( $redirect_url ) { ?>
<!--  <a class="btn" href="--><?php //echo $redirect_url . "&go_back=1" ?><!--" style="color: black;">Go Back</a>-->
<?php //} ?>

<div class="scjpc-navigation-buttons d-flex">
  <a id="go-back" class="btn" style="color: black; margin-right: 10px; display: none;" onclick="window.history.back()">Go Back</a>
  <a id="go-forward" class="btn" style="color: black; display: none;" onclick="window.history.forward()">Go Forward</a>
</div>

<div class="well mw-100 text-secondary mt-5">
  <div class="row result-row">
    <div class="col-sm-6">
      <h5>Pole Number: <?php echo $pole_result['pole_number']; ?></h5>
    </div>
    <div class="col-sm-6">
      <h5 class="text-end">
        <?php $status = $pole_result['status'];
        $class = $status == 'A' ? 'text-success' : 'text-danger'; ?>
        Status: <span class="<?php echo $class; ?>"><?php echo STATUS_LABELS[$status]; ?></span>
      </h5>
    </div>
  </div>
  <div class="row result-row">
    <div class="col-sm-12 text-end"><strong>Unique ID:</strong> <?php echo $pole_result['unique_id']; ?></div>
  </div>
  <div class="row result-row">
    <div class="col-sm-2"><strong>Base Owner:</strong></div>
    <div class="col-sm-10"><?php echo $pole_result['base_owner']; ?></div>
  </div>
  <div class="row result-row">
    <div class="col-sm-2"><strong>Location:</strong></div>
    <div class="col-sm-10 text-uppercase"><?php echo str_replace("''", "'", $pole_result['location']); ?></div>
  </div>
  <div class="row result-row">
    <div class="col-sm-2"><strong>Latitude:</strong></div>
    <div class="col-sm-4">
      <?php echo $pole_result['latitude']; ?>
    </div>
    <div class="col-sm-6">
      <strong>Class:</strong> <?php echo $pole_result['pole_class']; ?>
    </div>
  </div>
  <div class="row result-row">
    <div class="col-sm-2"><strong>Longitude:</strong></div>
    <div class="col-sm-4">
      <?php echo $pole_result['longitude']; ?>
    </div>
    <div class="col-sm-6">
    </div>
  </div>
  <div class="row result-row">
    <div class="col-sm-2"><strong>Pole Height:</strong></div>
    <div class="col-sm-4"><?php echo $pole_result['height']; ?></div>
    <div class="col-sm-6">
      <strong>Top:</strong> <?php echo $pole_result['top_extension']; ?>
    </div>
  </div>
  <div class="row result-row">
    <div class="col-sm-2"><strong>Year Set:</strong></div>
    <div class="col-sm-4"><?php echo $pole_result['year_set']; ?></div>
    <div class="col-sm-6">
      <strong>Treatment:</strong> <?php echo $pole_result['treatment']; ?>
    </div>
  </div>
  <div class="row result-row">
    <div class="col-sm-2"><strong>City:</strong></div>
    <div class="col-sm-4"><?php echo $pole_result['city']; ?></div>
    <div class="col-sm-6">
      <strong>Code:</strong> <?php echo $pole_result['area_code']; ?>
    </div>
  </div>
  <div class="row result-row">
    <div class="col-sm-2"><strong>Repl Pole #:</strong></div>
    <div class="col-sm-4"><?php echo $pole_result['pole_replacement']; ?></div>
    <div class="col-sm-6"></div>
  </div>
  <div class="row result-row">
    <div class="col-sm-3"><strong>Removal/Relinquishment:</strong></div>
    <div class="col-sm-9"><?php echo $pole_result['req_rem'] ?></div>
    <div class="col-sm-5"></div>
  </div>
  <div class="row result-row">
    <div class="col-sm-4"><strong>Member to remove pole or member relinquished:</strong></div>
    <div class="col-sm-4"><?php echo $pole_result['req_rem_co'] ?></div>
    <div class="col-sm-4"></div>
  </div>
  <div class="row result-row">
    <div class="col-sm-1"><strong>Company</strong></div>
    <div class="col-sm-1"><strong>ANT</strong></div>
    <div class="col-sm-4"><strong>Grade & Space</strong></div>
    <div class="col-sm-6"><strong>Additional Info</strong></div>
  </div>
  <?php for ($i = 1; $i <= 10; $i++) { ?>
    <?php if ($pole_result["company_{$i}"]) { ?>
      <?php $company_gns = $pole_result["company_{$i}_gn_s"] ? str_replace("''", "'", $pole_result["company_{$i}_gn_s"]) : ''; ?>
      <div class="row result-row">
        <div class="col-sm-1"><?php echo $pole_result[ "company_{$i}" ]; ?></div>
        <div class="col-sm-1"><?php echo $pole_result[ "antenna{$i}" ] ? "ANT" : '-'; ?></div>
        <div class="col-sm-4"><?php echo $company_gns; ?></div>
        <div class="col-sm-6">
          <?php echo $pole_result[ "anc_for_company_{$i}" ]; ?>
          <?php if ( $i <= 3 && ! empty ( $pole_result[ "anc_for_company_{$i}_a" ] ) ) { ?>
            <span style="padding: 0 20px;">|</span>
            <?php echo $pole_result[ "anc_for_company_{$i}_a" ]; ?>
          <?php } ?>
        </div>
      </div>
    <?php }
  } ?>
  <div class="row result-row">
    <?php $jpas_length = count($jpa_results);; ?>
    <?php
        $jpa_numbers_lbl_css = "col-sm-4";
        $jpa_numbers_data_css = "col-sm-8";
        if ($jpas_length > 4) {
            $jpa_numbers_lbl_css = "col-sm-2";
            $jpa_numbers_data_css = "col-sm-10";
        }
    ?>
    <div class="<?php echo $jpa_numbers_lbl_css; ?>"><strong>JPA Number:</strong></div>
    <div class="<?php echo $jpa_numbers_data_css; ?>">
      <?php $base_cdn_url = rtrim(get_option('scjpc_aws_cdn'), '/');
      $base_cdn_url = str_starts_with($base_cdn_url, 'https://') ? $base_cdn_url : "https://$base_cdn_url";

      foreach ($jpa_results as $index => $jpa_result) {
        $jpa_number = $jpa_result['jpa_number_2'];
        $jpa_detail_url = "/pole-search/?jpa_number=$jpa_number&action=jpa_detail_search&per_page=50&page_number=1&last_id=";
        $jpa_pdf_url = "$base_cdn_url/{$jpa_result['pdf_s3_key']}"; ?>
        <a href="<?php echo $jpa_detail_url; ?>"><?php echo $jpa_number; ?></a>
        <?php if ($jpa_result['pdf_s3_key']) { ?>
          <a href="<?php echo $jpa_pdf_url; ?>" target="_blank">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" viewBox="0 0 16 16"
                 class="bi bi-file-pdf text-danger">
              <path
                d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1"/>
              <path
                d="M4.603 12.087a.8.8 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.7 7.7 0 0 1 1.482-.645 20 20 0 0 0 1.062-2.227 7.3 7.3 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.187-.012.395-.047.614-.084.51-.27 1.134-.52 1.794a11 11 0 0 0 .98 1.686 5.8 5.8 0 0 1 1.334.05c.364.065.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.86.86 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.7 5.7 0 0 1-.911-.95 11.6 11.6 0 0 0-1.997.406 11.3 11.3 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.8.8 0 0 1-.58.029m1.379-1.901q-.25.115-.459.238c-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361q.016.032.026.044l.035-.012c.137-.056.355-.235.635-.572a8 8 0 0 0 .45-.606m1.64-1.33a13 13 0 0 1 1.01-.193 12 12 0 0 1-.51-.858 21 21 0 0 1-.5 1.05zm2.446.45q.226.244.435.41c.24.19.407.253.498.256a.1.1 0 0 0 .07-.015.3.3 0 0 0 .094-.125.44.44 0 0 0 .059-.2.1.1 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a4 4 0 0 0-.612-.053zM8.078 5.8a7 7 0 0 0 .2-.828q.046-.282.038-.465a.6.6 0 0 0-.032-.198.5.5 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822q.036.167.09.346z"/>
            </svg>
          </a>
        <?php }
        if ($index + 1 < $jpas_length) {
          echo " | ";
        }
      } ?>
    </div>
  </div>
  <div class="row result-row">
    <div class="col-sm-12">
      <strong>Bill of Sale:</strong><br><br>
      <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
          <pre><?php echo $pole_result['bos_2']; ?></pre>
        </div>
        <hr>
      </div>
    </div>
  </div>
</div>
