<?php

//1. Setup Admin page

add_action( 'admin_menu', 'simplelmc_create_plugin_settings_page' );

function simplelmc_create_plugin_settings_page() {
	// Add the menu item and page
	$page_title = 'Loan and Mortgage Calculator Settings Page';
	$menu_title = 'Loan & Mortgage';
	$capability = 'manage_options';
	$slug = 'simplelmc-settings';
	$callback = 'simplelmc_settings_page_content';
	$icon = 'dashicons-chart-area';
	$position = 100;

	add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
}

function simplelmc_settings_page_content() { ?>
	<div class="wrap">
		<h2>Simple Loan & Mortgage Calculator Settings</h2>
		<form method="post" action="options.php">
            <?php
                settings_fields( 'simplelmc-settings' );
                do_settings_sections( 'simplelmc-settings' );
                submit_button();
            ?>
		</form>
	</div> <?php
}

//2. Setup Sections and Fields

add_action( 'admin_init', 'simplelmc_setup_sections' );
add_action( 'admin_init', 'simplelmc_setup_fields' );

function simplelmc_setup_sections() {
	add_settings_section( 'simplelmc_section', 'Basic Calculator Settings', 'simplelmc_section_callback', 'simplelmc-settings' );
}

function simplelmc_section_callback( $arguments ) {
    			echo 'Please edit the options !';
 }

 function simplelmc_setup_fields() {
 	$fields = array(
 		array(
 			'uid' => 'title_field',
 			'label' => 'Custom title',
 			'section' => 'simplelmc_section',
 			'type' => 'text',
 			'options' => false,
 			'placeholder' => 'Type here',
 			'helper' => '',
 			'supplemental' => 'Add your oun title',
 			'default' => 'Mortgage Calculator'
 		),
 		array(
         	'uid' => 'chart_field',
         	'label' => 'Display Chart',
         	'section' => 'simplelmc_section',
         	'type' => 'checkbox',
         	'options' => false,
         	'placeholder' => '',
         	'helper' => 'Please display the chart',
         	'supplemental' => 'Select to display the Google chart',
         	'default' => true
        ),
        array(
        	'uid' => 'currency_field',
        	'label' => 'Currency Select',
         	'section' => 'simplelmc_section',
         	'type' => 'select',
         	'options' => array(
         		'$' => '$ Dollar',
         		'€' => '€ Euro',
         		'¥' => '¥ Yen / Yuan',
         		'£' => '£ Pound / Lira'
         	),
         	'placeholder' => 'Pick an otion',
         	'helper' => '',
         	'supplemental' => 'Pick your currency indicator',
         	'default' => '$'
         	)

 	);
 	foreach( $fields as $field ){
 		add_settings_field( $field['uid'], $field['label'], 'simplelmc_field_callback', 'simplelmc-settings', $field['section'], $field );
 		register_setting( 'simplelmc-settings', $field['uid'] );
 	}
 }

 function simplelmc_field_callback( $arguments ) {
     $value = get_option( $arguments['uid']); // Get the current value, if there is one

         if( ! $value ) { // If no value exists
             $value = $arguments['default']; // Set to our default
         }

     	// Check which type of field we want
         switch( $arguments['type'] ){
             case 'text': // If it is a text field
                 printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
                 break;

             case 'checkbox': // if it is a checkbox
                $ischeched = checked(1, get_option('chart_field'), false);
                printf ( '<input name="%1$s" id="%1$s" type="%2$s" value="1" %4$s />', $arguments['uid'], $arguments['type'], $value, $ischeched );
                //echo '<input name="'.$arguments['uid'].'" id="'.$arguments['uid'].'" type="'.$arguments['type'].'" value="1" '.checked(1, get_option('chart_field'), false).' />';

                break;

             case 'select': // If it is a select dropdown
             		if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
             			$options_markup = '';
             			foreach( $arguments['options'] as $key => $label ){
             				$options_markup .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $value, $key, false ), $label );
             			}
             			printf( '<select name="%1$s" id="%1$s">%2$s</select>', $arguments['uid'], $options_markup );
             		}
             		break;
         }

     	// If there is help text
         if( $helper = $arguments['helper'] ){
             printf( '<span class="helper"> %s</span>', $helper ); // Show it
         }

     	// If there is supplemental text
         if( $supplimental = $arguments['supplemental'] ){
             printf( '<p class="description">%s</p>', $supplimental ); // Show it
         }
 }



?>