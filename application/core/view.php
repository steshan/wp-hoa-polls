<?php

class View {
    function generate($content_view, $template_view, $data = null) {
        include WP_HOA_ROOT . '/application/view/' . $template_view;
    }
}

