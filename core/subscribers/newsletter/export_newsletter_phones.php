<div class="nirweb_newsletter_export">
    <div class="nirweb_newsletter_tabs">
        <?php
        $current = isset($_GET['query']) && !empty($_GET['query']) ? sanitize_text_field($_GET['query']) : 'stock';
        $queries = array(
            'stock' =>  esc_html__('Stock','nss'),
            'onsale' => esc_html__('Onsale','nss'),
            'comments' => esc_html__('Comments','nss'),
            'newsletter' => esc_html__('Newsletter','nss'),
        );
        foreach ($queries as $query => $query_title){
            $class = ($query == $current ) ? 'nav-tab-active' : null;
            ?>
            <a class='nav-tab pi_nav_tb <?= esc_attr($class) ?>' href='<?= esc_url_raw(add_query_arg(array('query' => $query ,'action' => ''))) ?>'><p><?= esc_html( $query_title) ?></p></a>
        <?php } ?>
    </div>
    <div class="nirweb_newsletter_data">

    </div>
</div>

