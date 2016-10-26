<div class="mase-bs mase-container">
    <select multiple="multiple" name="_devices[]" data-width="100%" class="widefat mase_select2_simple" id="">
        <option <?php if(in_array(MASE_DEVICE_DESKTOP, $_selected_devices)) echo 'selected="SELECTED" '; ?>value="<?php echo MASE_DEVICE_DESKTOP; ?>"><?php _e('Desktop', MASE_TEXT_DOMAIN); ?></option>
        <option <?php if(in_array(MASE_DEVICE_TABLET, $_selected_devices)) echo 'selected="SELECTED" '; ?>value="<?php echo MASE_DEVICE_TABLET; ?>"><?php _e('Tablet', MASE_TEXT_DOMAIN); ?></option>
        <option <?php if(in_array(MASE_DEVICE_MOBILE, $_selected_devices)) echo 'selected="SELECTED" '; ?>value="<?php echo MASE_DEVICE_MOBILE; ?>"><?php _e('Smartphone', MASE_TEXT_DOMAIN); ?></option>
    </select>
</div>