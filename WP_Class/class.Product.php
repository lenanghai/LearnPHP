<?php
/**
* @author Kai Dev
* @since 1.0
* @date 4/2015
*/

if (!defined('ABSPATH'))
    exit('No direct script access allowed');

class AWEProduct {

    protected $args;
    protected $tax_values;

    public function __construct() {
        // Setting something
        add_action('wp_ajax_product_filter', array($this, 'refreshProduct'));
        add_action('wp_ajax_nopriv_product_filter', array($this, 'refreshProduct'));
        //Setting args
        $this->args = array();
        $this->args['post_type'] = 'product';
        $this->args['tax_query'] = array('relation' => 'AND');
        $this->args['post_status'] = 'publish';
    }

    /**
     * Set Tax Query
     * @param type $args
     * @param type $value
     */
    public function setTaxQuery(&$args, $name, $value) {
        $values = explode(",", $value);
        $this->args['tax_query'][] = array(
            'taxonomy' => $name,
            'field' => 'slug',
            'terms' => $values,
        );
    }

    /**
     * Set Price Query
     * @param type $args
     * @param type $value
     */
    public function setPriceQuery(&$args, $value) {
        $values = explode(",", $value);
        if (count($values) != 2) {
            die();
        }
        // Get Price Range
        $min = intval($values[0]);
        $max = intval($values[1]);
        $price_value = array($min, $max);
        $this->args['meta_query'][] = $price_value;
    }

    /**
     * Set Order Query
     * @param type $args
     * @param type $value
     */
    public function setOrderbyQuery(&$args, $value) {
         $order = "ASC";
         if (@ preg_match("/-/", $value)) {
            list($orderby, $order) = explode("-", $value);
        }else{
            $orderby = $value;
            $order = "ASC";
        }       
        
        // default - menu_order
        $this->args['orderby'] = 'menu_order title';
        $this->args['order'] = $order == 'DESC' ? 'DESC' : 'ASC';

        
        switch ($orderby) {
            case 'rand' :
                $this->args['orderby'] = 'rand';
                break;
            case 'date' :
                $this->args['orderby'] = 'date';
                $this->args['order'] = $order == 'ASC' ? 'ASC' : 'DESC';
                break;
            case 'price' :
                $this->args['orderby'] = 'meta_value_num';
                $this->args['order'] = $order == 'desc' ? 'DESC' : 'ASC';
                $this->args['meta_key'] = '_price';
                break;
            case 'popularity' :
                $this->args['meta_key'] = 'total_sales';
                break;
        }
        
        
    }

    /**
     * Set Paging Query
     * @param type $args
     * @param type $value
     */
    public function setPostPerPageQuery( &$args, $value ) {
        $this->args['posts_per_page'] = $value;
    }

    /**
     * Set Paging Query
     * @param type $args
     * @param page
     */
    public function setCurrentPage( &$args, $value ) {
        $this->args['paged'] = $value;
    }

    /**
     * Main Query via ajax
     */
    public function setMainQuery($args, $layout) {
        $view = ($layout == "grid") ? "grid-cn" : "list-cn";
        /**
         * Main AJAX Query
         */
        echo '<pre>';
        print_r($args);
        echo '</pre>';
        $wp_query = new WP_Query($args);
        $a = "";
        if ($wp_query->have_posts()) {
            // do_action('woocommerce_before_shop_loop');
            woocommerce_product_loop_start();
            woocommerce_product_subcategories();
            ob_start();
            echo '<div class="' . $view . '">';
            while ($wp_query->have_posts()) : $wp_query->the_post();
                wc_get_template_part('content', $layout);
            endwhile;
            echo '</div>';
            woocommerce_product_loop_end();
            // do_action('woocommerce_after_shop_loop');
            $a = ob_get_contents();
            ob_get_clean();
            echo $a;
            wp_reset_postdata();
        }
        exit();
    }

    /**
     * Refresh Product
     * @return ProductList
     */
    public function refreshProduct() {

        //By Product cat
        if (!empty($_GET['product_cat'])) {
            $value = $_GET['product_cat'];
            $name = "product_cat";
            $this->setTaxQuery($args, $name, $value);
        }

        //By Manufacture
        if (!empty($_GET['manufacture'])) {
            $value = $_GET['manufacture'];
            $name = "manufacture";
            $this->setTaxQuery($args, $name, $value);
        }

        //By Size
        if (!empty($_GET['size'])) {
            $value = $_GET['size'];
            $name = "pa_size";
            $this->setTaxQuery($args, $name, $value);
        }

        // By Color
        if (!empty($_GET['color'])) {
            $value = $_GET['color'];
            $name = "pa_color";
            $this->setTaxQuery($args, $name, $value);
        }


        // By Price
        if (!empty($_GET['price'])) {
            $price = $_GET['price'];
            $this->setPriceQuery($args, $price);
        }


        // Order By

        if (!empty($_GET['orderby'])) {
            $value = $_GET['orderby'];
            $this->setOrderbyQuery($args, $value);
        }

        // Show post

        if (!empty($_GET['show'])) {
            if($_GET['show']=="all"){
                
            }else{
            $show = $_GET['show'];
            $this->setPostPerPageQuery($args, $show);
            }
        }
       
        // Current Page
        
        if(!empty($_GET['paged'])){
            $value = $_GET['paged'];
            $this->setCurrentPage($args, $value);            
        }
        


        // View         
        if (isset($_GET['view'])) {
            $view = $_GET['view'];
        } else {
            $view = "list";
        }
        $args = $this->args;
        // Set Main Query
        $this->setMainQuery($args, $view);
        exit();
    }

}
