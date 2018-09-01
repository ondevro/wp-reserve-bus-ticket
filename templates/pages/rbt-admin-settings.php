<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
    	<div class="wrap">
    		<h2>My Awesome Settings Page</h2>
    		<?php if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) { ?>
	        <div class="notice notice-success is-dismissible">
	            <p>Your settings have been updated!</p>
	        </div>
            <?php } ?>
    		<form method="POST" action="options.php">
                <?php
                    settings_fields( 'smashing_fields' );
                    do_settings_sections( 'smashing_fields' );
                    submit_button();
                ?>
    		</form>
		</div>