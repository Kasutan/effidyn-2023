<?php
/*Template Name: Page simple*/






// Bannière avec titre, sur-titre et décors
add_action( 'tha_entry_top', 'kasutan_page_banniere', 7 );


// Build the page
require get_template_directory() . '/index.php';




