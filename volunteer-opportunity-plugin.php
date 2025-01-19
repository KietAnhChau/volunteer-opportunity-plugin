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

}


function plugin_uninstall()
{
   global $wpdb;

   $wpdb->query("DROP TABLE volunteer_opportunities");
}


function admin_page_html()
{
   ?>

   <?php
}

function admin_page()
{

}

// Add Admin Page
add_action("admin_menu", "admin_page");


// Register Custom Hooks
register_activation_hook(__FILE__, "plugin_activate");
register_deactivation_hook(__FILE__, "plugin_deactivate");
register_uninstall_hook(__FILE__, "plugin_uninstall");


?>