<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   PluginName
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */

 
 if($_POST['send']){
 
	// SAVE THAT SHIT
	
	$cf7fiScript = get_option('_cf7fi_script','');
	if($cf7fiScript == ''){
		add_option( '_cf7fi_script', $_POST['cf7fi-code'], '', 'no' );
	}else{
		update_option( '_cf7fi_script', $_POST['cf7fi-code'] );
	}
	
	// REDIRECT NICELY
 
 }
 
 
 ?>



<div class="wrap">

	<?php 
	
		screen_icon(); 
	
		$cf7fiScript = stripslashes(get_option('_cf7fi_script',''));
	
	?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	
	<form action="" method="post">
		<table class="form-table">
			<tr valign="top">
			<th scope="row" > <label for="cf7ficode"> Put your Formisimo Tracking Code in here </label> </th>
			<td> <textarea cols="100" rows="8" name="cf7fi-code"><?php echo esc_html($cf7fiScript); ?></textarea> </td>
			</tr>
			<tr valign="top">
			<th scope="row"></th >
			<td> <input type="submit" class="button-primary" name="sender" value="save Code" /> </td>
			</tr>
			</tr>
		</table>
		
		
		
		<input type="hidden" name="send" value="send" />
	</form>
	
	<!-- TODO: Provide markup for your options page here. -->

</div>
