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

}

function plugin_deactivate()
{

}


function plugin_uninstall()
{

}




// Register Custom Hooks
register_activation_hook(__FILE__, "plugin_activate");
register_deactivation_hook(__FILE__, "plugin_deactivate");
register_uninstall_hook(__FILE__, "plugin_uninstall");


?>