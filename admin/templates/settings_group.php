<div class="wrap">
  <h1>SCJPC Settings</h1>
  <form method="post" action="options.php">
    <?php settings_fields('scjpc-settings-group');
    do_settings_sections('scjpc-settings-group'); ?>
    <table class="form-table">
      <tr>
        <th scope="row">Elasticsearch Host</th>
        <td>
          <label>
            <input type="text" name="scjpc_es_host" placeholder="https://hostorip.com"
                   value="<?php echo esc_attr(get_option('scjpc_es_host')); ?>"/>
          </label>
        </td>
      </tr>

      <tr>
        <th scope="row">Security Key</th>
        <td>
          <label>
            <input type="password" name="scjpc_client_auth_key"
                   value="<?php echo esc_attr(get_option('scjpc_client_auth_key')); ?>"/>
          </label>
        </td>
      </tr>

      <tr>
        <th scope="row">AWS CDN</th>
        <td>
          <label>
            <input type="password" name="scjpc_aws_cdn" value="<?php echo esc_attr(get_option('scjpc_aws_cdn')); ?>"/>
          </label>
        </td>
      </tr>
    </table>
    <?php submit_button(); ?>
  </form>
</div>
