<?php
/**
 * Plugin Name: Simple Loan & Mortgage Calculator
 * Plugin URI: http://danielpataki.com
 * Description: Responsive Calculator with shortcode and chart support.
 * Version: 1.0.0
 * Author: Giannis Dallas
 * Author URI: giannisdallas.com
 * License: GPL2
 */

define( 'simple_loan_mortgage_calculator_MY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
include( simple_loan_mortgage_calculator_MY_PLUGIN_PATH . 'simplelmc-config.php');

//include( simple_loan_mortgage_calculator_MY_PLUGIN_PATH . 'assets/acf/acf.php' );

//include( simple_loan_mortgage_calculator_MY_PLUGIN_PATH . 'simple-pregnancy-calculator-widget.php');

/**
 * Proper way to enqueue scripts and styles.
 */


function simplelmc_tether() {
    wp_enqueue_script( 'tether', plugins_url('assets/tether.min.js', __FILE__ ),array(),true);
}

function simplelmc_scripts() {

    wp_enqueue_style( 'bootstrap-4-alpha',plugins_url( 'assets/bootstrap-4.0.0/css/bootstrap.min.css', __FILE__ ),array(),true);
    wp_enqueue_script( 'bootstrap-4-alpha-js',plugins_url('assets/bootstrap-4.0.0/js/bootstrap.min.js', __FILE__ ),array(),true);
    wp_enqueue_script( 'googlecharts-js', plugins_url( 'assets/googlecharts.js', __FILE__ ),array(),true);
    wp_enqueue_style( 'font-awesome-470', plugins_url( 'assets/font-awesome-4.7.0/css/font-awesome.min.css', __FILE__ ),array(),true);

    wp_enqueue_script( 'simplelmc-js', plugins_url( 'js/custom.js', __FILE__ ),array(),true);

     wp_localize_script( 'simplelmc-js', 'php_variables', array(
          'var_1' => '$$$',
          'php_currency' => get_option('currency_field')
          )
     );

    wp_enqueue_style('simplelmc-css', plugins_url( 'css/custom.css', __FILE__ ),array(),true);
}
add_action( 'wp_enqueue_scripts', 'simplelmc_tether' ,0);
add_action( 'wp_enqueue_scripts', 'simplelmc_scripts' ,20);

// Create default options
function simplelmc_activate() {

    update_option( 'title_field', 'Loan & Mortgage Calculator');
    update_option( 'charts_field', true);
    update_option( 'currency_field', '$');
}
register_activation_hook( __FILE__, 'simplelmc_activate' );

/*---- Define maim shortcode ---*/
add_shortcode( 'simplelmc', 'include_simplelmc' );

function include_simplelmc(){

    $opt1 = get_option('title_field');
    $opt2 = ( get_option('chart_field') ? 'displayed':'hidden');
    $opt3 = get_option('currency_field');

    $output='<br><div class="simplelmc-container">
    <h2>'.$opt1.'</h2>
    <div class="input-area">
    <label for="input_value">Initial Value <i class="fa fa-question-circle-o" data-toggle="popover" data-placement="top" data-content="Estimated property value or loan amount"></i></label>
      <div class="input-group">
        <span class="input-group-addon">'.$opt3.'</span>
      <input type="number" class="form-control" id="input_value"  placeholder="Enter value">
      <span class="input-group-addon">.00</span>
      </div>

      <label for="input_down_payment">Down Payment <i class="fa fa-question-circle-o" data-toggle="popover" data-placement="top" data-content="Before the fist payment period"></i></label>
      <div class="input-group">
        <span class="input-group-addon">'.$opt3.'</span>
      <input type="number" class="form-control" id="input_down_payment"  placeholder="Enter value">
      <span class="input-group-addon">.00</span>
      </div>

      <label for="input_interest">Interest rate <i class="fa fa-question-circle-o" data-toggle="popover" data-placement="top" data-content="The average expected interest in % percentage"></i></label>
        <div class="input-group">
          <select id="interest_option" class="form-control">
            <option>Yearly</option>
            <option>Monthly</option>
          </select>
          <input type="number" class="form-control" id="input_interest"  placeholder="Enter Percentage" min="2.00" step="0.01">
            <span class="input-group-addon">%</span>
      </div>

      <label for="input_duration">Loan or Mortgage term <i class="fa fa-question-circle-o" data-toggle="popover" data-placement="top" data-content="Repayment payment in years"></i></label>
        <div class="input-group">
          <input type="number" class="form-control" id="input_duration"  placeholder="Enter Duration" step="1">
          <select id="term_option" class="form-control">
            <option>Years</option>
            <option>Months</option>
          </select>
    </div>
    </div>

    <div>
        <button id="calculate" type="button" class="btn btn-info btn-lg">Generate Report</button>
    </div>

    <div class="card">
      <div class="card-block">
        <h4 class="card-title">Your Loan or Mortgage Details</h4>
        <p id="result" class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>
      </div>
    </div>

    <div id="chart_div" class="custom-chart '.$opt2.' "></div>
    </div>
    ';

             return $output;
}

// Create plugins page links
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'simplelmc_action_links' );

function simplelmc_action_links( $links ) {
   $links[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=simplelmc-settings') ) .'">Settings</a>';
   $links[] = '<a href="https://profiles.wordpress.org/giannisdallas#content-plugins" target="_blank">More plugins by Giannis Dallas</a>';
   return $links;
}