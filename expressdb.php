<?php
/*
Plugin Name: ExpressDB Shortcode
Plugin URI: http://expressdb.com
Description: The new ExpressDB Shortcode plugin for WordPress lets you easily add data capture forms to your blog or website, in combination with the ExpressDB cloud database service from Caspio Inc.
Author: Caspio Inc.
Version: 1.0.1
Author URI: http://expressdb.com
*/

add_shortcode('expressdb','expressdb_form');

function expressdb_form($atts, $content = NULL) {
ob_start();

$style = get_option("expressdb_style");
if($content)
	$style .= $content;
?>

<style>
<?=$style?>
</style>

<form name="expressdb" class="expressdb" method="post" action="<?=$atts["action"]?>" enctype="multipart/form-data">  
<table>
<?php

if($atts["text"])
{
$fields = explode(",",$atts["text"]);
foreach($fields as $label)
{
$label = trim($label);
$field_id = preg_replace('/[^A-Za-z0-9]/','_',$label);
$tab++;
?>
<tr>    
<td colspan="2" class="labelcell"><label for="<?=$field_id?>"><?=$label?></label></td>   
<td colspan="2" class="fieldcell"><input class="text" type="text" name="<?=$field_id?>" id="<?=$field_id?>" tabindex="<?=$tab?>" /></td>   
</tr>
<?php
}// end foreach
} // end text fields

if($atts["textarea"])
{
$fields = explode(",",$atts["textarea"]);
foreach($fields as $label)
{
$label = trim($label);
$field_id = preg_replace('/[^A-Za-z0-9]/','_',$label);
$tab++;
?>
<tr>    
<td colspan="2" class="labelcell"><label for="<?=$field_id?>"><?=$label?></label></td>   
<td colspan="2" class="fieldcell"><textarea name="<?=$field_id?>" id="<?=$field_id?>" tabindex="<?=$tab?>" ></textarea></td>   
</tr>
<?php
}// end foreach
} // end textarea fields


if($atts["checkboxes"])
{
$fields = explode(",",$atts["checkboxes"]);
?>
<tr>    
<td colspan="2" class="labelcell"><label>Options</label></td>   
<td colspan="2" class="fieldcell">
<?php
foreach($fields as $label)
{
$label = trim($label);
$field_id = preg_replace('/[^A-Za-z0-9]/','_',strip_tags($label) );
$tab++;
?>
<input type="checkbox" name="<?=$field_id?>" id="<?=$field_id?>" tabindex="<?=$tab?>" /> <label><?=$label?></label> 
<?php
}// end foreach
?>
</td>   
</tr>
<?php
} // end checkboxes


?>
<tr><td colspan="4"><button id="expdbsubmit" type="submit" tabindex="<?=$tab + 1?>" />Submit</button></td></tr>
</table>   
</form>
<?php

return ob_get_clean();
}

function expressdb_activate(){

add_option("expressdb_style",'
.expressdb table, .expressdb td, expressdb tr, .expressdb input, .expressdb textarea, .expressdb label, .expressdb p {margin: 0; padding: 0; font-size: 12px; font-family: font-family: Arial, Helvetica, sans-serif;}

form.expressdb {padding-top: 15px;padding-bottom: 15px;}

.expressdb table {   
 background-color: #F9FBFD;   
 color: #000000;   
 width: 440px;   
 border: 1px solid #D7E5F2;   
 border-collapse: collapse;   
}   
  
.expressdb td {   
 border: 1px solid #D7E5F2;   
 padding-left: 4px;
 vertical-align: top;   
}

.labelcell {
 font: 11px Verdana, Geneva, Arial, Helvetica, sans-serif;    
 color: #3670A7;    
 background-color: transparent;    
 width: 220px;    
}

.fieldcell label {
 font: 11px Verdana, Geneva, Arial, Helvetica, sans-serif;    
 color: #3670A7;    
}
   
.fieldcell {    
 background-color: #F2F7FB;    
 color: #000000;    
 text-align: right;    
 margin-right: 0px;    
 padding-right: 0px;    
}    
   
.smalllabelcell {    
 font: 11px Verdana, Geneva, Arial, Helvetica, sans-serif;    
 background-color: transparent;    
 color: #3670A7;    
 width: 100px;    
}    
   
.smallfieldcell {    
 background-color: #F2F7FB;    
 color: #000000;    
 text-align: right;    
    
}
.expressdb input.text,.expressdb textarea {
width: 250px;
}

#expdbsubmit {
background-color: #0000FF;
color: #FFFFFF;
font-weight: bold;
}
');
}

//call register settings function
add_action( 'admin_init', 'register_expressdb_settings' );

function register_expressdb_settings() {
	//register our settings
	register_setting( 'expressdb-settings-group', "expressdb_style" );
}

function expressdb_settings_page() {
?>
<div class="wrap">
<h2>ExpressDB Shortcode</h2>
<p>Set the default styling for your form</p>
<form method="post" action="options.php">
<?php settings_fields( 'expressdb-settings-group' );
	
	$expressdb_style = get_option('expressdb_style');
?>
<textarea name="expressdb_style" id="expressdb_style" rows="20" cols="80"><?php echo $expressdb_style; ?></textarea>
<button>Save</button>
</form>
<?php
}

// create custom plugin settings menu
add_action('admin_menu', 'expressdb_create_menu');

function expressdb_create_menu() {
	//create new top-level menu
	add_submenu_page('options-general.php','ExpressDB', 'ExpressDB', 'manage_options', 'expressdb_settings_page', 'expressdb_settings_page');
}

register_activation_hook( __FILE__, 'expressdb_activate' );
?>