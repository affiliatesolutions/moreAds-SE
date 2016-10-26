
<div class="mase-bs">
    <input type="hidden" name="mase_menu_item_id" value="<?php echo $item->ID; ?>" />

    <p style="display: inline-block;">
        <input type="checkbox" value="1" data-id="<?php echo $item->ID; ?>" class="mase-menu-zone-chkbox widefat" name="menu-item-mase-is-menu-zone[<?php echo $item->ID; ?>]" <?php checked($is_menu_zone, "1"); ?> /><label style="margin-bottom: 0; margin-left: 3px;"><?php _e('Use menu entry as moreAds SE Popup Zone', MASE_TEXT_DOMAIN);?> (Beta)</label>
    </p>

    <p class="mase-menu-element-<?php echo $item->ID; ?>" <?php if(empty($is_menu_zone)) { echo 'style="display: none;"'; } ?>>
        <strong><?php _e('Information', MASE_TEXT_DOMAIN); ?>:</strong>
        <small style="margin: 5px;"><?php _e('If a user clicks on the menu entry and no ad can be found for example because of wrong device, wrong country, wrong connection ..., the user will be redirected to the url of the menu entry.', MASE_TEXT_DOMAIN); ?></small>
    </p>

    <p class="mase-menu-element-<?php echo $item->ID; ?>" <?php if(empty($is_menu_zone)) { echo 'style="display: none;"'; } ?>>
        <button data-id="<?php echo $item->ID; ?>" class="button button-primary mase_zone_configurator">
            <?php _e('Start Zone Configurator', MASE_TEXT_DOMAIN); ?>
        </button>

        <?php if($zone_ads_count == 1) {
            echo '<span style="margin-left: 15px; line-height: 28px; background-color: #0085ba;" class="label label-warning">'.sprintf(__("%d Ad in Zone active", MASE_TEXT_DOMAIN), $zone_ads_count).'</span>';
        } elseif($zone_ads_count > 1) {
            echo '<span style="margin-left: 15px; line-height: 28px; background-color: #0085ba;" class="label label-warning">'.sprintf(__("%d Ads in Zone active", MASE_TEXT_DOMAIN), $zone_ads_count).'</span>';
        }
        ?>
    </p>

</div>