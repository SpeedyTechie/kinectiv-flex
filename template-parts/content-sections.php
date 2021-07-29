<?php
$default_args = array(
    'sections' => null
);
$args = array_merge($default_args, $args);


if ($args['sections']) {
    foreach ($args['sections'] as $i_section => $section) {
        // get previous section and next section
        $section_prev = ($i_section > 0) ? $args['sections'][$i_section - 1] : null;
        $section_next = ($i_section < count($args['sections']) - 1) ? $args['sections'][$i_section + 1] : null;

        // pass variables to template part
        $section_args = array(
            'sections' => array(
                'current_index' => $i_section,
                'current' => $section,
                'prev' => $section_prev,
                'next' => $section_next
            )
        );

        get_template_part('template-parts/section', $section['acf_fc_layout'], $section_args);
    }
}
?>