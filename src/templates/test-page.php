<?php 
if ( current_user_can("manage_options") ){
?>
    <div class="wrap">
        <h1>Hello World</h1>

        <?php settings_errors(); ?>
        <form method="post" action="options.php">
            <?php  
                settings_fields("reading");
                do_settings_sections("j8ahmed_test_plugin_1");
                submit_button();
            ?>
        </form>

    </div>

<?php
} else {
?>
    <h1>You do not have permission to view this page</h1>
<?php
}
 
