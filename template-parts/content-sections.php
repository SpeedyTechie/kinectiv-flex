<?php
$f_sections = get_field('page_content_sections'); // get sections for standard pages
if (is_singular('post')) {
    $f_sections = get_field('post_content_sections'); // get sections for single posts
} elseif (is_singular('event')) {
    $f_sections = get_field('event_content_sections'); // get sections for single events
} elseif (is_404()) {
    $f_sections = get_field('404_content_sections', 'option'); // get sections for 404 page
}

if ($f_sections) {
    foreach ($f_sections as $i_section => $section) {
        // get previous section and next section
        $section_prev = ($i_section > 0) ? $f_sections[$i_section - 1] : null;
        $section_next = ($i_section < count($f_sections) - 1) ? $f_sections[$i_section + 1] : null;

        // pass variables to template part
        acf_register_store('passthrough_section', array(
            'sections' => array(
                'current_index' => $i_section,
                'current' => $section,
                'prev' => $section_prev,
                'next' => $section_next
            )
        ));

        get_template_part('template-parts/section', $section['acf_fc_layout']);

        acf_register_store('passthrough_section', array());
    }
}
?>