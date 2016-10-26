<div class="mase-bs mase-container">
    <span title="<?php _e('All countries', MASE_TEXT_DOMAIN); ?>" class="label label-info mase_quickselect mase_quickselect_all"><?php _e('Quick Select All', MASE_TEXT_DOMAIN); ?></span>
    <span title="<?php _e('No countries', MASE_TEXT_DOMAIN); ?>" class="label label-info mase_quickselect mase_quickselect_none"><?php _e('Quick Select None', MASE_TEXT_DOMAIN); ?></span>
    <span title="<?php _e('Current Country of the Admin', MASE_TEXT_DOMAIN); ?>" class="label label-info mase_quickselect mase_quickselect_admin"><?php _e('Quick Select Current Admin Country', MASE_TEXT_DOMAIN); ?></span>
    <span title="<?php _e('Select the countries Germany, Austria, Switzerland', MASE_TEXT_DOMAIN); ?>" class="label label-info mase_quickselect mase_quickselect_dachplus"><?php _e('Quick Select DACH+', MASE_TEXT_DOMAIN); ?></span>
    <span title="<?php _e('All countries except Germany, Austria, Switzerland', MASE_TEXT_DOMAIN); ?>" class="label label-info mase_quickselect mase_quickselect_dachminus"><?php _e('Quick Select DACH-', MASE_TEXT_DOMAIN); ?></span>
    <br/><br/>

    <select name="_geoip[]" class="mase_country_select" multiple="multiple">
        <?php
        foreach(MASE::$countries as $cc => $country) {
        ?>
            <option <?php selected(in_array($cc, $_selected_geoip), 1); ?> value="<?php echo $cc ?>"><?php echo $country ?></option>
        <?php
        }
        ?>
    </select>
</div>