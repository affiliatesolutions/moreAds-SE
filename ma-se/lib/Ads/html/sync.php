<div class="mase-bs mase-container">
    <p>
        <?php if($_sync) { ?>
            <input name="_sync_b" <?php checked($_sync) ?> disabled="DISABLED" type="checkbox"> <?php _e('Mark as Global Ad', MASE_TEXT_DOMAIN) ?>
            <input type="hidden" name="_sync" value="1" />
        <?php } else { ?>
            <input name="_sync" <?php checked($_sync) ?> type="checkbox"> <?php _e('Enable Synchronization', MASE_TEXT_DOMAIN) ?>
        <?php } ?>

    </p>
    <small><?php _e('Information:', MASE_TEXT_DOMAIN); ?>:</small><br/>
    <small><?php _e('This Ad will be automatically synchronized between all your WordPress Instances', MASE_TEXT_DOMAIN); ?>.</small><br/>
    <small><?php _e('You will gain the possibility to view impression, click and ctr statistics for this ad.', MASE_TEXT_DOMAIN); ?>.</small><br/><br/>

    <small><?php _e('Warning', MASE_TEXT_DOMAIN); ?>:</small><br/>
    <small><?php _e('This option cannot be disabled after activating it', MASE_TEXT_DOMAIN); ?>.</small>
</div>