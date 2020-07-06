<?php
/**
 * Partial template for rendering Critical CSS metabox
 *
 * @version 1.0.0
 * @since 1.0.0
 */

wp_nonce_field($action, $name); ?>

<table class="form-table">
  <tbody>
    <tr>
      <th scope="row">
        <label for="critical_styles">
          <strong>Styles</strong>
        </label>
      </th>
      <td>
        <p>Last updated: <?= $data['_updated_at'] ?? 'Never' ?></p>
        <p><textarea class="widefat" rows="4" name="criticalstyles[_styles]" id="critical_styles"><?= $data[
          '_styles'
        ] ?? '' ?></textarea>
        </p>
      </td>
    </tr>
  </tbody>
</table>