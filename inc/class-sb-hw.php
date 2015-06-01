<?php
class SB_HW {
    public static function get_reference_cats($args = array()) {
        return SB_Term::get('rcat', $args);
    }

    public static function get_readable_post_types() {
        $defaults = array(
            'post',
            'home',
            'blog',
            'guide'
        );
        $customs = apply_filters('sb_theme_hw_readable_post_types', array());
        $defaults = wp_parse_args($defaults, $customs);
        return $defaults;
    }

    public static function the_recent_update_and_random_widget($exclude_ids = array()) {
        $transient_name = 'sb_theme_query_home_post_recent_update';
        if(false === ($query = get_transient($transient_name))) {
            $args = array(
                'post__not_in' => $exclude_ids,
                'post_type' => self::get_readable_post_types(),
                'posts_per_page' => 5
            );
            $query = SB_Query::get_recent_update($args);
            set_transient($transient_name, $query, DAY_IN_SECONDS);
        }

        if($query->have_posts()) : ?>
            <div class="sub-widget">
                <h4 class="sub-title"><span>Mới cập nhật</span></h4>
                <div class="sub-content">
                    <ul class="list-posts list-unstyled">
                        <?php while($query->have_posts()) : $query->the_post();
                            $exclude_ids[] = get_the_ID();
                            sb_get_custom_loop('loop-more-recent-sub-posts');
                        endwhile; wp_reset_postdata(); ?>
                    </ul>
                </div>
            </div>
        <?php endif;
        $transient_name = 'sb_theme_query_home_random_post';
        if(false === ($query = get_transient($transient_name))) {
            $args = array(
                'post__not_in' => $exclude_ids,
                'post_type' => SB_HW::get_readable_post_types(),
                'posts_per_page' => 4
            );
            $query = SB_Query::get_random_posts($args);
            set_transient($transient_name, $query, HOUR_IN_SECONDS);
        }
        if($query->have_posts()) : ?>
            <div class="sub-widget">
                <h4 class="sub-title"><span>Ngẫu nhiên</span></h4>
                <div class="sub-content">
                    <ul class="list-posts list-unstyled">
                        <?php while($query->have_posts()) : $query->the_post();
                            $exclude_ids[] = get_the_ID();
                            sb_get_custom_loop('loop-more-recent-sub-posts');
                        endwhile; wp_reset_postdata(); ?>
                    </ul>
                </div>
            </div>
        <?php endif;
    }

    public static function get_readable_posts($args = array()) {
        $args['post_type'] = self::get_readable_post_types();
        return SB_Query::get($args);
    }

    public static function get_recent_news($args = array()) {
        $ids = array();
        $query = SB_Query::get(array('posts_per_page' => -1, 'post_type' => 'post'));
        foreach($query->posts as $my_post) {
            $ids[] = $my_post->ID;
        }
        $tmp_args = array(
            'post_type' => 'blog',
            'posts_per_page' => -1
        );
        $term = SB_Term::get_by('slug', 'wp-news', 'bcat');
        if(is_object($term)) {
            $tmp_args['tax_query'] = array(
                array(
                    'taxonomy' => 'bcat',
                    'field' => 'id',
                    'terms' => $term->term_id
                )
            );
        }
        $query = SB_Query::get($tmp_args);
        foreach($query->posts as $my_post) {
            $ids[] = $my_post->ID;
        }
        $tmp_args = array(
            'post_type' => 'home',
            'posts_per_page' => -1
        );
        $term = SB_Term::get_by('slug', 'thong-bao', 'hcat');
        if(is_object($term)) {
            $tmp_args['tax_query'] = array(
                array(
                    'taxonomy' => 'hcat',
                    'field' => 'id',
                    'terms' => $term->term_id
                )
            );
        }
        $query = SB_Query::get($tmp_args);
        foreach($query->posts as $my_post) {
            $ids[] = $my_post->ID;
        }
        $args['post__in'] = $ids;
        $args['post_type'] = self::get_readable_post_types();
        return SB_Query::get($args);
    }

    public static function get_projects($args = array()) {
        $args['post_type'] = 'project';
        $all_project = SB_Post::get_by_slug('tat-ca-du-an', 'project');
        if($all_project) {
            $args['post__not_in'] = array($all_project->ID);
        }
        return SB_Query::get($args);
    }

    public static function get_project_cats($args = array()) {
        return SB_Term::get('pcat', $args);
    }

    public static function get_project_by_category($cat_id, $args = array()) {
        if($cat_id < 1) {
            return self::get_projects($args);
        }
        $tax_query = array(
            'taxonomy' => 'pcat',
            'field' => 'id',
            'terms' => $cat_id
        );
        $args = SB_Query::build_tax_query($tax_query, $args);
        return self::get_projects($args);
    }

    public static function get_reference_function($post_id) {
        $function = SB_Post::get_sb_meta($post_id, 'reference_function');
        return $function;
    }

    public static function the_reference_function($post_id) {
        $value = do_shortcode(self::get_reference_function($post_id));
        if(empty($value)) {
            return;
        }
        ?>
        <h2 class="reference-function">
            <a href="<?php echo get_permalink($post_id); ?>"><?php echo $value; ?></a>
        </h2>
        <?php
    }

    public static function get_example_guide_url($post_id) {
        return SB_Post::get_sb_meta($post_id, 'guide_url');
    }

    public static function get_example_download_url($post_id) {
        return SB_Post::get_sb_meta($post_id, 'download_url');
    }

    public static function get_example_source_url($post_id) {
        return SB_Post::get_sb_meta($post_id, 'source_url');
    }

    public static function get_example_source($path) {
        if(empty($path)) {
            return '';
        }
        $file = '/static/demo/' . $path;
        $example_path = untrailingslashit(ABSPATH) . $file;
        if(file_exists($example_path)) {
            return home_url($file);
        }
        return '';
    }

    public static function get_reference_description($post_id) {
        $desc = SB_Post::get_sb_meta($post_id, 'reference_description');
        return $desc;
    }

    public static function the_reference_description($post_id) {
        $value = self::get_reference_description($post_id);
        if(empty($value)) {
            return;
        }
        ?>
        <section class="description">
            <p><?php echo $value; ?></p>
        </section>
        <?php
    }

    public static function get_reference_long_description($post_id) {
        $desc = SB_Post::get_sb_meta($post_id, 'reference_long_description');
        $desc = wpautop($desc);
        return $desc;
    }

    public static function the_reference_long_description($post_id) {
        $value = self::get_reference_long_description($post_id);
        if(empty($value)) {
            return;
        }
        ?>
        <section class="long-description">
            <?php echo $value; ?>
        </section>
    <?php
    }

    public static function get_reference_return($post_id) {
        $value = SB_Post::get_sb_meta($post_id, 'reference_return');
        return $value;
    }

    public static function the_reference_return($post_id) {
        $value = self::get_reference_return($post_id);
        if(empty($value)) {
            return;
        }
        ?>
        <section class="return">
            <p><strong><?php _e('Giá trị trả về', 'sb-theme'); ?>:</strong> <?php echo $value; ?></p>
        </section>
    <?php
    }

    public static function get_reference_parameters($post_id) {
        $value = SB_Post::get_sb_meta($post_id, 'reference_parameters');
        $value = do_shortcode($value);
        return $value;
    }

    public static function the_reference_parameters($post_id) {
        $value = self::get_reference_parameters($post_id);
        if(empty($value)) {
            return;
        }
        ?>
        <hr>
        <section class="parameters">
            <h2><?php _e('Tham số đầu vào', 'sb-theme'); ?></h2>
            <?php echo $value; ?>
        </section>
        <?php
    }

    public static function get_reference_explanation($post_id) {
        $value = SB_Post::get_sb_meta($post_id, 'reference_explanation');
        $value = apply_filters('the_content', $value);
        return $value;
    }

    public static function the_reference_explanation($post_id) {
        $value = self::get_reference_explanation($post_id);
        if(empty($value)) {
            return;
        }
        ?>
        <section class="explanation">
            <?php echo $value; ?>
        </section>
    <?php
    }

    public static function get_reference_source($post_id) {
        $value = SB_Post::get_sb_meta($post_id, 'reference_source');
        $value = apply_filters('the_content', $value);
        return $value;
    }

    public static function the_reference_source($post_id) {
        $value = self::get_reference_source($post_id);
        if(empty($value)) {
            return;
        }
        ?>
        <hr>
        <section class="source-content">
            <h2><?php _e('Nguồn', 'sb-theme'); ?></h2>
            <?php echo $value; ?>
        </section>
    <?php
    }

    public static function get_reference_example($post_id) {
        $value = SB_Post::get_sb_meta($post_id, 'reference_example');
        $value = apply_filters('the_content', $value);
        return $value;
    }

    public static function the_reference_example($post_id) {
        $value = self::get_reference_example($post_id);
        if(empty($value)) {
            return;
        }
        ?>
        <hr>
        <section class="example">
            <h2><?php _e('Ví dụ', 'sb-theme'); ?></h2>
            <?php echo $value; ?>
        </section>
    <?php
    }

    public static function get_reference_loop_description($post_id) {
        $desc = self::get_reference_description($post_id);
        if(empty($desc)) {
            $desc = get_the_excerpt();
        }
        return $desc;
    }

    public static function get_reference_first_category($post_id) {
        $cat = SB_Post::get_first_term($post_id, 'rcat');
        return $cat;
    }

    public static function the_reference_loop_description($post_id) {
        $desc = self::get_reference_description($post_id);
        $first_cat = self::get_reference_first_category($post_id);
        $cat_name = '';
        if(!SB_Core::is_error($first_cat)) {
            $cat_name = SB_PHP::lowercase($first_cat->name);
        }
        $prefix = '';
        if('functions' == $cat_name) {
            $prefix = __('Hàm', 'sb-theme');
        } elseif('classes' == $cat_name) {
            $prefix = __('Lớp', 'sb-theme');
        } elseif('hooks' == $cat_name) {
            $prefix = __('Hook', 'sb-theme');
        } elseif('methods' == $cat_name) {
            $prefix = __('Phương thức', 'sb-theme');
        } elseif('codex' == $cat_name) {
            //$prefix = __('Hướng dẫn', 'sb-theme');
        }
        if(empty($desc)) {
            $desc = get_the_excerpt();
        }
        ?>
        <p>
            <?php if(!empty($prefix)) : ?>
                <strong><?php echo $prefix; ?>: </strong>
            <?php endif; ?>
            <?php echo $desc; ?>
        </p>
        <?php
    }

    public static function get_project_price($project_id) {
        $price = SB_Post::get_meta($project_id, 'wpcf-project-price');
        if(empty($price)) {
            $price = SB_Post::get_sb_meta($project_id, 'project_price');
        }
        return $price;
    }

    public static function the_project_price($project_id) {
        $price = self::get_project_price($project_id);
        $price = floatval($price);
        if(is_numeric($price) && $price > 10) {
            $price = number_format($price, 0, ',', '.') . '₫';
        } else {
            $price = __('Liên hệ', 'sb-theme');
        }
        echo $price;
    }

    public static function set_go_url($url) {
        global $sb_go_url;
        $sb_go_url = esc_url($url);
    }

    public static function get_go_url() {
        global $sb_go_url;
        return $sb_go_url;
    }

    public static function get_aff_url($post_id) {
        $result = SB_Post::get_meta($post_id, 'wpcf-affiliate-url');
        if(empty($result)) {
            $result = SB_Post::get_sb_meta($post_id, 'affiliate_url');
        }
        return $result;
    }

    public static function get_aff_by_slug($slug) {
        return SB_Post::get_by_slug($slug, 'aff');
    }

    public static function get_project_full_image_url($post_id) {
        $meta = SB_Post::get_meta($post_id, 'wpcf-full-thumbnail');
        $result = SB_PHP::get_first_image($meta);
        $result = str_replace('project/wp-content', 'wp-content', $result);
        if(empty($result)) {
            $result = SB_Post::get_sb_meta($post_id, 'project_full_image');
        }
        return $result;
    }

    public static function get_project_id($post_id) {
        $id = SB_Post::get_meta($post_id, 'wpcf-project-id');
        if(empty($id)) {
            $id = SB_Post::get_sb_meta($post_id, 'project_id');
        }
        return $id;
    }

    public static function get_project_short_description($post_id) {
        $description = SB_Post::get_meta($post_id, 'wpcf-short-description');
        if(empty($description)) {
            $description = SB_Post::get_sb_meta($post_id, 'project_description');
        }
        return $description;
    }

    public static function get_post_taxonomy_name() {
        $taxonomy_name = 'category';
        if(is_post_type_archive('blog') || is_singular('blog')) {
            $taxonomy_name = 'bcat';
        }
        if(is_post_type_archive('home') || is_singular('home')) {
            $taxonomy_name = 'hcat';
        }
        return $taxonomy_name;
    }
}