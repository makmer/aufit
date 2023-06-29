<?php
/**
 * Single variation cart button
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;

global $product;
?>

<div class="woocommerce-variation-add-to-cart variations_button">
    <?php do_action('woocommerce_before_add_to_cart_button'); ?>
    <div class="input-wrap">
        <?php
        do_action('woocommerce_before_add_to_cart_quantity');
        ?>
        <div class='quantity-wrapper input-wrap__item'>
            <div class="title"><?php echo esc_html(pll__('Количество листов')); ?>, мм</div>
            <?php
            woocommerce_quantity_input(
                array(
                    'min_value' => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                    'max_value' => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                    'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
                )
            );

            echo '</div>';

            do_action('woocommerce_after_add_to_cart_quantity');
            ?>
        </div>
        <div class="add-to-cart-wraper">
            <button type="submit"
                    class="single_add_to_cart_button button alt btn-custom btn-custom--primary<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>"><?php echo esc_html($product->single_add_to_cart_text()); ?></button>
            <!-- <p id="test">here will be work</p> -->

            <?php do_action('woocommerce_after_add_to_cart_button'); ?>

            <input type="hidden" name="add-to-cart" value="<?php echo absint($product->get_id()); ?>"/>
            <input type="hidden" name="product_id" value="<?php echo absint($product->get_id()); ?>"/>
            <input type="hidden" name="variation_id" class="variation_id" value="0"/>
        </div>
        <div class="price-wraper">
            <div class="price-wraper__item total_price">
                <span><?php echo esc_html(pll__('Ціна загальна')); ?> :</span>
                <p id="total_price"><?php echo make_total_price($product, 1); ?></p>
            </div>
 <?php
        $meta = !empty(get_post_meta($product->get_id(), 'product_type_meta', true)) ? get_post_meta($product->get_id(), 'product_type_meta', true) : 0;
        ?>
        <?php if ($meta == 2 || $meta == 3): ?>
            <div class="price-for-m">
                <span><?php echo esc_html(pll__('Ціна за м²')); ?> :</span>
                <?php if ($meta == 2): ?>
                    <p id="price_for_m"><?php echo get_price_for_m($product); ?></p>
                <?php elseif ($meta == 3): ?>
                    <p id="price_for_mch"><?php echo get_price_for_mch($product); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
            <div class="price-wraper__item">
                <span><?php echo esc_html(pll__(get_price_text($product))); ?> :</span>
                <p class="<?php echo esc_attr(apply_filters('woocommerce_product_price_class', 'price-custom')); ?>"><span class="woocommerce-Price-amount amount" id="price_for_q"><?php echo make_price_for_q($product); ?></span></p>
            </div>
			
        </div>
		
<!--         <?php
        $meta = !empty(get_post_meta($product->get_id(), 'product_type_meta', true)) ? get_post_meta($product->get_id(), 'product_type_meta', true) : 0;
        ?>
        <?php if ($meta == 2 || $meta == 3): ?>
            <div class="price-for-m">
                <span><?php echo esc_html(pll__('Ціна за м²')); ?> :</span>
                <?php if ($meta == 2): ?>
                    <p id="price_for_m"><?php echo get_price_for_m($product); ?></p>
                <?php elseif ($meta == 3): ?>
                    <p id="price_for_mch"><?php echo get_price_for_mch($product); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?> -->
        <!--div class="price-wraper--square">
		<span><?php echo esc_html(pll__('Розничная цена')); ?> :</span>
		<span class="price-square">222</span>
	</div-->


        <?php
        // echo $product->get_price_excluding_tax();

        //echo $price = wc_get_product($product_or_variation_id)->get_price();

        //function get_cart_item_prices() {
        $user_cart = WC()->cart->get_cart();
        foreach ($user_cart as $cart_item) {
            $price = wc_get_product($cart_item['variation_id'])->get_price();
            // echo $price;
            // echo "<br/>";
        }

        $args = array(
            'post_type' => 'product_variation',
            'post_status' => array('private', 'publish'),
            'numberposts' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'post_parent' => get_the_ID() // get parent post-ID
        );
        $variations = get_posts($args);

        foreach ($variations as $variation) {

            // get variation ID
            $variation_ID = $variation->ID;

            // get variations meta
            $product_variation = new WC_Product_Variation($variation_ID);

            // get variation featured image
            $variation_image = $product_variation->get_image();

            // get variation price
            $variation_price = $product_variation->get_price_html();
            //get variation name
            $variation_name = $product_variation->get_variation_attributes();
//echo $variation_image;
//echo "<p class='product_price' >";
//echo $variation_name["KEY"].": ";
//echo ($variation_price);
//echo "</p>";
        }

        //}

        ?>

        <?php
        //$product = wc_get_product();
        //echo wc_get_price_to_display($product) . '<br />';
        ?>

    </div>
