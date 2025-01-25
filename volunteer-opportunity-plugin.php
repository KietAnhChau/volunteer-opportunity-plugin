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


/**
 * Renders the HTML for the admin page to create a volunteer opportunity.
 *
 * ref: https://dotorgstyleguide.wordpress.com/
 * @return void
 */
function admin_page_html()
{
   ?>
      <div class="wrap">
         <h1 class="wp-heading-inline"><?php echo esc_html(get_admin_page_title()) ?></h1>
         <hr class="wp-header-end">

         <div class="postbox">
            <div class="inside">
               <h2 >Create Volunteer Opportunity</h2>
               <form method="post">
                  <table class="form-table">
                     <tr>
                        <th scope="row"><label for="title">Title (Position)</label></th>
                        <td><input name="title" type="text" id="title" value="" class="regular-text" required></td>
                     </tr>

                     <tr>
                        <th scope="row"><label for="description">Description</label></th>
                        <td><textarea name="description" id="description" class="large-text" required></textarea></td>
                     </tr>

                     <tr>
                        <th scope="row"><label for="type">Type</label></th>
                        <td>
                           <select name="type" id="type" required>
                           <option selected value="one-time">One-time</option>
                           <option value="recurring">Recurring</option>
                           <option value="seasonal">Seasonal</option>
                           </select>
                        </td>
                     </tr>

                     <tr>
                        <th scope="row"><label for="email">E-mail</label></th>
                        <td><input name="email" type="email" id="email" value="" class="regular-text" required></td>
                     </tr>
                     
                     <tr>
                        <th scope="row"><label for="location">Location</label></th>
                        <td><input name="location" type="text" id="location" value="" class="regular-text" required></td>
                     </tr>

                     <tr>
                        <th scope="row"><label for="hours">Hours</label></th>
                        <td><input name="hours" type="number" id="hours" value="" class="regular-text" required></td>
                     </tr>

                     <tr>
                        <th scope="row"><label for="skills_required">Skills Required</label></th>
                        <td><input name="skills_required" type="text" id="skills_required" value="" class="regular-text" required></td>
                     </tr>
                  </table>

                  <p class="submit">
                     <input type="submit" name="create_opportunity" id="create_opportunity" class="button button-primary" value="Create">
                  </p>
               </form>
            </div>
         </div>
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