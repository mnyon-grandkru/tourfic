<?php
/**
 * Tourfic Handle Emails Class for admin/vendors/customers
 * @author: Abu Hena
 * @package: TourFic
 * @since: 2.3.0
 *
 */
class TF_Handle_Emails {

    //free email settings
    protected static $tf_email_settings;
    //Pro metabox email settings
    protected static $tf_mb_email_settings;
    //Pro email template settings
    protected static $tf_email_template_settings;
     //authors email array
    private $vendors_email = array();

    /**
     * Constructor
     */
    public function __construct() {
        self::$tf_email_settings = tfopt( 'email-settings' ) ? tfopt( 'email-settings' ) : array();
        self::$tf_email_template_settings = !empty( tfopt( 'email_template_settings' ) ) ? tfopt( 'email_template_settings' ) : array();
        
        
        //send mail if Tourfic pro is active
        //send confirmation mail
        add_action( 'woocommerce_thankyou', array( $this, 'send_email' ), 10, 1 );
        //send pro confirmation mail
        add_action( 'woocommerce_thankyou', array( $this, 'send_confirmation_email_pro' ), 10, 1 );
        //send cancellation mail
        add_action( 'woocommerce_order_status_cancelled', array( $this, 'send_cancellation_email_pro' ), 10, 1 );
    }

    /**
     * email body open markup
     * @param  string $brand_logo
     * @param  string $order_email_heading
     * @param  string $email_heading_bg
     */
    public function email_body_open($brand_logo, $order_email_heading, $email_heading_bg){
        //email body open
        $email_body_open = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1"></head><body><body style="font-family: Work sans, sans-serif; font-size: 16px; color: #9C9C9C; margin: 0; padding: 0;">
           <div style="width: 100%; max-width: 600px; margin: 0 auto;">
               <div style="background-color: ' . esc_attr($email_heading_bg) . '; color: #fff; padding: 20px;">';
        if (!empty($brand_logo)) {
            $email_body_open .= '<div style="text-align:center;width:200px;margin: 0 auto;"><img src="' . esc_url($brand_logo) . '" alt="logo" /></div>';
        }
        $email_body_open .= '<div class="heading" style="text-align: center;">
           <h1 style="font-size: 32px; line-height: 40px; font-weight: 400; letter-spacing: 2px; margin: 20px 0; color: #ffffff;">
           ' . $order_email_heading . '
           </h1>
           <h2 style="font-size:16px;font-weight:500;line-height:20px;color:#ffffff;">
                ' . __('Order number : ', 'tourfic') . '#{booking_id}
           </h2>
       </div>';
        $email_body_open .= '</div>';
        return $email_body_open;
    }

    /**
     * email body close markup
     */
    public function email_body_close(){
        //email body close
        $email_body_close = '</div></body></html>';
        return $email_body_close;
    }


    /**
     * Replace all available mail tags
     * @param  string $template
     * @param  int $order_id
     * @return string
     * @since  2.9.17
     */
    public function replace_mail_tags( $template, $order_id ) {

        $order                  = wc_get_order( $order_id );
        $order_data             = $order->get_data();
        $order_items            = $order->get_items();
        $order_items_data       = array();
        $order_subtotal         = $order->get_subtotal();
        $order_total            = $order->get_total();
        $order_billing_name     = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
        $order_billing_address  = $order->get_billing_address_1() . ' ' . $order->get_billing_address_2();
        $order_billing_email    = $order->get_billing_email();
        $order_billing_phone    = $order->get_billing_phone();
        $order_billing_city     = $order->get_billing_city();
        $order_billing_country  = $order->get_billing_country();
        $order_billing_postcode = $order->get_billing_postcode();
        $payment_method_title   = $order->get_payment_method_title();
        $order_status           = $order->get_status();
        $order_date_created     = $order->get_date_created();
        //payment method
        $order_url = get_edit_post_link( $order_id );
        //get order items details as table format so we can use it in email template
        foreach ( $order_items as $item_id => $item_data ) {
            $item_name         = $item_data->get_name();
            $item_quantity     = $item_data->get_quantity();
            $item_total        = $item_data->get_total();
            $item_subtotal     = $item_data->get_subtotal();
            $item_subtotal_tax = $item_data->get_subtotal_tax();
            $item_total_tax    = $item_data->get_total_tax();
            $item_taxes        = $item_data->get_taxes();
            $item_meta_data    = $item_data->get_meta_data();

            $item_meta_data_array = array();
            foreach ( $item_meta_data as $meta_data ) {
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
       

        $booking_details = '<table width="100%" style="max-width: 600px;border-collapse: collapse; color: #5A5A5A;"><thead><tr><th align="left" style="color:#0209AF">Item Name</th><th align="center" style="color:#0209AF">Quantity</th><th align="right" style="color:#0209AF">Price</th></tr></thead><tbody style="border-bottom: 2px solid #D9D9D9">';
        foreach ( $order_items_data as $item ) {
            $booking_details .= '<tr>';
            $booking_details .= '<td style="padding: 15px 0;text-align: left;padding-top: 15px;padding-bottom: 15px;line-height: 1.7;">' . $item['item_name'];
            //item meta data except _order_type,_post_author,_tour_id php loop
            foreach ( $item['item_meta_data'] as $meta_data ) {
                if ( $meta_data['key'] != '_order_type' && $meta_data['key'] != '_post_author' && $meta_data['key'] != '_tour_id' && $meta_data['key'] != '_post_id' && $meta_data['key'] != '_unique_id' ) {
                    $booking_details .= '<br><strong>' . $meta_data['key'] . '</strong>: ' . $meta_data['value'];
                }
                //identify vendor details
                if ( $meta_data['key'] == '_post_author' ) {
                    $author_id    = $meta_data['value'];
                    $author_name  = get_the_author_meta( 'display_name', $author_id );
                    $author_email = get_the_author_meta( 'user_email', $author_id );
                    //get user role
                    $user_data  = get_userdata( $author_id );
                    $user_roles = $user_data->roles;
                    if ( in_array( 'tf_vendor', $user_roles ) ) {
                        //add vendor email to array
                        array_push( $this->vendors_email, $author_email );
                    }
                }
            }

            $booking_details .= '</td>';
            $booking_details .= '<td align="center">' . $item['item_quantity'] . '</td>';
            $booking_details .= '<td align="right">' . wc_price( $item['item_subtotal'] ) . '</td>';
            $booking_details .= '</tr>';

        }
        $booking_details .= '</tbody>';
        $booking_details .= '<tfoot><tr><th colspan="2" align="left" style="padding-bottom:10px;padding-top:10px;">Subtotal</th>';
        $booking_details .= '<td align="right">' . wc_price( $order_subtotal ) . '</td></tr>';
        //payment method
        $booking_details .= '<tr><th colspan="2" align="left" style="padding-bottom:10px">Payment Method</th>';
        $booking_details .= '<td align="right">' . $payment_method_title . '</td></tr>';
        //total
        $booking_details .= '<tr><th colspan="2" align="left" style="padding-bottom:10px">Total</th>';
        $booking_details .= '<td align="right">' . wc_price( $order_total ) . '</td></tr>';
        $booking_details .= '</tfoot>';

        $booking_details .= '</table>';
        //booking details end

        //customer details
        $customer_details = '<table style="max-width: 600px;border-collapse: collapse; color: #5A5A5A;"><tbody><tr><td style="padding: 15px 0;text-align: left;">';
        $customer_details .= '<strong>Customer Name:</strong> ' . $order_billing_name . '<br>';
        $customer_details .= '<strong>Customer Address:</strong> ' . $order_billing_address . '<br>';
        $customer_details .= '<strong>Customer Email:</strong> ' . $order_billing_email . '<br>';
        $customer_details .= '<strong>Customer Phone:</strong> ' . $order_billing_phone . '<br>';
        $customer_details .= '<strong>Customer City:</strong> ' . $order_billing_city . '<br>';
        $customer_details .= '<strong>Customer Country:</strong> ' . $order_billing_country . '<br>';
        $customer_details .= '<strong>Customer Postcode:</strong> ' . $order_billing_postcode . '<br>';
        $customer_details .= '</td></tr></tbody></table>';
        //customer details end

        $replacements = array(
            '{booking_id}'       => $order_id,
            '{booking_url}'      => $order_url,
            '{booking_details}'  => $booking_details,
            '{fullname}'         => $order_data['billing']['first_name'] . ' ' . $order_data['billing']['last_name'],
            '{user_email}'       => $order_data['billing']['email'],
            '{billing_address}'  => $order_data['billing']['address_1'] . ' ' . $order_data['billing']['address_2'],
            '{city}'             => $order_data['billing']['city'],
            '{billing_state}'    => $order_data['billing']['state'],
            '{billing_zip}'      => $order_data['billing']['postcode'],
            '{country}'          => $order_data['billing']['country'],
            '{phone}'            => $order_data['billing']['phone'],
            '{payment_method}'   => $order_data['payment_method_title'],
            '{order_total}'      => wc_price($order_total),
            '{order_subtotal}'   => wc_price($order_subtotal),
            '{order_date}'       => $order_date_created,
            '{order_status}'     => $order_status,
            '{site_name}'        => get_bloginfo( 'name' ),
            '{site_url}'         => get_bloginfo( 'url' ),
        );

        $tags = array_keys($replacements);
        $values = array_values($replacements);

        return str_replace( $tags, $values, $template );
    }

    /**
     * Get email template
     * @param string $template_type
     * @param string $template
     * @param string $sendto
     * @since 2.3.0
     *
     */
    public static function get_email_template( $template_type = 'order', $template = '', $sendto = 'admin' ) {
        $email_settings = self::$tf_email_settings;
        $templates      = array(
            'order'              => array(
                'admin'    => !empty( $email_settings['admin_booking_email_template'] ) ? $email_settings['admin_booking_email_template'] : '',
                'customer' => !empty( $email_settings['customer_booking_email_template'] ) ? $email_settings['customer_booking_email_template'] : '',
            ),
            'order_confirmation' => array(
                'admin'    => !empty( $email_settings['admin_confirmation_email_template'] ) ? $email_settings['admin_confirmation_email_template'] : '',
                'customer' => !empty( $email_settings['customer_confirmation_email_template'] ) ? $email_settings['customer_confirmation_email_template'] : '',
            ),
            'cancellation'      => array(
                'admin'    => !empty( $email_settings['admin_cancellation_email_template'] ) ? $email_settings['admin_cancellation_email_template'] : '',
                'customer' => !empty( $email_settings['customer_cancellation_email_template'] ) ? $email_settings['customer_cancellation_email_template'] : '',
            ),
        );

        $content = !empty( $templates[$template_type][$sendto] ) ? $templates[$template_type][$sendto] : '';

        if ( !empty( $content ) ) {
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
            case 'cancellation':
                $template = 'booking/cancellation.php';
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
    public static function get_emails_strings( $template_type, $sendto = 'admin', $string = 'heading' ) {
        $strings = apply_filters(
            'tf_email_strings',
            array(
                'order'              => array(
                    'admin'    => array(
                        'heading'         => __( 'New Order Received', 'tourfic' ),
                        'greeting'        => __( 'Dear Admin,', 'tourfic' ),
                        'greeting_byline' => __( 'A new booking has been made on your website. Booking details are listed below.', 'tourfic' ),
                    ),
                    'vendor'   => array(
                        'heading'         => __( 'New Order Received', 'tourfic' ),
                        'greeting'        => __( 'Dear Vendor,', 'tourfic' ),
                        'greeting_byline' => __( 'A new booking has been made on your website. Booking details are listed below.', 'tourfic' ),
                    ),
                    'customer' => array(
                        'heading'         => __( 'Booking Confirmation', 'tourfic' ),
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
                        'greeting'        => __( 'Dear {fullname},', 'tourfic' ),
                        'greeting_byline' => __( 'A payment has been received for {booking_id}. The payment details are listed below.', 'tourfic' ),
                    ),
                    'customer' => array(
                        'heading'         => __( 'Your booking has been confirmed.', 'tourfic' ),
                        'greeting'        => __( 'Dear {fullname},', 'tourfic' ),
                        'greeting_byline' => __( 'Your booking has been confirmed. Your booking and payment information is listed below.', 'tourfic' ),
                    ),
                ),
                'cancellation'  => array(
                    'admin'    => array(
                        'heading'         => __( 'A booking has been cancelled', 'tourfic' ),
                        'greeting'        => __( 'Dear Admin,', 'tourfic' ),
                        'greeting_byline' => __( 'A booking has been cancelled. The booking details are listed below.', 'tourfic' ),
                    ),
                    'vendor'   => array(
                        'heading'         => __( 'A booking has been cancelled', 'tourfic' ),
                        'greeting'        => __( 'Dear Vendor,', 'tourfic' ),
                        'greeting_byline' => __( 'A booking has been cancelled. The booking details are listed below.', 'tourfic' ),
                    ),
                    'customer' => array(
                        'heading'         => __( 'Your booking has been cancelled.', 'tourfic' ),
                        'greeting'        => __( 'Dear {fullname},', 'tourfic' ),
                        'greeting_byline' => __( 'Your booking has been cancelled. Your booking and payment information is listed below.', 'tourfic' ),
                    ),
                ),

            ),
        );
        if ( isset( $strings[$template_type][$sendto][$string] ) ) {
            return $strings[$template_type][$sendto][$string];
        }
        return false;

    }
    public static function tf_send_attachment() {
        $email_settings = self::$tf_email_settings;
        $brand_logo     = !empty( $email_settings['brand_logo'] ) ? $email_settings['brand_logo'] : '';
        if ( !empty( $brand_logo ) ) {
            $logo_id = attachment_url_to_postid( $brand_logo );

            $brand_logo_path = get_attached_file( $logo_id ); //phpmailer will load this file
            $uid             = 'logo-uid'; //will map it to this UID
            global $phpmailer;
            $phpmailer->AddEmbeddedImage( $brand_logo_path, $uid );
        } else {
            return;
        }

    }
   
    /**
     * Send Email
     * @param string $to
     * @param string $subject
     * @param string $message
     * @return void
     */
    public function send_email( $order_id ) {
        if( ! function_exists( 'is_tf_pro' ) && is_tf_pro() == false ):
            //get order details
            $order = wc_get_order( $order_id );        
            $order_billing_email     = $order->get_billing_email();

            $email_settings      = self::$tf_email_settings;
            $order_email_heading = !empty( $email_settings['order_email_heading'] ) ? $email_settings['order_email_heading'] : '';

            $brand_logo       = !empty( $email_settings['brand_logo'] ) ? $email_settings['brand_logo'] : '';
            $email_heading_bg = !empty( $email_settings['email_heading_bg'] ) ? $email_settings['email_heading_bg']['bg_color'] : '#0209AF';

            $send_notifcation        = !empty( $email_settings['send_notification'] ) ? $email_settings['send_notification'] : '';
            $sale_notification_email = !empty( $email_settings['sale_notification_email'] ) ? $email_settings['sale_notification_email'] : get_bloginfo( 'admin_email' );
            $admin_email_disable     = !empty( $email_settings['admin_email_disable'] ) ? $email_settings['admin_email_disable'] : false;
            $admin_email_subject     = !empty( $email_settings['admin_email_subject'] ) ? $email_settings['admin_email_subject'] . " # " . $order_id : '';
            $email_from_name         = !empty( $email_settings['email_from_name'] ) ? $email_settings['email_from_name'] : get_bloginfo( 'name' );
            $email_from_email        = !empty( $email_settings['email_from_email'] ) ? $email_settings['email_from_email'] : get_bloginfo( 'admin_email' );
            $email_content_type      = !empty( $email_settings['email_content_type'] ) ? $email_settings['email_content_type'] : 'text/html';

            //mail headers
            $charset = apply_filters( 'tourfic_mail_charset', 'Content-Type: text/html; charset=UTF-8' );
            $headers = $charset . "\r\n";
            $headers .= "MIME-Version: 1.0" . "\r\n";
            $headers .= "From: $email_from_name <$email_from_email>" . "\r\n";
            $headers .= "Reply-To: $email_from_name <$email_from_email>" . "\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

            //email body started
            $email_body_open = $this->email_body_open( $email_heading_bg, $brand_logo, $order_email_heading );

            $email_body_open               = str_replace( '{booking_id}', $order_id, $email_body_open );
            $admin_booking_email_template  = !empty( $email_settings['admin_booking_email_template'] ) ? $email_settings['admin_booking_email_template'] : '';
            $vendor_booking_email_template = !empty( $email_settings['vendor_booking_email_template'] ) ? $email_settings['vendor_booking_email_template'] : '';

            //replace mail tags
            $admin_booking_email_template = $this->replace_mail_tags( $admin_booking_email_template , $order_id );
            //email body ended
            $email_body_close  = $this->email_body_close();       
        
            $admin_email_booking_body_full = $email_body_open . $admin_booking_email_template . $email_body_close;
            //decode entity
            if ( $email_content_type == 'text/plain' ) {
                //$admin_email_booking_body_full = html_entity_decode( $admin_email_booking_body_full, '3' , 'UTF-8' );
                $admin_email_booking_body_full = wp_strip_all_tags( $admin_email_booking_body_full );
            } else {
                $admin_email_booking_body_full = wp_kses_post( html_entity_decode( $admin_email_booking_body_full, '3', 'UTF-8' ) );
            }      

            //check if admin emails disable
            if ( isset( $admin_email_disable ) && $admin_email_disable == false ) {
                if ( !empty( $admin_booking_email_template ) ) {
                    //send multiple emails to multiple admins
                    if ( strpos( $sale_notification_email, ',' ) !== false ) {
                        $sale_notification_email = explode( ',', $sale_notification_email );
                        $sale_notification_email = str_replace( ' ', '', $sale_notification_email );
                        foreach ( $sale_notification_email as $key => $email_address ) {
                            wp_mail( $email_address, $admin_email_subject, $admin_email_booking_body_full, $headers );
                        }
                    } else {
                        //send admin email
                        wp_mail( $sale_notification_email, $admin_email_subject, $admin_email_booking_body_full, $headers );

                    }
                } else {
                    //send static default mail
                    $default_mail = '<p>' . __( 'Dear Admin', 'tourfic' ) . '</p></br>';
                    $default_mail .= '<p>' . __( 'You have received a new booking. The details are as follows:', 'tourfic' ) . '</p></br>';
                    $default_mail .= __( '{booking_details}', 'tourfic' ) . '</br>';
                    $default_mail .= __( '<strong>Customer details</strong>', 'tourfic' ) . '</br>';
                    $default_mail .= __( '{customer_details}', 'tourfic' ) . '</br>';
                    $default_mail .= __( '<p>Thank you</p>', 'tourfic' );
                    $default_mail .= __( 'Regards', 'tourfic' ) . '</br>';
                    $default_mail .= __( '{site_name}', 'tourfic' ) . '</br>';

                    $default_mail = $this->replace_mail_tags( $default_mail , $order_id );

                    wp_mail( $sale_notification_email, $admin_email_subject, $default_mail, $headers );

                }
            }

            //send mail to vendor
            if ( !empty( $send_notifcation ) && $send_notifcation == 'admin_vendor' ) {

                $vendor_email_subject          = !empty( $email_settings['admin_email_subject'] ) ? $email_settings['admin_email_subject'] : '';
                $vendor_from_name              = !empty( $email_settings['vendor_from_name'] ) ? $email_settings['vendor_from_name'] : '';
                $vendor_from_email             = !empty( $email_settings['vendor_from_email'] ) ? $email_settings['vendor_from_email'] : '';
                $vendor_booking_email_template = !empty( $email_settings['vendor_booking_email_template'] ) ? $email_settings['vendor_booking_email_template'] : '';

                //replace mail tags to actual value
                $vendor_booking_email_template  = $this->replace_mail_tags( $vendor_booking_email_template , $order_id );
                $vendor_email_booking_body_full = $email_body_open . $vendor_booking_email_template . $email_body_close;
                //send mail in plain text and html conditionally
                if ( $email_content_type == 'text/plain' ) {
                    $vendor_email_booking_body_full = wp_strip_all_tags( $vendor_email_booking_body_full );
                } else {
                    $vendor_email_booking_body_full = wp_kses_post( $vendor_email_booking_body_full );
                }
                if ( !empty( $vendor_booking_email_template ) ) {
                    //send mail to vendor
                    if ( !empty( $vendors_email ) ) {
                        foreach ( $vendors_email as $key => $vendor_email ) {
                            wp_mail( $vendor_email, $vendor_email_subject, $vendor_email_booking_body_full, $headers );
                        }
                    }
                } else {
                    //send default mail
                    $default_mail = '<p>' . __( 'Dear Admin', 'tourfic' ) . '</p></br>';
                    $default_mail .= '<p>' . __( 'You have received a new booking. The details are as follows:', 'tourfic' ) . '</p></br>';
                    $default_mail .= __( '{booking_details}', 'tourfic' ) . '</br>';
                    $default_mail .= __( '<strong>Customer details</strong>', 'tourfic' ) . '</br>';
                    $default_mail .= __( '{customer_details}', 'tourfic' ) . '</br>';
                    $default_mail .= __( 'Thank you', 'tourfic' ) . '</br>';
                    $default_mail .= __( 'Regards', 'tourfic' ) . '</br>';
                    $default_mail .= __( '{site_name}', 'tourfic' ) . '</br>';

                    $default_mail = str_replace( '{customer_details}', $customer_details, $default_mail );
                    $default_mail = str_replace( '{booking_details}', $booking_details, $default_mail );
                    $default_mail = str_replace( '{site_name}', get_bloginfo( 'name' ), $default_mail );

                    if ( !empty( $vendors_email ) ) {
                        foreach ( $vendors_email as $key => $vendor_email ) {
                            wp_mail( $vendor_email, $vendor_email_subject, $default_mail, $headers );
                        }
                    }
                }

            }
            //customer email settings
            $customer_email_address          = $order_billing_email;
            $disable_customer_email          = !empty( $email_settings['customer_email_disable'] ) ? $email_settings['customer_email_disable'] : false;
            $customer_email_subject          = !empty( $email_settings['customer_confirm_email_subject'] ) ? $email_settings['customer_confirm_email_subject'] : '';
            $customer_email_subject          = str_replace( '{booking_id}', $order_id, $customer_email_subject );
            $customer_from_name              = !empty( $email_settings['customer_from_name'] ) ? $email_settings['customer_from_name'] : '';
            $customer_from_email             = !empty( $email_settings['customer_from_email'] ) ? $email_settings['customer_from_email'] : '';
            $customer_confirm_email_template = !empty( $email_settings['customer_confirm_email_template'] ) ? $email_settings['customer_confirm_email_template'] : '';
            $headers .= "From: {$customer_from_name} <{$customer_from_email}>" . "\r\n";
            //send mail to customer
            if ( $disable_customer_email == false ) {
                if ( !empty( $customer_confirm_email_template ) ) {
                    //replace mail tags to actual value
                    $customer_confirm_email_template = $this->replace_mail_tags( $customer_confirm_email_template , $order_id );

                    $customer_email_body_full = $email_body_open . $customer_confirm_email_template . $email_body_close;
                    //send mail in plain text and html conditionally
                    if ( $email_content_type == 'text/plain' ) {
                        $customer_email_body_full = wp_strip_all_tags( $customer_email_body_full );
                    } else {
                        $customer_email_body_full = wp_kses_post( $customer_email_body_full );
                    }
                    wp_mail( $customer_email_address, $customer_email_subject, $customer_email_body_full, $headers );
                } else {
                    //send default mail
                    $default_mail = '<p>' . __( 'Dear', 'tourfic' ) . ' {fullname}</p></br>';
                    $default_mail .= '<p>' . __( 'Thank you for your booking. The details are as follows:', 'tourfic' ) . '</p></br>';
                    $default_mail .= __( '{booking_details}', 'tourfic' ) . '</br>';
                    $default_mail .= __( '<strong>Shipping Details</strong>', 'tourfic' ) . '</br>';
                    $default_mail .= __( '{customer_details}', 'tourfic' ) . '</br>';
                    $default_mail .= __( 'Thank you', 'tourfic' ) . '</br>';
                    $default_mail .= __( 'Regards', 'tourfic' ) . '</br>';
                    $default_mail .= __( '{site_name}', 'tourfic' ) . '</br>';

                    $default_mail = $this->replace_mail_tags( $default_mail , $order_id );

                    wp_mail( $customer_email_address, $customer_email_subject, $default_mail, $headers );
                }
            }
        endif;
    }

    /**
     * Send email when order status is confirmed
     * @param  [int] $order_id [pass the order id]
     *
     */
    public function send_confirmation_email_pro( $order_id ){
        if( function_exists( 'is_tf_pro' ) && is_tf_pro() ):
            //get order details
            $order = wc_get_order( $order_id );
            //get customer email
            $order_billing_email    = $order->get_billing_email();

            //email body ended
            $email_template_settings           = $this::$tf_email_template_settings;
            $enable_admin_conf_email           = !empty( $email_template_settings['enable_admin_conf_email'] ) ? $email_template_settings['enable_admin_conf_email'] : '';
            $enable_vendor_conf_email          = !empty( $email_template_settings['enable_vendor_conf_email'] ) ? $email_template_settings['enable_vendor_conf_email'] : '';
            $enable_customer_conf_email        = !empty( $email_template_settings['enable_customer_conf_email'] ) ? $email_template_settings['enable_customer_conf_email'] : '';
            $admin_confirmation_template_id    = !empty( $email_template_settings['admin_confirmation_email_template'] ) ? $email_template_settings['admin_confirmation_email_template'] : '';
            $vendor_confirmation_template_id   = !empty( $email_template_settings['vendor_confirmation_email_template'] ) ? $email_template_settings['vendor_confirmation_email_template'] : '';
            $customer_confirmation_template_id = !empty( $email_template_settings['customer_confirmation_email_template'] ) ? $email_template_settings['customer_confirmation_email_template'] : '';
        

            if( ! empty ( $enable_admin_conf_email ) && $enable_admin_conf_email == 1 ){
                //email settings metabox value
                if( ! empty ( $admin_confirmation_template_id ) ){

                    //get the mail template content   
                    $admin_confirmation_email_template   = get_post( $admin_confirmation_template_id );
                    $admin_confirmation_template_content = !empty( $admin_confirmation_email_template->post_content ) ? $admin_confirmation_email_template->post_content : ' ';
                    $admin_confirmation_template_content = $this->replace_mail_tags( $admin_confirmation_template_content, $order_id );
                    
                    $meta                    = get_post_meta( $admin_confirmation_template_id, 'tf_email_templates_metabox', true );
                    $brand_logo              = ! empty( $meta['brand_logo'] ) ? $meta['brand_logo'] : '';
                    $sale_notification_email = ! empty( $meta['sale_notification_email'] ) ? $meta['sale_notification_email'] : '';
                    $email_subject           = ! empty( $meta['email_subject'] ) ? $meta['email_subject'] : '';
                    $email_from_name         = ! empty( $meta['email_from_name'] ) ? $meta['email_from_name'] : '';
                    $email_from_email        = ! empty( $meta['email_from_email'] ) ? $meta['email_from_email'] : '';
                    $order_email_heading     = ! empty( $meta['order_email_heading'] ) ? $meta['order_email_heading'] : '';
                    $email_header_bg         = ! empty( $meta['email_header_bg'] ) ? unserialize($meta['email_header_bg']) : array();
                    $email_header_bg         = ! empty( $email_header_bg['bg_color'] ) ? $email_header_bg['bg_color'] : '';
                    //mail headers
                    $charset  = apply_filters( 'tourfic_mail_charset', 'Content-Type: text/html; charset=UTF-8' );
                    $headers  = $charset . "\r\n";
                    $headers .= "MIME-Version: 1.0" . "\r\n";
                    $headers .= "From: $email_from_name <$email_from_email>" . "\r\n";
                    $headers .= "Reply-To: $email_from_name <$email_from_email>" . "\r\n";
                    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
                    //email body open
                    $email_body_open                     = $this->email_body_open( $brand_logo, $order_email_heading, $email_header_bg);
                    $email_body_open                     = str_replace( '{booking_id}', $order_id, $email_body_open );
                    $admin_confirmation_template_content = $this->replace_mail_tags( $admin_confirmation_template_content, $order_id );
                    $email_body_close                    = $this->email_body_close();
                    $admin_email_booking_body_full       = $email_body_open . $admin_confirmation_template_content . $email_body_close;
                    $admin_email_booking_body_full       = wp_kses_post( html_entity_decode( $admin_email_booking_body_full, '3', 'UTF-8' ) );
                   
                    //send multiple emails to multiple admins
                    if ( strpos( $sale_notification_email, ',' ) !== false ) {
                        $sale_notification_email = explode( ',', $sale_notification_email );
                        $sale_notification_email = str_replace( ' ', '', $sale_notification_email );
                        foreach ( $sale_notification_email as $key => $email_address ) {
                            wp_mail( $email_address, $email_subject, $admin_email_booking_body_full, $headers );
                        }
                    } else {
                        //send admin email
                        wp_mail( $sale_notification_email, $email_subject, $admin_email_booking_body_full, $headers );

                    }
                } 
            }

            //send vendor confirmation email template
            if( ! empty ( $enable_vendor_conf_email ) && $enable_vendor_conf_email == 1 ){
                //email settings metabox value
                if( ! empty ( $vendor_confirmation_template_id ) ){
                    //get the mail template content   
                    $vendor_confirmation_email_template   = get_post( $vendor_confirmation_template_id );
                    $vendor_confirmation_template_content = !empty( $vendor_confirmation_email_template->post_content ) ? $vendor_confirmation_email_template->post_content : ' ';
                    $vendor_confirmation_template_content = $this->replace_mail_tags( $vendor_confirmation_template_content, $order_id );
                    
                    $meta                    = get_post_meta( $vendor_confirmation_template_id, 'tf_email_templates_metabox', true );
                    $brand_logo              = !empty( $meta['brand_logo'] ) ? $meta['brand_logo'] : '';
                    $sale_notification_email = !empty( $meta['sale_notification_email'] ) ? $meta['sale_notification_email'] : '';
                    $email_subject           = !empty( $meta['email_subject'] ) ? $meta['email_subject'] : '';
                    $email_from_name         = !empty( $meta['email_from_name'] ) ? $meta['email_from_name'] : '';
                    $email_from_email        = !empty( $meta['email_from_email'] ) ? $meta['email_from_email'] : '';
                    $order_email_heading     = !empty( $meta['order_email_heading'] ) ? $meta['order_email_heading'] : '';
                    $email_header_bg         = ! empty( $meta['email_header_bg'] ) ? unserialize($meta['email_header_bg']) : array();
                    $email_header_bg         = ! empty( $email_header_bg['bg_color'] ) ? $email_header_bg['bg_color'] : '';
                    //mail headers
                    $charset  = apply_filters( 'tourfic_mail_charset', 'Content-Type: text/html; charset=UTF-8' );
                    $headers  = $charset . "\r\n";
                    $headers .= "MIME-Version: 1.0" . "\r\n";
                    $headers .= "From: $email_from_name <$email_from_email>" . "\r\n";
                    $headers .= "Reply-To: $email_from_name <$email_from_email>" . "\r\n";
                    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
                    //email body open
                    $email_body_open                      = $this->email_body_open( $brand_logo, $order_email_heading, $email_header_bg);
                    $email_body_open                      = str_replace( '{booking_id}', $order_id, $email_body_open );
                    $vendor_confirmation_template_content = $this->replace_mail_tags( $vendor_confirmation_template_content, $order_id );
                    $email_body_close                     = $this->email_body_close();
                    $vendor_email_booking_body_full       = $email_body_open . $vendor_confirmation_template_content . $email_body_close;
                    $vendor_email_booking_body_full       = wp_kses_post( html_entity_decode( $vendor_email_booking_body_full, '3', 'UTF-8' ) );
                    //send mail to vendor
                    if ( !empty( $this->vendors_email ) ) {
                        foreach ( $this->vendors_email as $key => $vendor_email ) {
                            wp_mail( $vendor_email, $email_subject, $vendor_email_booking_body_full, $headers );
                        }
                    }
                }
            }
            //send customer confirmation email template
            if( ! empty ( $enable_customer_conf_email ) && $enable_customer_conf_email == 1 ){
                //email settings metabox value
                if( ! empty ( $customer_confirmation_template_id ) ){
                    //echo "hels";
                    //get the mail template content   
                    $customer_confirmation_email_template   = get_post( $customer_confirmation_template_id );
                    $customer_confirmation_template_content = !empty( $customer_confirmation_email_template->post_content ) ? $customer_confirmation_email_template->post_content : ' ';
                    $customer_confirmation_template_content = $this->replace_mail_tags( $customer_confirmation_template_content, $order_id );
                    $meta                    = get_post_meta( $customer_confirmation_template_id, 'tf_email_templates_metabox', true );
                    $brand_logo              = !empty( $meta['brand_logo'] ) ? $meta['brand_logo'] : '';
                    $sale_notification_email = !empty( $meta['sale_notification_email'] ) ? $meta['sale_notification_email'] : '';
                    $email_subject           = !empty( $meta['email_subject'] ) ? $meta['email_subject'] : '';
                    $email_from_name         = !empty( $meta['email_from_name'] ) ? $meta['email_from_name'] : '';
                    $email_from_email        = !empty( $meta['email_from_email'] ) ? $meta['email_from_email'] : '';
                    $order_email_heading     = !empty( $meta['order_email_heading'] ) ? $meta['order_email_heading'] : '';
                    $email_header_bg         = ! empty( $meta['email_header_bg'] ) ? unserialize($meta['email_header_bg']) : array();
                    $email_header_bg         = ! empty( $email_header_bg['bg_color'] ) ? $email_header_bg['bg_color'] : '';
                    //mail headers
                    $charset  = apply_filters( 'tourfic_mail_charset', 'Content-Type: text/html; charset=UTF-8' );
                    $headers  = $charset . "\r\n";
                    $headers .= "MIME-Version: 1.0" . "\r\n";
                    $headers .= "From: $email_from_name <$email_from_email>" . "\r\n";
                    $headers .= "Reply-To: $email_from_name <$email_from_email>" . "\r\n";
                    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
                    //email body open
                    $email_body_open                        = $this->email_body_open( $brand_logo, $order_email_heading, $email_header_bg);
                    $email_body_open                        = str_replace( '{booking_id}', $order_id, $email_body_open );
                    $customer_confirmation_template_content = $this->replace_mail_tags( $customer_confirmation_template_content, $order_id );
                    $email_body_close                       = $this->email_body_close();
                    $customer_email_booking_body_full       = $email_body_open . $customer_confirmation_template_content . $email_body_close;
                    $customer_email_booking_body_full       = wp_kses_post( html_entity_decode( $customer_email_booking_body_full, '3', 'UTF-8' ) );
                    
                    //send mail to customer
                    wp_mail( $order_billing_email, $email_subject, $customer_email_booking_body_full, $headers );
                }
            }
               
        endif;
        
    }

    /**
     * Send mail when order cancelled
     * @param  int $order_id
     * @return void
     */
    public function send_cancellation_email_pro( $order_id ){
        if( function_exists( 'is_tf_pro' ) && is_tf_pro() ):
            //get order details
            $order = wc_get_order( $order_id );
            //get customer email
            $order_billing_email    = $order->get_billing_email();

            //email body ended
            $email_template_settings           = $this::$tf_email_template_settings;
            $enable_admin_canc_email           = !empty( $email_template_settings['enable_admin_canc_email'] ) ? $email_template_settings['enable_admin_canc_email'] : '';
            $enable_vendor_canc_email          = !empty( $email_template_settings['enable_vendor_canc_email'] ) ? $email_template_settings['enable_vendor_canc_email'] : '';
            $enable_customer_canc_email        = !empty( $email_template_settings['enable_customer_canc_email'] ) ? $email_template_settings['enable_customer_canc_email'] : '';
            $admin_cancellation_template_id    = !empty( $email_template_settings['admin_cancellation_email_template'] ) ? $email_template_settings['admin_cancellation_email_template'] : '';
            $vendor_cancellation_template_id   = !empty( $email_template_settings['vendor_cancellation_email_template'] ) ? $email_template_settings['vendor_cancellation_email_template'] : '';
            $customer_cancellation_template_id = !empty( $email_template_settings['customer_cancellation_email_template'] ) ? $email_template_settings['customer_cancellation_email_template'] : '';
            //send admin cancellation email template
            if( ! empty ( $enable_admin_canc_email ) && $enable_admin_canc_email == 1 ){
                //email settings metabox value
                if( ! empty ( $admin_cancellation_template_id ) ){
                    //get the mail template content   
                    $admin_cancellation_email_template   = get_post( $admin_cancellation_template_id );
                    $admin_cancellation_template_content = !empty( $admin_cancellation_email_template->post_content ) ? $admin_cancellation_email_template->post_content : ' ';
                    $admin_cancellation_template_content = $this->replace_mail_tags( $admin_cancellation_template_content, $order_id );
                    
                    $meta                    = get_post_meta( $admin_cancellation_template_id, 'tf_email_templates_metabox', true );
                    $brand_logo              = !empty( $meta['brand_logo'] ) ? $meta['brand_logo'] : '';
                    $sale_notification_email = !empty( $meta['sale_notification_email'] ) ? $meta['sale_notification_email'] : get_bloginfo( 'admin_email' );
                    $email_subject           = !empty( $meta['email_subject'] ) ? $meta['email_subject'] : '';
                    $email_from_name         = !empty( $meta['email_from_name'] ) ? $meta['email_from_name'] : '';
                    $email_from_email        = !empty( $meta['email_from_email'] ) ? $meta['email_from_email'] : '';
                    $order_email_heading     = !empty( $meta['order_email_heading'] ) ? $meta['order_email_heading'] : '';
                    $email_header_bg         = ! empty( $meta['email_header_bg'] ) ? unserialize($meta['email_header_bg']) : array();
                    $email_header_bg         = ! empty( $email_header_bg['bg_color'] ) ? $email_header_bg['bg_color'] : '';
                    //mail headers
                    $charset  = apply_filters( 'tourfic_mail_charset', 'Content-Type: text/html; charset=UTF-8' );
                    $headers  = $charset . "\r\n";
                    $headers .= "MIME-Version: 1.0" . "\r\n";
                    $headers .= "From: $email_from_name <$email_from_email>" . "\r\n";
                    $headers .= "Reply-To: $email_from_name <$email_from_email>" . "\r\n";
                    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
                    //email body open
                    $email_body_open                     = $this->email_body_open( $brand_logo, $order_email_heading, $email_header_bg);
                    $email_body_open                     = str_replace( '{booking_id}', $order_id, $email_body_open );
                    $admin_cancellation_template_content = $this->replace_mail_tags( $admin_cancellation_template_content, $order_id );
                    $email_body_close                    = $this->email_body_close();
                    $admin_email_cancellation_body_full  = $email_body_open . $admin_cancellation_template_content . $email_body_close;
                    $admin_email_cancellation_body_full  = wp_kses_post( html_entity_decode( $admin_email_cancellation_body_full, '3', 'UTF-8' ) );
                    //send mail to admin
                    wp_mail( $sale_notification_email, $email_subject, $admin_email_cancellation_body_full, $headers );            
                }
            }
            //send vendor cancellation email template
            if( ! empty ( $enable_vendor_canc_email ) && $enable_vendor_canc_email == 1 ){
                //email settings metabox value
                if( ! empty ( $vendor_cancellation_template_id ) ){
                    //get the mail template content   
                    $vendor_cancellation_email_template   = get_post( $vendor_cancellation_template_id );
                    $vendor_cancellation_template_content = !empty( $vendor_cancellation_email_template->post_content ) ? $vendor_cancellation_email_template->post_content : ' ';
                    $vendor_cancellation_template_content = $this->replace_mail_tags( $vendor_cancellation_template_content, $order_id );
                    
                    $meta                    = get_post_meta( $vendor_cancellation_template_id, 'tf_email_templates_metabox', true );
                    $brand_logo              = ! empty( $meta['brand_logo'] ) ? $meta['brand_logo'] : '';
                    $sale_notification_email = ! empty( $meta['sale_notification_email'] ) ? $meta['sale_notification_email'] : '';
                    $email_subject           = ! empty( $meta['email_subject'] ) ? $meta['email_subject'] : '';
                    $email_from_name         = ! empty( $meta['email_from_name'] ) ? $meta['email_from_name'] : '';
                    $email_from_email        = ! empty( $meta['email_from_email'] ) ? $meta['email_from_email'] : '';
                    $order_email_heading     = ! empty( $meta['order_email_heading'] ) ? $meta['order_email_heading'] : '';
                    $email_header_bg         = ! empty( $meta['email_header_bg'] ) ? unserialize($meta['email_header_bg']) : array();
                    $email_header_bg         = ! empty( $email_header_bg['bg_color'] ) ? $email_header_bg['bg_color'] : '';
                    //mail headers
                    $charset  = apply_filters( 'tourfic_mail_charset', 'Content-Type: text/html; charset=UTF-8' );
                    $headers  = $charset . "\r\n";
                    $headers .= "MIME-Version: 1.0" . "\r\n";
                    $headers .= "From: $email_from_name <$email_from_email>" . "\r\n";
                    $headers .= "Reply-To: $email_from_name <$email_from_email>" . "\r\n";
                    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
                    //email body open
                    $email_body_open                      = $this->email_body_open( $brand_logo, $order_email_heading, $email_header_bg);
                    $email_body_open                      = str_replace( '{booking_id}', $order_id, $email_body_open );
                    $vendor_cancellation_template_content = $this->replace_mail_tags( $vendor_cancellation_template_content, $order_id );
                    $email_body_close                     = $this->email_body_close();
                    $vendor_email_cancellation_body_full  = $email_body_open . $vendor_cancellation_template_content . $email_body_close;
                    $vendor_email_cancellation_body_full  = wp_kses_post( html_entity_decode( $vendor_email_cancellation_body_full, '3', 'UTF-8' ) );
                    //send mail to vendor
                    foreach( $this->vendors_email as $key => $vendor_email ){
                        wp_mail( $vendor_email, $email_subject, $vendor_email_cancellation_body_full, $headers );
                    }
                }
            }
            //send customer cancellation email template
            if( ! empty( $enable_customer_canc_email ) && $enable_customer_canc_email == 1 ){
                if( ! empty( $customer_cancellation_template_id )){
                    //get the mail template content   
                    $customer_cancellation_email_template   = get_post( $customer_cancellation_template_id );
                    $customer_cancellation_template_content = !empty( $customer_cancellation_email_template->post_content ) ? $customer_cancellation_email_template->post_content : ' ';
                    $customer_cancellation_template_content = $this->replace_mail_tags( $customer_cancellation_template_content, $order_id );
                   
                    
                    $meta                    = get_post_meta( $customer_cancellation_template_id, 'tf_email_templates_metabox', true );
                    $brand_logo              = !empty( $meta['brand_logo'] ) ? $meta['brand_logo'] : '';
                    $sale_notification_email = !empty( $meta['sale_notification_email'] ) ? $meta['sale_notification_email'] : $order_billing_email;
                    $email_subject           = !empty( $meta['email_subject'] ) ? $meta['email_subject'] : '';
                    $email_from_name         = !empty( $meta['email_from_name'] ) ? $meta['email_from_name'] : '';
                    $email_from_email        = !empty( $meta['email_from_email'] ) ? $meta['email_from_email'] : '';
                    $order_email_heading     = !empty( $meta['order_email_heading'] ) ? $meta['order_email_heading'] : '';
                    $email_header_bg         = !empty( $meta['email_header_bg'] ) ? unserialize($meta['email_header_bg']) : '';
                    $email_header_bg         = $email_header_bg['bg_color'];
                    //mail headers
                    $charset  = apply_filters( 'tourfic_mail_charset', 'Content-Type: text/html; charset=UTF-8' );
                    $headers  = $charset . "\r\n";
                    $headers .= "MIME-Version: 1.0" . "\r\n";
                    $headers .= "From: $email_from_name <$email_from_email>" . "\r\n";
                    $headers .= "Reply-To: $email_from_name <$email_from_email>" . "\r\n";
                    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
                    //email body open
                    $email_body_open                        = $this->email_body_open( $brand_logo, $order_email_heading, $email_header_bg);
                    $email_body_open                        = str_replace( '{booking_id}', $order_id, $email_body_open );
                    $customer_cancellation_template_content = $this->replace_mail_tags( $customer_cancellation_template_content, $order_id );
                    $email_body_close                       = $this->email_body_close();
                    $customer_email_cancellation_body_full  = $email_body_open . $customer_cancellation_template_content . $email_body_close;
                    $customer_email_cancellation_body_full  = wp_kses_post( html_entity_decode( $customer_email_cancellation_body_full, '3', 'UTF-8' ) );
                    //send mail to customer
                    wp_mail( $order_billing_email, $email_subject, $customer_email_cancellation_body_full, $headers );

                }
            }
        endif;

    }

}
//init the class
new TF_Handle_Emails();