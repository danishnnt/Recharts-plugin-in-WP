<?php
/**
 * Plugin Name: Recharts with React
 * Author: Danish Khan
 * Description: WordPress React plugin Dashboard.
 */

if( ! defined( 'ABSPATH' ) ) : exit(); endif; 


define ( 'WPRK_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define ( 'WPRK_URL', trailingslashit( plugins_url( '/', __FILE__ ) ) );


add_action( 'admin_enqueue_scripts', 'load_scripts' );
function load_scripts() {
    wp_enqueue_script( 'wp-react-kickoff', WPRK_URL . 'dist/bundle.js', [ 'jquery', 'wp-element' ], wp_rand(), true );
    wp_localize_script( 'wp-react-kickoff', 'appLocalizer', [
        'apiUrl' => home_url( '/wp-json' ),
        'nonce' => wp_create_nonce( 'wp_rest' ),
    ] );
}


function my_dashboard_widget() {
    wp_add_dashboard_widget(
      'my_dashboard_widget',
      'My Graph Widget',
      'my_dashboard_widget_callback'
    );
  }
  add_action( 'wp_dashboard_setup', 'my_dashboard_widget' );
  
  function my_dashboard_widget_callback() {
    echo '<div id="my-dashboard-widget"></div>';
  }

function dashbord_table_init() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'chartTable';
    $sql = "CREATE TABLE {$table_name} (
			id INT NOT NULL AUTO_INCREMENT,
			`name` VARCHAR(250),
      uv INT,
      pv INT,
      amt INT,
      dateT DATE,
			PRIMARY KEY (id)
	);";
    require_once ABSPATH . "wp-admin/includes/upgrade.php";
    dbDelta( $sql );

		$insert_query = "INSERT into ".$table_name." (name,uv,pv,amt,dateT) VALUES 
    ('Page A',4000,2000,2400,'2024-04-01'),
    ('Page B',2000,4000,3000,'2024-04-13'),
    ('Page C',6000,3000,2000,'2024-04-6'),
    ('Page D',1000,2000,5000,'2024-04-1'),
    ('Page E',6000,1000,4000,'2024-04-16')
    ";

		$wpdb->query($insert_query);
}

register_activation_hook( __FILE__, "dashbord_table_init" );


require_once WPRK_PATH . 'classes/class-create-settings-routes.php';