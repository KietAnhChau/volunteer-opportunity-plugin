<?php

/**
* Plugin Name: Volunteer Opportunity Plugin
* Description: A plugin for Volunteer Opportunity.
* Author: Kiet Anh Chau
* Version: 1.0
* 
*/



function plugin_activate()
{
   global $wpdb;

   $wpdb->query("CREATE TABLE volunteer_opportunities (
         id int AUTO_INCREMENT PRIMARY KEY,
         position varchar(255),
         organization varchar(255),
         type varchar(50),
         email varchar(255),
         description text,
         location varchar(255),
         hours int,
         skills_required text
   );");
}

function plugin_deactivate()
{
   global $wpdb;

   $wpdb->query("TRUNCATE TABLE volunteer_opportunities");
}


function plugin_uninstall()
{
   global $wpdb;

   $wpdb->query("DROP TABLE volunteer_opportunities");
}


function admin_page_html()
{
   ?>
      <div class="wrap">
         <h2><?php echo esc_html(get_admin_page_title()) ?></h2> 
      </div>

   <?php
}


/**
 * Adds a custom admin page to the WordPress dashboard.
 *
 * ref: https://developer.wordpress.org/reference/functions/add_menu_page/
 * @return void
 */
function admin_page()
{
   /* add_menu_page( 
      string $page_title, 
      string $menu_title, 
      string $capability, 
      string $menu_slug, 
      callable $callback = '', 
      string $icon_url = '', 
      int|float $position = null ): string
   */

   add_menu_page(
      "Volunteer",
      "Volunteer",
      "manage_options",
      "volunteer",
      "admin_page_html",
      "",
      20
  );
}

// Add Shortcode
function shortcode($atts = [], $content = NULL)
{

}


// Add Shortcode
add_shortcode("add", "wporg_shortcode");

// Add Admin Page
add_action("admin_menu", "admin_page");


// Register Custom Hooks
register_activation_hook(__FILE__, "plugin_activate");
register_deactivation_hook(__FILE__, "plugin_deactivate");
register_uninstall_hook(__FILE__, "plugin_uninstall");


?>