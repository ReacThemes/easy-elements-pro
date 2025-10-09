<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Easy_Dynamic_Select_Ajax {

    public function __construct() {
        add_action('wp_ajax_dynamic_post_select_ajax', [$this, 'easyGetSelectInputData']);
        add_action('wp_ajax_nopriv_dynamic_post_select_ajax', [$this, 'no_permission']);
    }

    public function no_permission() {
        wp_send_json_error('No permission');
    }

    private function getSearchQuery() {
        return isset($_POST['search_text']) ? sanitize_text_field($_POST['search_text']) : '';
    }

    public function easyGetSelectInputData() {
        $nonce = isset($_POST['security']) ? sanitize_text_field($_POST['security']) : '';

        try {
            if (!wp_verify_nonce($nonce, 'easy_dynamic_post_select')) {
                throw new Exception('Invalid request');
            }

            if (!current_user_can('edit_posts')) {
                throw new Exception('Unauthorized request');
            }

            $query     = isset($_POST['query']) ? sanitize_text_field($_POST['query']) : '';
            $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'post';

            switch ($query) {
                case 'terms':
                    $data = $this->getTerms();
                    break;
                case 'authors':
                    $data = $this->getAuthors();
                    break;
                case 'authors_role':
                    $data = $this->getAuthorRoles();
                    break;
                case 'only_post':
                    $data = $this->getOnlyPosts($post_type);
                    break;
                case 'elementor_template':
                    $data = $this->getElementorTemplates();
                    break;
                case 'anywhere_template':
                    $data = $this->getAnywhereTemplates();
                    break;
                case 'elementor_dynamic_loop_template':
                    $data = $this->getDynamicTemplates();
                    break;
                case 'bbpress_single_forum':
                    $data = $this->getDynamicBbPressForumIDs();
                    break;
                case 'bbpress_single_topic':
                    $data = $this->getDynamicBbPressTopicIDs();
                    break;
                case 'bbpress_single_reply':
                    $data = $this->getDynamicBbPressReplyIDs();
                    break;
                case 'acf':
                    $data = $this->getAcfFields();
                    break;
                case 'comments':
                    $data = $this->getComments();
                    break;
                default:
                    $data = $this->getPosts($post_type);
                    break;
            }

            wp_send_json_success($data);
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }

        die();
    }

    /*------------------------------------
     * Helper: posts with Select2 format
     *------------------------------------*/
    private function getPosts( $post_type = 'post') {
        $search = $this->getSearchQuery();
        $args = [
            'post_type' => $post_type,
            'posts_per_page' => 20,
        ];
        if ($search) {
            $args['s'] = $search;
        }

        $query = new WP_Query($args);
        $results = [];
        foreach ($query->posts as $post) {
            $results[] = [
                'id'   => $post->ID,
                'text' => esc_html($post->post_title),
            ];
        }
        return $results;
    }

    private function getOnlyPosts($post_type = 'post') {
        return $this->getPosts($post_type);
    }

    private function getTerms() {
        $search = $this->getSearchQuery();
        $args = [
            'taxonomy' => 'category',
            'hide_empty' => false,
        ];
        if ($search) {
            $args['search'] = $search;
        }

        $terms = get_terms($args);
        $results = [];
        if (!is_wp_error($terms)) {
            foreach ($terms as $term) {
                $results[] = [
                    'id' => $term->term_id,
                    'text' => esc_html($term->name),
                ];
            }
        }
        return $results;
    }

    private function getAuthors() {
        $search = $this->getSearchQuery();
        $args = ['who' => 'authors'];
        if ($search) {
            $args['search'] = '*' . esc_attr($search) . '*';
        }

        $users = get_users($args);
        $results = [];
        foreach ($users as $user) {
            $results[] = [
                'id' => $user->ID,
                'text' => esc_html($user->display_name),
            ];
        }
        return $results;
    }

    private function getAuthorRoles() {
        global $wp_roles;
        $roles = $wp_roles->roles;
        $results = [];
        foreach ($roles as $key => $role) {
            $results[] = [
                'id' => $key,
                'text' => esc_html($role['name']),
            ];
        }
        return $results;
    }

    private function getElementorTemplates() {
        return $this->getPosts('elementor_library');
    }

    private function getAnywhereTemplates() {
        return $this->getPosts('ae_global_templates');
    }

    public function getDynamicTemplates() {
        $searchText = $this->getSearchQuery();
        $args = ['post_type' => 'elementor_library'];
        if ($searchText) {
            $args['s'] = $searchText;
        }
        $query = new WP_Query($args);
        $results = [];
        foreach ($query->posts as $post) {
            $results[] = [
                'id' => $post->ID,
                'text' => esc_html($post->post_title),
            ];
        }
        return $results;
    }

    private function getDynamicBbPressForumIDs() {
        if (!post_type_exists('forum')) return [];
        return $this->getPosts('forum');
    }

    private function getDynamicBbPressTopicIDs() {
        if (!post_type_exists('topic')) return [];
        return $this->getPosts('topic');
    }

    private function getDynamicBbPressReplyIDs() {
        if (!post_type_exists('reply')) return [];
        return $this->getPosts('reply');
    }

    private function getAcfFields() {
        if (!function_exists('acf_get_field_groups')) return [];
        $search = $this->getSearchQuery();
        $fields = [];
        $groups = acf_get_field_groups();
        foreach ($groups as $group) {
            $group_fields = acf_get_fields($group['key']);
            foreach ($group_fields as $field) {
                if ($search && stripos($field['label'], $search) === false) continue;
                $fields[] = [
                    'id' => $field['key'],
                    'text' => esc_html($field['label']),
                ];
            }
        }
        return $fields;
    }

    private function getComments() {
        $search = $this->getSearchQuery();
        $args = ['number' => 20];
        if ($search) {
            $args['search'] = $search;
        }

        $comments = get_comments($args);
        $results = [];
        foreach ($comments as $comment) {
            $results[] = [
                'id' => $comment->comment_ID,
                'text' => wp_trim_words($comment->comment_content, 5),
            ];
        }
        return $results;
    }
}

// Init
new Easy_Dynamic_Select_Ajax();