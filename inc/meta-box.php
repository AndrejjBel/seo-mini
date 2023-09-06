<?php
$delimiter = seo_mini_get_options('delimiter');
$title_end = seo_mini_get_options('title_page_end');
class_exists( 'Kama_Post_Meta_Box' ) && new Kama_Post_Meta_Box( [
	'id'        => '_seo_mini',
	'title'     => 'Сео метатеги (Seo mini)',
	'theme'     => 'grid',
	// 'post_type' => [ 'page' ],
    // 'disable_func' => 'disable_box',
	'fields'    => [
		'seo_mini_title' => [
			'title' => 'Title',
			'callback'      => 'seo_mini_title_field',
			// 'sanitize_func' => static function ( $array ) {
			// 	return array_map( 'sanitize_text_field', $array );
			// },
		],
		'seo_mini_description' => [
			'title' => 'Description',
			'callback'      => 'seo_mini_description_field',
			// 'sanitize_func' => static function ( $array ) {
			// 	return array_map( 'sanitize_text_field', $array );
			// },
		],
		'seo_mini_keywords' => [
			'title' => 'Keywords',
            'type'  => 'textarea',
			'desc_after'  => 'Укажите Keywords, если не заполнено, выводиться не будет',
		],
        // 'seo_mini_delimiter' => [
		// 	'title' => 'Разделитель',
		// 	'placeholder' => $delimiter,
		// 	'desc_after'  => 'Укажите, если хотите использовать разделитель, отличный от указанного в общих настройках',
        //     'attr'  => 'style="width:10%;"',
		// ],
        // 'seo_mini_title_end' => [
		// 	'title' => 'Текст после заголовка',
        //     'type'  => 'textarea',
		// 	'placeholder' => $title_end,
		// 	'desc_after'  => 'Укажите, если хотите использовать Текст после заголовка, отличный от указанного в общих настройках',
		// ],
	],
] );

// функция вывода поля
function seo_mini_title_field( $args, $post, $name, $val ){
	ob_start();
	echo '<input type="text" name="' . $name . '" value="' . esc_attr( @ $val ) . '" placeholder="' . get_the_title($post->ID) . '" class="seo-mini-input"><br>
	<p class="description kpmb__desc --after">Укажите Title, по умолчанию - текущий заголовок</p>';
	return ob_get_clean();
}

function seo_mini_description_field( $args, $post, $name, $val ){
	$maxchar = seo_mini_get_options('count_descr');
	$placeholder = kama_excerpt_seo_mini( [ 'maxchar'=>$maxchar, 'autop'=>false, 'sanitize_callback' =>'sanitize_text_field', $post->post_content ] );
	ob_start();
	echo '<textarea name="' . $name . '" value="' . esc_attr( @ $val ) . '" placeholder="' . $placeholder . '" class="seo-mini-textarea">' . esc_attr( @ $val ) . '</textarea><br>
	<p class="description kpmb__desc --after">Укажите Description, по умолчанию - отрывок содержания</p>';
	return ob_get_clean();
}

// function disable_box($post) {
//     if ( $post->ID != 6 && $post->ID != 8 ) {
//         return 'отключить';
//     }
// }
