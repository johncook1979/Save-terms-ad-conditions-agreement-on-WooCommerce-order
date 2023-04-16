/**
 * Save the terms and conditions checkout checkbox field as the order meta
 */
add_action( 'woocommerce_checkout_create_order', 'my_terms_field_update_order_meta', 10, 2 );

function my_terms_field_update_order_meta( $order, $data ) {

    // Set the correct values from the terms checkbox
    $value = isset($_POST['terms']) ? '1' : '0'; 
    
    // Save as custom order meta data
    $order->update_meta_data( 'terms', $value );

}




/**
 * Displaying the terms field value in the order edit page
 */
add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_terms_field_display_admin_order_meta', 10, 1 );

function my_terms_field_display_admin_order_meta( $order ){

    // Get the custom meta field from the order
    $accepted_terms = get_post_meta( $order->get_id(), 'terms', true );

    // Set the value to readable text.  Change the text as necessary
    // The values are stored as 1 or 0. 1 for yes and 0 for no. 
	  $accepted_terms = ($accepted_terms == 1) ? 'Accepted' : 'Not accepted';

    // Check that the field is not empty to display the necessary message
    if( ! empty( $accepted_terms ))
        echo '<p><strong>'.__('Accepted terms', 'woocommerce').':</strong> ' . $accepted_terms . '</p>';
}



/**
 * Add a custom field (in an order) to the emails
 *
 * Modified from https://woocommerce.com/document/add-a-custom-field-in-an-order-to-the-emails/
 */
add_filter( 'woocommerce_email_order_meta_fields', 'terms_woocommerce_email_order_meta_fields', 10, 3 );

function terms_woocommerce_email_order_meta_fields( $fields, $sent_to_admin, $order ) {
	
    // Get the order meta for the terms
	  $accepted_terms = get_post_meta( $order->id, 'terms', true );

    // Define the text the customer will read for accepted or not accepted
	  $accepted_terms = ($accepted_terms == 1) ? 'Accepted' : 'Not accepted';
    
    // Only display if the terms field is not empty
    if( ! empty( $accepted_terms )) {
      $fields['meta_key'] = array(
          'label' => __( 'Accepted terms and conditions' ),
          'value' => $accepted_terms,
      );
      return $fields;
    }
}
