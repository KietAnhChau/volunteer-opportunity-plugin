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

   // Insert some data
   $wpdb->insert('volunteer_opportunities', [
      'position' => 'Community Clean-Up',
      'organization' => 'Green Earth',
      'type' => 'One-time',
      'email' => 'contact@greenearth.org',
      'description' => 'Help clean up the local park.',
      'location' => 'Central Park',
      'hours' => 4,
      'skills_required' => 'None'
   ]);

   $wpdb->insert('volunteer_opportunities', [
      'position' => 'Event Organizer',
      'organization' => 'Community Builders',
      'type' => 'One-time',
      'email' => 'events@communitybuilders.org',
      'description' => 'Assist in organizing community events.',
      'location' => 'Community Center',
      'hours' => 6,
      'skills_required' => 'Event Planning, communication, fundraising'
   ]);
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
 * ref: https://developer.wordpress.org/reference/functions/
 * @return void
 */
function admin_page_html()
{
   global $wpdb;

   // Flag
   $edit = false;

   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Create Volunteer Opportunity
      if (isset($_POST['create_opportunity'])) {
         $title = sanitize_text_field($_POST['title']);
         $organization = sanitize_text_field($_POST['organization']);
         $description = sanitize_textarea_field($_POST['description']);
         $type = sanitize_text_field($_POST['type']);
         $email = sanitize_email($_POST['email']);
         $location = sanitize_text_field($_POST['location']);
         $hours = intval($_POST['hours']);
         $skills_required = sanitize_text_field($_POST['skills_required']);

         if (!empty($title) && !empty($organization) && !empty($description) && !empty($type) && !empty($email) && !empty($location) && !empty($hours) && !empty($skills_required)) {
            $wpdb->insert('volunteer_opportunities',
               [
                  'position' => $title,
                  'organization' => $organization,
                  'description' => $description,
                  'type' => $type,
                  'email' => $email,
                  'location' => $location,
                  'hours' => $hours,
                  'skills_required' => $skills_required
               ]
            );

            echo '<div id="create-success-message"><p>Volunteer opportunity created successfully.</p></div>';
            echo '<script>
               setTimeout(function() {
                 var message = document.getElementById("create-success-message");
                 if (message) {
                 message.style.display = "none";
                 }
               }, 5000);
            </script>';
          } else {
            echo '<div id="create-error-message"><p>Please fill in all fields.</p></div>';
            echo '<script>
               setTimeout(function() {
                 var message = document.getElementById("create-error-message");
                 if (message) {
                 message.style.display = "none";
                 }
               }, 5000);
            </script>';
          }
      }

      // Delete Volunteer Opportunity
      if (isset($_POST['delete_opportunity'])) {
         // Validate the id
         $id = intval($_POST['id']);

         // Check if the opportunity exists
         $opportunity = $wpdb->get_row($wpdb->prepare("SELECT * FROM volunteer_opportunities WHERE id = %d", $id));

         // Delete the opportunity
         if ($opportunity) {
            $wpdb->delete('volunteer_opportunities', ['id' => $id]);
            echo '<div id="delete-success-message"><p>Volunteer opportunity deleted successfully.</p></div>';
         } else {
            echo '<div id="delete-error-message"><p>Volunteer opportunity not found.</p></div>';
         }
            echo '<script>
               setTimeout(function() {
                  var message = document.getElementById("delete-success-message") || document.getElementById("delete-error-message");
                  if (message) {
                     message.style.display = "none";
                  }
               }, 5000);
            </script>';
      }

      // Edit Volunteer Opportunity 
      if (isset($_POST['edit_opportunity_setup'])) {
         $id = intval($_POST['id']);

         $opportunity = $wpdb->get_row($wpdb->prepare("SELECT * FROM volunteer_opportunities WHERE id = %d", $id));

         if ($opportunity) {
            $edit = true;
         }
      }

      // Update Volunteer Opportunity
      if (isset($_POST["update_opportunity"])) {
         $id = intval($_POST['id']);
         $title = sanitize_text_field($_POST['title']);
         $organization = sanitize_text_field($_POST['organization']);
         $description = sanitize_textarea_field($_POST['description']);
         $type = sanitize_text_field($_POST['type']);
         $email = sanitize_email($_POST['email']);
         $location = sanitize_text_field($_POST['location']);
         $hours = intval($_POST['hours']);
         $skills_required = sanitize_text_field($_POST['skills_required']);

         if (!empty($title) && !empty($organization) && !empty($description) && !empty($type) && !empty($email) && !empty($location) && !empty($hours) && !empty($skills_required)) {
            $wpdb->update('volunteer_opportunities',
               [
                  'position' => $title,
                  'organization' => $organization,
                  'description' => $description,
                  'type' => $type,
                  'email' => $email,
                  'location' => $location,
                  'hours' => $hours,
                  'skills_required' => $skills_required
               ],
               ['id' => $id]
            );

            echo '<div id="update-success-message"><p>Volunteer opportunity updated successfully.</p></div>';
            echo '<script>
               setTimeout(function() {
                 var message = document.getElementById("update-success-message");
                 if (message) {
                 message.style.display = "none";
                 }
               }, 5000);
            </script>';
         } else {
            echo '<div id="update-error-message"><p>Please fill in all fields.</p></div>';
            echo '<script>
               setTimeout(function() {
                 var message = document.getElementById("update-error-message");
                 if (message) {
                 message.style.display = "none";
                 }
               }, 5000);
            </script>';
         }
      }

   }

   $opportunities = $wpdb->get_results("SELECT * FROM volunteer_opportunities");

   ?>
      <div class="wrap">
         <h1 class="wp-heading-inline"><?php echo esc_html(get_admin_page_title()) ?></h1>
         <hr class="wp-header-end">

         <!-- Create Volunteer Opportunity -->
         <div class="postbox">
            <div class="inside">
               <h2><?php echo $edit ? 'Edit' : 'Create'; ?> Volunteer Opportunity</h2>
               <form method="post">
                  <input type="hidden" name="id" value="<?php echo $edit ? esc_attr($opportunity->id) : ''; ?>">
                  <table class="form-table" action="<?php echo admin_url('admin.php?page=volunteer/volunteer-opportunity-plugin'); ?>">
                     <tr>
                        <th scope="row"><label for="title">Title (Position)</label></th>
                        <td><input name="title" type="text" id="title" value="<?php echo $edit ? esc_attr($opportunity->position) : ''; ?>" class="regular-text" required></td>
                     </tr>

                     <tr>
                        <th scope="row"><label for="organization">Organization</label></th>
                        <td><input name="organization" type="text" id="organization" value="<?php echo $edit ? esc_attr($opportunity->organization) : ''; ?>" class="regular-text" required></td>
                     </tr>

                     <tr>
                        <th scope="row"><label for="description">Description</label></th>
                        <td><textarea name="description" id="description" class="large-text" required><?php echo $edit ? esc_textarea($opportunity->description) : ''; ?></textarea></td>
                     </tr>

                     <tr>
                        <th scope="row"><label for="type">Type</label></th>
                        <td>
                           <select name="type" id="type" required>
                              <option value="one-time" <?php echo $edit && $opportunity->type == 'one-time' ? 'selected' : ''; ?>>One-time</option>
                              <option value="recurring" <?php echo $edit && $opportunity->type == 'recurring' ? 'selected' : ''; ?>>Recurring</option>
                              <option value="seasonal" <?php echo $edit && $opportunity->type == 'seasonal' ? 'selected' : ''; ?>>Seasonal</option>
                           </select>
                        </td>
                     </tr>

                     <tr>
                        <th scope="row"><label for="email">E-mail</label></th>
                        <td><input name="email" type="email" id="email" value="<?php echo $edit ? esc_attr($opportunity->email) : ''; ?>" class="regular-text" required></td>
                     </tr>
                     
                     <tr>
                        <th scope="row"><label for="location">Location</label></th>
                        <td><input name="location" type="text" id="location" value="<?php echo $edit ? esc_attr($opportunity->location) : ''; ?>" class="regular-text" required></td>
                     </tr>

                     <tr>
                        <th scope="row"><label for="hours">Hours</label></th>
                        <td><input name="hours" type="number" id="hours" value="<?php echo $edit ? esc_attr($opportunity->hours) : ''; ?>" class="regular-text" required></td>
                     </tr>

                     <tr>
                        <th scope="row"><label for="skills_required">Skills Required</label></th>
                        <td><input name="skills_required" type="text" id="skills_required" value="<?php echo $edit ? esc_attr($opportunity->skills_required) : ''; ?>" class="regular-text" required></td>
                     </tr>
                  </table>

                  <p class="submit">
                     <input type="submit" name="<?php echo $edit ? 'update_opportunity' : 'create_opportunity'; ?>" id="<?php echo $edit ? 'update_opportunity' : 'create_opportunity'; ?>" class="button button-primary" value="<?php echo $edit ? 'Update' : 'Create'; ?>">
                  </p>
               </form>
            </div>
         </div>

         <!-- Display existing volunteer opportunities -->
         <div class="postbox">
            <div class="inside">
               <h2>Existing Volunteer Opportunities</h2>
               <table class="wp-list-table widefat fixed striped">
                  <thead>
                     <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Organization</th>
                        <th>E-mail</th>
                        <th>Location</th>
                        <th>Hours</th>
                        <th>Skills Required</th>
                        <th>Actions</th>
                     </tr>
                  </thead>

                  <tbody>
                     <?php foreach ($opportunities as $opportunity) : ?>
                        <tr>
                           <td><?php echo esc_html($opportunity->id); ?></td>
                           <td><?php echo esc_html($opportunity->position); ?></td>
                           <td><?php echo esc_html($opportunity->description); ?></td>
                           <td><?php echo esc_html($opportunity->type); ?></td>
                           <td><?php echo esc_html($opportunity->organization); ?></td>
                           <td><?php echo esc_html($opportunity->email); ?></td>
                           <td><?php echo esc_html($opportunity->location); ?></td>
                           <td><?php echo esc_html($opportunity->hours); ?></td>
                           <td><?php echo esc_html($opportunity->skills_required); ?></td>
                           <td>
                              <form method="post" style="display:inline;">
                                 <input type="hidden" name="id" value="<?php echo esc_attr($opportunity->id); ?>">
                                 <input type="submit" name="delete_opportunity" class="button button-secondary" value="Delete">
                              </form>
                              <form method="post" style="display:inline;">
                                 <input type="hidden" name="id" value="<?php echo esc_attr($opportunity->id); ?>">
                                 <input type="submit" name="edit_opportunity_setup" class="button button-primary" value="Edit">
                              </form>
                           </td>
                        </tr>
                     <?php endforeach; ?>
                  </tbody>
               </table>
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
// ref: https://developer.wordpress.org/reference/functions/shortcode_atts/
function volunteer_shortcode($atts = [], $content = NULL)
{
   global $wpdb;
   $table_name = 'volunteer_opportunities';
   $query = "SELECT * FROM $table_name";
   
   // Parse shortcode attributes
   $atts = shortcode_atts([
      'hours' => NULL,
      'type' => NULL,
   ], $atts, 'volunteer');

   // Add conditions to the query based on attributes
   $conditions = [];

   if (!is_null($atts['hours'])) {
      $conditions[] = $wpdb->prepare("hours < %d", $atts['hours']);
   }

   if (!is_null($atts['type'])) {
      $conditions[] = $wpdb->prepare("type = %s", $atts['type']);
   }

   if (!empty($conditions)) {
      $query .= ' WHERE ' . implode(' AND ', $conditions);
   }
   
   $opportunities = $wpdb->get_results($query);

   // Start building the HTML table
   $output = '<table style="width:100%; border-collapse: collapse;">';
   $output .= '<tr>';
   $output .= '<th style="border: 1px solid #ddd; padding: 8px;">ID</th>';
   $output .= '<th style="border: 1px solid #ddd; padding: 8px;">Title</th>';
   $output .= '<th style="border: 1px solid #ddd; padding: 8px;">Description</th>';
   $output .= '<th style="border: 1px solid #ddd; padding: 8px;">Type</th>';
   $output .= '<th style="border: 1px solid #ddd; padding: 8px;">Organization</th>';
   $output .= '<th style="border: 1px solid #ddd; padding: 8px;">E-mail</th>';
   $output .= '<th style="border: 1px solid #ddd; padding: 8px;">Location</th>';
   $output .= '<th style="border: 1px solid #ddd; padding: 8px;">Hours</th>';
   $output .= '<th style="border: 1px solid #ddd; padding: 8px;">Skills Required</th>';
   $output .= '</tr>';
   
   foreach ($opportunities as $opportunity) {
      $row_style = '';
      if (is_null($atts['hours']) && is_null($atts['type'])) {
         if ($opportunity->hours < 10) {
            $row_style = 'background-color: #d4edda;';
         } elseif ($opportunity->hours >= 10 && $opportunity->hours <= 100) {
            $row_style = 'background-color: #fff3cd;';
         } elseif ($opportunity->hours > 100) {
            $row_style = 'background-color: #f8d7da;';
         }
      }

      $output .= '<tr style="' . $row_style . '">';
      $output .= '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($opportunity->id) . '</td>';
      $output .= '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($opportunity->position) . '</td>';
      $output .= '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($opportunity->description) . '</td>';
      $output .= '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($opportunity->type) . '</td>';
      $output .= '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($opportunity->organization) . '</td>';
      $output .= '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($opportunity->email) . '</td>';
      $output .= '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($opportunity->location) . '</td>';
      $output .= '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($opportunity->hours) . '</td>';
      $output .= '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($opportunity->skills_required) . '</td>';
      $output .= '</tr>';
   }
      
   $output .= '</table>';
   
   return $output;
   
}


// Add Shortcode
add_shortcode("volunteer-opportunity", "volunteer_shortcode");

// Add Admin Page
add_action("admin_menu", "admin_page");


// Register Custom Hooks
register_activation_hook(__FILE__, "plugin_activate");
register_deactivation_hook(__FILE__, "plugin_deactivate");
register_uninstall_hook(__FILE__, "plugin_uninstall");

?>