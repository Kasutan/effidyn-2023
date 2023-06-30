<?php
/**
 * Page
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/




// Bannière avec titre, sur-titre et décors
add_action( 'tha_entry_top', 'kasutan_page_banniere', 7 );


// Build the page
require get_template_directory() . '/index.php';




