<div class="mase-bs mase-container">
    <select multiple="multiple" name="_connection_ids[]" data-width="100%" class="widefat mase_select2_simple" id="">
        <option <?php if(in_array(MASE_CONNECTION_3G, $_selected_connections)) echo 'selected="SELECTED" '; ?>value="<?php echo MASE_CONNECTION_3G; ?>"><?php _e('Mobile', MASE_TEXT_DOMAIN); ?></option>
        <option <?php if(in_array(MASE_CONNECTION_WIFI, $_selected_connections)) echo 'selected="SELECTED" '; ?>value="<?php echo MASE_CONNECTION_WIFI; ?>"><?php _e('WIFI', MASE_TEXT_DOMAIN); ?></option>
    </select>
</div>