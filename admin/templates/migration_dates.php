<?php
$migration_date = scjpc_prepare_date_format(get_option('scjpc_migration_date'), 'd/m/Y', 'Y-m-d');
$latest_billed_jpa = scjpc_prepare_date_format(get_option('scjpc_latest_billed_jpa_date'), 'm/y', 'Y-m-d');
$latest_billed_jpa_pdf = scjpc_prepare_date_format(get_option('scjpc_latest_billed_jpa_pdf_date'), 'm/y', 'Y-m-d');
?>
<div class="wrap">
  <h1>SCJPC Migration Dates Settings</h1>
  <form method="post" action="options.php">
    <?php settings_fields('scjpc-migration-dates-settings');
    do_settings_sections('scjpc-migration-dates-settings'); ?>
    <table class="form-table">
      <tr>
        <th scope="row">Migration Date</th>
        <td>
          <label>
            <input type="date" name="scjpc_migration_date" placeholder="MM/DD/YYYY"
                   value="<?php echo esc_attr($migration_date); ?>"/>
          </label>
        </td>
      </tr>

      <tr>
        <th scope="row">Latest Billed JPA Date</th>
        <td>
          <label>
            <input type="date" name="scjpc_latest_billed_jpa_date" placeholder="MM/YYYY"
                   value="<?php echo esc_attr($latest_billed_jpa); ?>"/>
          </label>
        </td>
      </tr>

      <tr>
        <th scope="row">Latest Billed JPA PDF Date</th>
        <td>
          <label>
            <input type="date" name="scjpc_latest_billed_jpa_pdf_date"
                   value="<?php echo esc_attr($latest_billed_jpa_pdf); ?>" placeholder="MM/YYYY"/>
          </label>
        </td>
      </tr>
    </table>
    <?php submit_button(); ?>
  </form>
</div>
