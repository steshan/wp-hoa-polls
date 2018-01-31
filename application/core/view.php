<?php

class View {
    function generate($content_view, $template_view, $data = null) {
        include_once WP_HOA_ROOT . '/application/view/' . $template_view;
    }
}

