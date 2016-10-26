<?php
$wpma2_errors = false;
$wpma2_infos = false;

if(isset($_POST['add-sidebar'])) {

    $sidebar_name = htmlspecialchars($_REQUEST['sidebar-name']);
    $key = preg_replace("/[^a-z0-9-_]/i", "", strtolower(htmlspecialchars($_REQUEST['sidebar-name'])));
    if(!isset(MASE_Shortcode_Widgets::$SideBars[$key])) {
        if(
        MASE_Shortcode_Widgets::addShortCode(array(
            'name' => $sidebar_name
        ))
        ) {
            $wpma2_infos[] = array(
                'text' => __('The Shortcode was created successfully.', MASE_TEXT_DOMAIN),
                'type' => __('Success', MASE_TEXT_DOMAIN)
            );
        } else {
            $wpma2_errors[] = array(
                'text' => __('The Shortcode could not be created. Please try again.', MASE_TEXT_DOMAIN)
            );
        }

    } else {
        $wpma2_errors[] = array(
            'text' => __('A Shortcode with that name already exists. Please try again.', MASE_TEXT_DOMAIN)
        );
    }
}

if(isset($_POST['delete-sidebar'])) {

    $sidebar_name = htmlspecialchars($_REQUEST['sidebar-name']);
    if(!isset(MASE_Shortcode_Widgets::$SideBars[strtolower($sidebar_name)])) {
        $wpma2_errors[] = array(
            'text' => __('The selected shortcode does not exist and therefore can not be deleted.', MASE_TEXT_DOMAIN)
        );

    } else {
        unset(MASE_Shortcode_Widgets::$SideBars[strtolower($sidebar_name)]);
        MASE_Shortcode_Widgets::set_sidebars(MASE_Shortcode_Widgets::$SideBars);
        $wpma2_infos[] = array(
            'text' => __('The selected shortcode was deleted successfully.', MASE_TEXT_DOMAIN),
            'type' => __('Success', MASE_TEXT_DOMAIN)
        );
    }
}

?>


<div class="wrap">
    <div class="mase-bs">
        <?php if($wpma2_errors) { foreach($wpma2_errors as $error) { ?>
            <div class="alert alert-danger" role="alert">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <span class="sr-only"><?php _e('Error', MASE_TEXT_DOMAIN); ?>:</span>
                <?php echo $error['text']; ?>
            </div>
        <?php } } ?>

        <?php if($wpma2_infos) { foreach($wpma2_infos as $info) { ?>
            <div class="alert alert-success" role="alert">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <span class="sr-only"><?php echo $info['type']; ?></span>
                <?php echo $info['text']; ?>
            </div>
        <?php } } ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php _e('Create Shortcode Widget Area', MASE_TEXT_DOMAIN) ?>
                        </div>
                        <div class="panel-body" style="font-size: 13px;">
                            <?php
                            _e('moreAds SE Shortcode Widget Areas is a tool which allows you to create widget areas.', MASE_TEXT_DOMAIN);
                            echo '<br/>';
                            _e('After creating a widget area you will find the area under Appearance > Widgets.', MASE_TEXT_DOMAIN);
                            echo '<br/>';
                            _e('You can add any moreAds SE zones to this area but also any other Widget you want.', MASE_TEXT_DOMAIN);
                            echo '<br/>';
                            _e('Use the shown WP Shortcode or Theme PHP Code to display the widget area on any place inside your website.', MASE_TEXT_DOMAIN);
                            echo '<br/>';
                            ?>
                        </div>
                        <div class="panel-footer">
                            <form method="post" action="">
                                <div class="button-float-wrapper" style="min-height: 40px;">
                                    <input type="hidden" name="add-sidebar" value="1" />
                                    <input placeholder="<?php _e('Your Shortcode Widget Name', MASE_TEXT_DOMAIN); ?>" style="height: 40px; width: 250px; display: inline-block;" class="form-control pull-left" type="text" name="sidebar-name" value="<?php echo htmlspecialchars($_REQUEST['name']); ?>" />
                                    <button style="display: inline-block; margin-left: 10px;" name="mase_add" class="btn btn-info media-button icon-btn btn-sm"><span class="glyphicon btn-glyphicon glyphicon glyphicon-plus img-circle text-info"></span> <?php _e('Create', MASE_TEXT_DOMAIN); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php if(!empty(MASE_Shortcode_Widgets::$SideBars)) { ?>
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php _e('Shortcode Widget Areas', MASE_TEXT_DOMAIN) ?>
                        </div>
                        <div class="panel-body">
                            <div class="alert alert-info" role="alert">
                                <strong><?php _e('Heads up!', MASE_TEXT_DOMAIN); ?></strong>
                                <?php _e('moreAds SE Shortcodes can only be embedded once on each page.', MASE_TEXT_DOMAIN); ?>
                            </div>

                                <div id="no-more-tables">
                                    <table class="table col-sm-12 table-bordered table-striped table-condensed cf">
                                        <thead>
                                        <tr>
                                            <th style="text-align: center;" width="25%"><?php _e('Name', MASE_TEXT_DOMAIN) ?></th>
                                            <th style="text-align: center;" width="25%"><?php _e('WP Shortcode', MASE_TEXT_DOMAIN) ?></th>
                                            <th style="text-align: center;" width="25%"><?php _e('Theme PHP Code', MASE_TEXT_DOMAIN) ?></th>
                                            <th style="text-align: center;"><?php _e('Created', MASE_TEXT_DOMAIN); ?></th>
                                            <th style="text-align: center;"><?php _e('Actions', MASE_TEXT_DOMAIN); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach((array) MASE_Shortcode_Widgets::$SideBars as $sidebar) { ?>
                                            <tr>
                                                <td style="vertical-align: middle; text-align: center;" data-title="Name"><b><?php echo $sidebar['name']; ?></b></td>
                                                <td style="vertical-align: middle; text-align: center;" data-title="WP Shortcode">
                                                    <pre style="text-align: left;">[mase_widget_area name="<?php echo $sidebar['key']; ?>"]</pre>
                                                </td>
                                                <td data-title="Theme PHP Code">
                                                    <pre>&lt;?php if(function_exists('dynamic_sidebar')) {
    dynamic_sidebar('<?php echo 'mase_widget_area_'.$sidebar['key']; ?>');
} ?&gt;</pre>
                                                </td>
                                                <td style="vertical-align: middle; text-align: center;" data-title="<?php _e('Created', MASE_TEXT_DOMAIN); ?>"><?php echo date('d.m.Y H:i:s', $sidebar['created']); ?></td>
                                                <td style="vertical-align: middle; text-align: center;">

                                                    <form method="post" action="">
                                                        <input type="hidden" name="delete-sidebar" value="1" />
                                                        <input type="hidden" name="sidebar-name" value="<?php echo $sidebar['key']; ?>" />
                                                        <button class="btn btn-danger media-button icon-btn btn-sm"><span style="padding:5px;" class="glyphicon btn-glyphicon glyphicon glyphicon-minus img-circle text-info"></span> <?php _e('Delete', MASE_TEXT_DOMAIN); ?></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>