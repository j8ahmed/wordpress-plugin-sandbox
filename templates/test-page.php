<?php 
if ( current_user_can("manage_options") ){
?>
    <h1>Hello World</h1>

<?php
} else {
?>
    <h1>You do not have permission to view this page</h1>
<?php
}
 
