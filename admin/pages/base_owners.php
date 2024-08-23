<?php $base_owners = scjpc_get_base_owners(); ?>
<div class="wrap">
  <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
  <table class="wp-list-table widefat fixed striped">
    <thead>
    <tr>
      <th scope="col">Code</th>
      <th scope="col">Name</th>
      <th scope="col">Status</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($base_owners)) { ?>
      <?php foreach ($base_owners as $owner) { ?>
        <tr id="row-<?php echo esc_attr($owner['base_owner_code']); ?>">
          <td><?php echo esc_html($owner['base_owner_code']); ?></td>
          <td><?php echo esc_html($owner['base_owner_name']); ?></td>
          <td>
            <button class="toggle-status" data-code="<?php echo esc_attr($owner['base_owner_code']); ?>"
                    style="background-color: <?php echo $owner['status'] === 'active' ? 'green' : 'red'; ?>; color: white;">
              <?php echo esc_html(ucfirst($owner['status'])); ?>
            </button>
          </td>
        </tr>
      <?php } ?>
    <?php } else { ?>
      <tr>
        <td colspan="3">No base owners found.</td>
      </tr>
    <?php } ?>
    </tbody>
  </table>
</div>
