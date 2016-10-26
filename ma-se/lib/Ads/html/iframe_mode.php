<div class="mase-bs mase-container">
    <p>
        <input name="_iframe_mode" value="1" <?php checked($_iframe_mode) ?> type="checkbox"> <?php _e('Enable IFrame Legacy Mode', MASE_TEXT_DOMAIN) ?><br/><br/>
        <small><?php _e('Information', MASE_TEXT_DOMAIN); ?>:</small><br/>
        <small><?php _e('The HTML Code is run inside an IFrame on the website.', MASE_TEXT_DOMAIN); ?></small><br/>
        <small><?php _e('This enables ads which rely on document.write javascript code (AdSense calls it "Synchronous Ads" to work with moreAds SE.', MASE_TEXT_DOMAIN); ?></small><br/>
        <small><?php _e('For more information about synchronous vs asynchronous ads you can read this <a target="_blank" href="http://www.matrudev.com/post/synchronous-asynchronous-adsense/">article</a>.', MASE_TEXT_DOMAIN); ?></small><br/>
        <small><?php _e('Please use this mode only if your ad is not shown.', MASE_TEXT_DOMAIN); ?></small><br/>
        <small><?php _e('Please note, that this Iframe way to display ads is forbidden with Google AdSense and can cause an account termination.', MASE_TEXT_DOMAIN); ?></small><br/><br/>
    </p>
</div>