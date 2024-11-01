<?php
/**
 * Plugin Name: Unique Chat SE
 * Description: Use our live chat in wordpress
 * Version: 1.1
 * Author: Unique
 * Author URI: https://uniquelibrary.com/patron-services/live-web-chat-software/
 * License: GPL2
 */

class UniqueChatSESettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_uniquechatse_page' ) );
        add_action( 'admin_init', array( $this, 'uniquechatse_init' ) );
    }

    /**
     * Add options page
     */
    public function add_uniquechatse_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Unique Chat SE', 
            'manage_options', 
            'uniquechatse_settings', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'uniquechatse_option' );
        $img = plugins_url( 'img/uniquechatse.png' , __FILE__ );

        ?>
        <div class="wrap">
            <?php printf('<a target="_blank" class="logo" href="https://uniquelibrary.com/patron-services/live-web-chat-software/"><img src="%s" alt="UniqueChatSE"></a>', $img); ?>
            <h2>Unique Chat Settings</h2>
            <p>By setting your Unique Chat Widget ID below and enabling the widget you'll install Unique Chat on your Wordpress site.</p>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'uniquechatse_group' );   
                do_settings_sections( 'uniquechatse_settings' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function uniquechatse_init()
    {        
        register_setting(
            'uniquechatse_group', // Option group
            'uniquechatse_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'uniquechatse_settings' // Page
        );  

        add_settings_field(
            'enable', 
            'Enable Unique Chat', 
            array( $this, 'enabled_callback' ), 
            'uniquechatse_settings', 
            'setting_section_id'
        );   

        add_settings_field(
            'widget_id', // ID
            'Widget ID', // Title 
            array( $this, 'widget_id_callback' ), // Callback
            'uniquechatse_settings', // Page
            'setting_section_id' // Section           
        );      
   
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['widget_id'] ) )
            $new_input['widget_id'] = sanitize_text_field( $input['widget_id'] );

        if( isset( $input['enable'] ) )
            $new_input['enable'] = $input['enable'];

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Please enter your Unique Chat data below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function widget_id_callback()
    {
        printf(
            '<input type="text" id="widget_id" name="uniquechatse_option[widget_id]" value="%s" /> ',
            isset( $this->options['widget_id'] ) ? esc_attr( $this->options['widget_id']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function enabled_callback()
    {
    	$checked = '';
    	if ($this->options['enable'] == 'checked') {
    		$checked = 'checked="true"';
    	}
        printf(
            '<input type="checkbox" id="enable" name="uniquechatse_option[enable]" value="checked" %s/>',
            $checked
        );
    }
}

$localWidgetId = null;

if( is_admin() )
    $uniquechatse = new UniqueChatSESettingsPage();
  
    
function load_uniquechatse_scripts(){
	global $localWidgetId;
	wp_enqueue_script('uniquechatse-script', plugin_dir_url( __FILE__ ) . 'js/uniquechatse-script.js',null,'1.1.10');
	wp_localize_script('uniquechatse-script', 'uniquechatse_script_vars', array('widget_id' => $localWidgetId));
}


$uniquechatse = get_option( 'uniquechatse_option' );
if($uniquechatse['enable'] && $uniquechatse['widget_id']) {
	$localWidgetId=$uniquechatse['widget_id'];
	add_action( 'wp_enqueue_scripts', 'load_uniquechatse_scripts' );
}

?>