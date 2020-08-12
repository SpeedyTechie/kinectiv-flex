<?php
/**
 * The main template file
 */

get_header();
?>

<?php
$f_sections = get_field('page_content_sections');

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

<?php
get_footer();
