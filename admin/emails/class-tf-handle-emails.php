<?php 
/**
 * Tourfic Handle Emails Class for admin/vendors/customers
 * @author: Abu Hena
 * @package: TourFic
 * @since: 2.3.0
 * 
 */
class TF_Handle_Emails{

    protected static $tf_email_settings;
    /**
     * Constructor
     */
    protected static $dd;
    public function __construct(){
        self::$tf_email_settings = tfopt('email-settings')  ? tfopt('email-settings') : array(); 
        //send mail after new woocommerce order thankyou page
        add_action( 'woocommerce_thankyou', array( $this, 'send_email' ), 10, 1 );
        //add_action( 'woocommerce_order_status_completed', array( $this, 'send_email' ), 10, 1 );

    }
    
    
     public static function get_styles(){
        $template_style = '<style type="text/css">
        body {
            font-family: Work Sans, sans-serif;
            font-size: 16px;
            color: #9C9C9C;
        }
 
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }
 
        .content {
            padding: 25px 50px;
        }
 
        h3.greeting {
            margin: 0;
            padding: 0;
        }
 
        .header {
            background-color: #0209AF;
            color: #fff;
            padding: 20px;
        }
 
        .header .brand-logo {
            width: 100px;
        }
 
        .header .heading {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            flex-direction: column;
        }
 
        .heading h1 {
            text-align: center;
            font-size: 32px;
            line-height: 40px;
            font-weight: 400;
            letter-spacing: 2px;
        }
 
        .heading h2 {
            font-size: 16px;
            font-weight: 500;
            line-height: 20px;
        }
 
        .order-table th {
            font-weight: bold;
            line-height: 20px;
            color: #0209AF;
            text-align: center;
            padding: 15px 0;
        }
 
        .order-table tr td {
            padding: 15px 0;
            text-align: center;
        }
 
        .order-table th:last-child,
        .order-table tr td:last-child {
            text-align: right;
        }
 
        .order-table th:first-child,
        .order-table tr td:first-child {
            text-align: left;
        }
 
        .order-table tbody tr:last-child {
            border-bottom: 2px solid #D9D9D9;
        }
 
        tr.total-amount {
            border-top: 2px solid #D9D9D9;
            font-weight: bold;
        }
 
        .customer-details {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin: 24px 0;
        }
 
        .customer-details h3 {
            font-size: 16px;
            font-weight: bold;
            color: #0209AF;
        }
 
        .customer-details .billing-info p:first-child {
            font-weight: bold;
        }
 
        .customer-details div {
            background: #e0f0fc6e;
            padding: 25px;
        }
 
        .notice {
            background: #e0f0fc6e;
            padding: 20px;
        }
 
        .footer {
            padding: 20px 50px;
        }
 
        .footer p {
            margin: 5px 0;
        }
 
        .social a {
            margin: 10px 0;
        }
 
        .social {
            margin-top: 15px;
            padding-right: 10px;
        }</style>';

        return $template_style;
    }
    /**
     * Get email template
     * @param string $template_type
     * @param string $template
     * @param string $sendto
     * @since 2.3.0
     * 
     */
    public static function get_email_template( $template_type = 'order', $template = '', $sendto = 'admin' ){

        $email_settings = self::$tf_email_settings;
        $templates = array(
            'order' => array(
                'admin'    => !empty( $email_settings['admin_booking_email_template'] ) ? $email_settings['admin_booking_email_template'] : '',
                'customer' => !empty( $email_settings['customer_booking_email_template'] ) ? $email_settings['customer_booking_email_template'] : '',
            ),
            'order_confirmation' => array(
                'admin'    => !empty( $email_settings['admin_confirmation_email_template'] ) ? $email_settings['admin_confirmation_email_template'] : '',
                'customer' => !empty( $email_settings['customer_confirmation_email_template'] ) ? $email_settings['customer_confirmation_email_template'] : '',
            )
		);

		$content = ! empty( $templates[ $template_type ][ $sendto ] ) ? $templates[ $template_type ][ $sendto ] : '';

		if ( ! empty( $content ) ) {
			return $content;
		}
		if ( empty( $template ) ) {
			switch ( $template_type ) {
				case 'order':
					$template = 'booking/notification.php';
					break;
				case 'order_confirmation':
					$template = 'booking/confirmation.php';
					break;
				default:
					$template = 'booking/notification.php';
					break;
			}
		}

        $args = array(
            'send_to' => $sendto,
            'strings' => self::get_emails_strings( $template_type, $sendto ),
        );

        //include email template
        $template_path = TF_EMAIL_TEMPLATES_PATH . $template;
        ob_start();
        include $template_path;
        $template = ob_get_clean();
        return $template;

    }

    //method get strings
    public static function get_emails_strings( $template_type, $sendto = 'admin', $string = 'heading'  ){
        $strings = apply_filters(
            'tf_email_strings',
            array(
                'order' => array(
                    'admin' => array(
						'heading'         => __( 'New Order Received','tourfic' ),
						'greeting'        => __( 'Dear Admin,', 'tourfic' ),
						'greeting_byline' => __( 'A new booking has been made on your website. Booking details are listed below.', 'tourfic' ),
                    ),
                    'vendor' => array(
                        'heading'         => __( 'New Order Received','tourfic' ),
                        'greeting'        => __( 'Dear Vendor,', 'tourfic' ),
                        'greeting_byline' => __( 'A new booking has been made on your website. Booking details are listed below.', 'tourfic' ),
                    ),
                    'customer' => array(
                        'heading'         => __( 'Booking Confirmation','tourfic' ),
                        'greeting'        => __( 'Dear Customer,', 'tourfic' ),
                        'greeting_byline' => __( 'A new booking has been made on your website. Booking details are listed below.', 'tourfic' ),

                    ),
                ),
                'order_confirmation' => array(
                    'admin'    => array(
						'heading'         => __( 'A Payment has been received for {booking_id}', 'tourfic' ),
						'greeting'        => __( 'Dear Admin,', 'tourfic' ),
						'greeting_byline' => __( 'A payment has been received for {booking_id}. The payment details are listed below.', 'tourfic' ),
					),
                    'vendor'   => array(
                        'heading'         => __( 'A Payment has been received for {booking_id}', 'tourfic' ),
                        'greeting'        => __( 'Dear Vendor,', 'tourfic' ),
                        'greeting_byline' => __( 'A payment has been received for {booking_id}. The payment details are listed below.', 'tourfic' ),
                    ),
					'customer' => array(
						'heading'         => __( 'Your booking has been confirmed.', 'tourfic' ),
						'greeting'        => __( 'Dear {name},', 'tourfic' ),
						'greeting_byline' => __( 'Your booking has been confirmed. Your booking and payment information is listed below.', 'tourfic' ),
					),
                )
        
            ), 
        );
        if( isset( $strings[$template_type][$sendto][$string] ) ){
            return $strings[$template_type][$sendto][$string];
        }
        return false;


    }

    /**
     * Send Email
     * @param string $to
     * @param string $subject
     * @param string $message
     * @return void
     */
    public function send_email( $order_id ){
       
        $email_settings = self::$tf_email_settings;
        $order_email_heading = !empty( $email_settings['order_email_heading'] ) ? $email_settings['order_email_heading'] : '';
        //get order details
        $order = wc_get_order( $order_id );
        $order_data = $order->get_data();
        $order_items = $order->get_items();
        $order_subtotal = $order->get_subtotal();
        $order_total = $order->get_total();        
        $order_billing_email = $order->get_billing_email();
        $order_billing_phone = $order->get_billing_phone();
        $order_payment_method = $order->get_payment_method();
        $payment_method_title = $order->get_payment_method_title();
        $order_shipping_method = $order->get_shipping_method();
        $order_currency = $order->get_currency();
        $order_status = $order->get_status();
        $order_date_created = $order->get_date_created();
        $order_items_data = array();
        //payment method
        $get_post_edit_link = get_edit_post_link( $order_id );
        //get order items details as table format so we can use it in email template
        foreach( $order_items as $item_id => $item_data ){
            $item_name = $item_data->get_name();
            $item_quantity = $item_data->get_quantity();
            $item_total = $item_data->get_total();
            $item_subtotal = $item_data->get_subtotal();
            $item_subtotal_tax = $item_data->get_subtotal_tax();
            $item_total_tax = $item_data->get_total_tax();
            $item_taxes = $item_data->get_taxes();
            $item_meta_data = $item_data->get_meta_data();            
           
            $item_meta_data_array = array();
            foreach( $item_meta_data as $meta_data ){
                $item_meta_data_array[] = array(
                    'key'   => $meta_data->key,
                    'value' => $meta_data->value,
                );
            }
            $order_items_data[] = array(
                'item_name'         => $item_name,
                'item_quantity'     => $item_quantity,
                'item_total'        => $item_total,
                'item_subtotal'     => $item_subtotal,
                'item_subtotal_tax' => $item_subtotal_tax,
                'item_total_tax'    => $item_total_tax,
                'item_taxes'        => $item_taxes,
                'item_meta_data'    => $item_meta_data_array,
            );
           
        }
        //authors email array
        $vendors_email = array();

        $booking_details = '<table width="100%" style="max-width: 600px;border-collapse: collapse; color: #5A5A5A;"><thead><tr><th>Item Name</th><th>Quantity</th><th>Price</th></tr></thead><tbody>';
        foreach( $order_items_data as $item ){
            $booking_details .= '<tr>';
            $booking_details .= '<td style="padding: 15px 0;text-align: center;">'.$item['item_name'];
            //item meta data except _order_type,_post_author,_tour_id php loop
            foreach( $item['item_meta_data'] as $meta_data ){
                if( $meta_data['key'] != '_order_type' && $meta_data['key'] != '_post_author' && $meta_data['key'] != '_tour_id' ){
                    $booking_details .= '<br><strong>'.$meta_data['key'].'</strong>: '.$meta_data['value'];
                }
                //identify vendor details
                if( $meta_data['key'] == '_post_author' ){
                    $author_id = $meta_data['value'];
                    $author_name = get_the_author_meta( 'display_name', $author_id );
                    $author_email = get_the_author_meta( 'user_email', $author_id );
                    //get user role
                    $user_data = get_userdata( $author_id );
                    $user_roles = $user_data->roles;
                    if( in_array( 'tf_vendor', $user_roles ) ){
                        array_push( $vendors_email, $author_email );
                    }                    
                }
            }            
           
            $booking_details .= '</td>';
            $booking_details .= '<td>'.$item['item_quantity'].'</td>';
            $booking_details .= '<td>'.wc_price($item['item_subtotal']).'</td>';
            $booking_details .= '</tr>';

        } 
        $booking_details .= '</tbody>';
        $booking_details .= '<tfoot><tr><th colspan="2" align="left">Subtotal</th>';
        $booking_details .= '<td>'.wc_price($order_subtotal).'</td></tr>';
        //payment method
        $booking_details .= '<tr><th colspan="2" align="left">Payment Method</th>';
        $booking_details .= '<td>'.$payment_method_title.'</td></tr>';
        //total
        $booking_details .= '<tr><th colspan="2" align="left">Total</th>';
        $booking_details .= '<td>'.wc_price($order_total).'</td></tr>';
        $booking_details .= '</tfoot>';
       
        $booking_details .= '</table>';
        //booking details end
      
        //admin email settings
        $brand_logo = !empty($email_settings['brand_logo'] ) ? $email_settings['brand_logo'] : '';
        $send_notifcation             = !empty($email_settings['send_notification'] ) ? $email_settings['send_notification'] : 'no';
        $sale_notification_email      = !empty($email_settings['sale_notification_email'] ) ? $email_settings['sale_notification_email'] : get_bloginfo('admin_email');
        $admin_email_disable          = !empty($email_settings['admin_email_disable'] ) ? $email_settings['admin_email_disable'] : false;
        $admin_email_subject          = !empty($email_settings['admin_email_subject'] ) ? $email_settings['admin_email_subject'] . "#" . $order_id: '';
        $email_from_name              = !empty($email_settings['email_from_name'] ) ? $email_settings['email_from_name'] : get_bloginfo('name');
        $email_from_email             = !empty($email_settings['email_from_email'] ) ? $email_settings['email_from_email'] : get_bloginfo('admin_email');
        $email_content_type           = !empty($email_settings['email_content_type'] ) ? $email_settings['email_content_type'] : 'html';
        $email_body_open              = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />'.self::get_styles().'</head><body><div class="container"><div class="header"><div class="brand-logo">';
        $email_body_open .= '<img src="'.wp_get_attachment_url( $brand_logo ).'" alt="logo" />';
        $email_body_open .= '<div class="heading" style="display:flex;flex-wrap:wrap;margin:5px;align-items:center;flex-direction:column;">
        <img width="200" src="https://www.w3schools.com/images/w3schools_green.jpg" alt="brand-logo">
        <h1 style="text-align: center; font-size: 32px;line-height: 40px;font-weight: 400;letter-spacing: 2px;">
           '.$order_email_heading.'
        </h1>
        <h2 style="font-size: 16px;font-weight: 500;line-height: 20px;">
             '. __( 'Order number : ','tourfic' ) . '#{booking_id}
        </h2>
    </div>';
        $email_body_open .= '</div></div>';
        $admin_booking_email_template = !empty($email_settings['admin_booking_email_template'] ) ? $email_settings['admin_booking_email_template'] : '';
        //send attachment to mail from settings image field
        
        //all mail tags mapping
        $tf_all_mail_tags = array(
            '{booking_id}'       => $order_id,
            '{booking_details}'  => $booking_details,
            '{fullname}'         => $order_data['billing']['first_name'] . ' ' . $order_data['billing']['last_name'],
            '{user_email}'       => $order_billing_email,
            '{billing_address}'  => $order_data['billing']['address_1'] . ' ' . $order_data['billing']['address_2'],
            '{city}'             => $order_data['billing']['city'],
            '{billing_state}'    => $order_data['billing']['state'],
            '{billing_zip}'      => $order_data['billing']['postcode'],
            '{country}'          => $order_data['billing']['country'],
            '{phone}'            => $order_data['billing']['phone'],
            '{shipping_address}' => $order_data['shipping']['address_1'] . ' ' . $order_data['shipping']['address_2'],
            '{shipping_city}'    => $order_data['shipping']['city'],
            '{shipping_state}'   => $order_data['shipping']['state'],
            '{shipping_zip}'     => $order_data['shipping']['postcode'],
            '{shipping_country}' => $order_data['shipping']['country'],
            '{shipping_phone}'   => $order_data['shipping']['phone'],
            '{payment_method}'   => $payment_method_title,
            '{order_total}'      => wc_price($order_total),
            '{order_subtotal}'   => wc_price($order_subtotal),
            '{order_date}'       => $order_date_created,
            '{order_status}'     => $order_status,
            '{site_name}'        => get_bloginfo('name'),
            '{site_url}'         => get_bloginfo('url'),
            '{site_email}'       => get_bloginfo('admin_email'),
        );

        $admin_booking_email_template = str_replace( array_keys( $tf_all_mail_tags ), array_values( $tf_all_mail_tags ), $admin_booking_email_template );


        
        $email_body_close = '</div></body></html>';
        $admin_email_booking_body_full = $email_body_open . $admin_booking_email_template . $email_body_close;

        //mail headers
        $charset = apply_filters( 'tourfic_mail_charset','Content-Type: text/html; charset=UTF-8') ;
        $headers = $charset . "\r\n";
        $headers.= "MIME-Version: 1.0" . "\r\n";
        $headers.= "From: $email_from_name <$email_from_email>" . "\r\n";
        $headers.= "Reply-To: $email_from_name <$email_from_email>" . "\r\n";
        $headers.= "X-Mailer: PHP/" . phpversion() . "\r\n";

        //check if admin emails disable
        if( isset( $admin_email_disable ) && $admin_email_disable == false ){
            if( !empty( $admin_booking_email_template ) ){
                //send multiple emails to multiple admins
                if( strpos( $sale_notification_email, ',') !== false ){
                    $sale_notification_email = explode(',', $sale_notification_email);
                    $sale_notification_email = str_replace(' ', '', $sale_notification_email);
                    foreach ( $sale_notification_email as $key => $email_address ) {
                        wp_mail( $email_address, $admin_email_subject, $admin_email_booking_body_full, $headers );
                    }            
                }else{
                    //send admin email
                    wp_mail( $sale_notification_email, $admin_email_subject, $admin_email_booking_body_full, $headers );

                }
            }
        }

        //send mail to vendor
       if( ! empty( $send_notifcation ) && $send_notifcation == 'admin_vendor' ){

            $vendor_email_subject = !empty( $email_settings['admin_email_subject'] ) ? $email_settings['admin_email_subject']  : '';
            $vendor_booking_email_template = !empty($email_settings['vendor_booking_email_template'] ) ? $email_settings['vendor_booking_email_template'] : '';
            
            //replace mail tags to actual value
            $vendor_booking_email_template = str_replace( array_keys( $tf_all_mail_tags ), array_values( $tf_all_mail_tags ), $vendor_booking_email_template );

            $vendor_email_booking_body_full = $email_body_open . $vendor_booking_email_template . $email_body_close;
            //send mail in plain text and html conditionally 
            $vendor_email_booking_body_full = wp_kses_post( html_entity_decode( $vendor_email_booking_body_full, 3, 'UTF-8' ) );
            
            //send mail to vendor
            if( ! empty( $vendors_email ) ){
                foreach ( $vendors_email as $key => $vendor_email ) {
                    wp_mail( $vendor_email, $vendor_email_subject, $vendor_email_booking_body_full, $headers );
                }
            }

       }

        //customer email settings
        $customer_email_address =  $order_billing_email;
        $customer_email_subject = !empty( $email_settings['customer_confirm_email_subject'] ) ? $email_settings['customer_email_subject']  : '';
        $customer_confirm_email_template = !empty($email_settings['customer_confirm_email_template'] ) ? $email_settings['customer_confirm_email_template'] : '';
        //send mail to customer 
        if( !empty( $customer_confirm_email_template ) ){
            //replace mail tags to actual value
            $customer_confirm_email_template = str_replace( array_keys( $tf_all_mail_tags ), array_values( $tf_all_mail_tags ), $customer_confirm_email_template );

            $customer_email_body_full = $email_body_open . $customer_confirm_email_template . $email_body_close;
            $customer_email_body_full = wp_kses_post(  $customer_email_body_full );
            wp_mail( $customer_email_address, $customer_email_subject, $customer_email_body_full, $headers );
        }
    }
     //static function which will output the css style to the email template head




   
}
//call the class
new TF_Handle_Emails();