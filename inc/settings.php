<?php
add_action( 'admin_menu', 'seo_mini_settings_menu_page', 25 );

function seo_mini_settings_menu_page(){
    global $exec_settings_page;
	add_menu_page(
		'Настройки Seo mini', // тайтл страницы
		'Настройки Seo mini', // текст ссылки в меню
		'manage_options', // права пользователя, необходимые для доступа к странице
		'seo_mini_settings', // ярлык страницы
		'seo_mini_settings_page_callback', // функция, которая выводит содержимое страницы
		'dashicons-admin-generic', // иконка, в данном случае из Dashicons
		3 // позиция в меню
	);
}

function seo_mini_settings_page_callback(){
	?>
	<div class="wrap">
		<h2><?php echo get_admin_page_title() ?></h2>

		<form action="options.php" method="POST">
			<?php
				settings_fields( 'seo_mini_settings' );     // скрытые защитные поля
				do_settings_sections( 'seo_mini_settings' ); // секции с настройками (опциями). У нас она всего одна 'section_id'
				submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Регистрируем настройки.
 * Настройки будут храниться в массиве, а не одна настройка = одна опция.
 */
add_action( 'admin_init', 'seo_mini_settings' );

function seo_mini_settings(){

	// параметры: $option_group, $option_name, $sanitize_callback
	register_setting( 'seo_mini_settings', 'seo_mini_option', 'sanitize_callback' );
    // register_setting( 'exec_settings', 'lang', 'sanitize_callback_lang' );

	// параметры: $id, $title, $callback, $page
	add_settings_section( 'section_id', '', '', 'seo_mini_settings' ); // 'section_id', 'Основные настройки', '', 'exec_settings'

	// параметры: $id, $title, $callback, $page, $section, $args
	add_settings_field('seo_mini_field_delimiter', 'Разделитель', 'fill_seo_mini_field_delimiter', 'seo_mini_settings', 'section_id' );
    add_settings_field('seo_mini_field_title_page_end', 'Текст после заголовка для страниц', 'fill_seo_mini_field_title_page_end', 'seo_mini_settings', 'section_id' );
	add_settings_field('seo_mini_field_title_blog_end', 'Текст после заголовка для статей блога', 'fill_seo_mini_field_title_blog_end', 'seo_mini_settings', 'section_id' );
    add_settings_field('seo_mini_field_count_descr', 'Количество символов в Description', 'fill_seo_mini_field_count_descr', 'seo_mini_settings', 'section_id' );
    // add_settings_field('seo_mini_field_type_author', 'Тип автора', 'fill_seo_mini_field_type_author', 'seo_mini_settings', 'section_id' );
    // add_settings_field('seo_mini_field_author_name', 'Имя автора', 'fill_seo_mini_field_author_name', 'seo_mini_settings', 'section_id' );
    add_settings_field('seo_mini_field_logo', 'Логотип ссылка', 'fill_seo_mini_field_logo', 'seo_mini_settings', 'section_id' );
    add_settings_field('seo_mini_field_robots', 'Содержание файла robots.txt', 'fill_seo_mini_field_robots', 'seo_mini_settings', 'section_id' );
}

function fill_seo_mini_field_robots(){
	$val = get_option('seo_mini_option');
    $val_option = '';
    if ( $val ) {
        if ( array_key_exists( 'robots', $val ) ) {
            $val_option = $val['robots'] ? $val['robots'] : null;
        }
    }
	?>
    <div class="seo-mini-settings-field">
        <textarea name="seo_mini_option[robots]" rows="12" cols="80" placeholder=""><?php echo esc_attr( $val_option ) ?></textarea>
    </div>
	<?php
}

function fill_seo_mini_field_author_name(){
	$val = get_option('seo_mini_option');
    $val_option = '';
    if ( $val ) {
        if ( array_key_exists( 'author_name', $val ) ) {
            $val_option = $val['author_name'] ? $val['author_name'] : null;
        }
    }
	?>
    <div class="seo-mini-settings-field">
        <input type="text" name="seo_mini_option[author_name]" value="<?php echo esc_attr( $val_option ) ?>" />
    </div>
	<?php
}

function fill_seo_mini_field_logo(){
	$val = get_option('seo_mini_option');
    $val_option = '';
    if ( $val ) {
        if ( array_key_exists( 'logo', $val ) ) {
            $val_option = $val['logo'] ? $val['logo'] : null;
        }
    }
	?>
    <div class="seo-mini-settings-field">
        <input type="text" name="seo_mini_option[logo]" value="<?php echo esc_attr( $val_option ) ?>" />
    </div>
	<?php
}

function fill_seo_mini_field_delimiter(){
	$val = get_option('seo_mini_option');
    $val_option = '';
    if ( $val ) {
        if ( array_key_exists( 'delimiter', $val ) ) {
            $val_option = $val['delimiter'] ? $val['delimiter'] : null;
        }
    }
	?>
    <div class="seo-mini-settings-field">
        <input class="seo-mini-delimiter" type="text" name="seo_mini_option[delimiter]" value="<?php echo esc_attr( $val_option ) ?>" />
    </div>
	<?php
}

function fill_seo_mini_field_title_page_end(){
	$val = get_option('seo_mini_option');
    $val_option = '';
    if ( $val ) {
        if ( array_key_exists( 'title_page_end', $val ) ) {
            $val_option = $val['title_page_end'] ? $val['title_page_end'] : null;
        }
    }
	?>
    <div class="seo-mini-settings-field">
        <input type="text" name="seo_mini_option[title_page_end]" value="<?php echo esc_attr( $val_option ) ?>" />
    </div>
	<?php
}

function fill_seo_mini_field_title_blog_end(){
    $val = get_option('seo_mini_option');
    $val_option = '';
    if ( $val ) {
        if ( array_key_exists( 'title_blog_end', $val ) ) {
            $val_option = $val['title_blog_end'] ? $val['title_blog_end'] : null;
        }
    }
	?>
    <div class="seo-mini-settings-field">
        <input type="text" name="seo_mini_option[title_blog_end]" value="<?php echo esc_attr( $val_option ) ?>" />
    </div>
	<?php
}

function fill_seo_mini_field_count_descr(){
    $val = get_option('seo_mini_option');
    $val_option = '';
    if ( $val ) {
        if ( array_key_exists( 'count_descr', $val ) ) {
            $val_option = $val['count_descr'] ? $val['count_descr'] : null;
        }
    }
	?>
    <div class="seo-mini-settings-field">
        <input class="seo-mini-number" type="number" name="seo_mini_option[count_descr]" value="<?php echo esc_attr( $val_option ) ?>" />
    </div>
	<?php
}

function fill_seo_mini_field_type_author(){
    $val = get_option('seo_mini_option');
    $val_option = '';
    if ( $val ) {
        if ( array_key_exists( 'type_author', $val ) ) {
            $val_option = $val['type_author'] ? $val['type_author'] : null;
        }
    }
	?>
    <label><input type="radio" name="seo_mini_option[type_author]" value="Person" <?php checked( 'Person', $val_option ) ?> /> Person</label>
    <label><input type="radio" name="seo_mini_option[type_author]" value="Organization" <?php checked( 'Organization', $val_option ) ?> /> Organization</label>
	<?php
}

function fill_exec_field_lang(){
	$val_lang = get_option('lang');
	$val_lang = $val_lang ? $val_lang : null;
	?>
    <div class="settings-field">
        <textarea name="lang" rows="12" cols="80" placeholder=""><?php echo esc_attr( $val_lang ) ?></textarea>
    </div>
	<?php
}

// Очистка данных
function sanitize_callback( $options ){
	foreach( $options as $name => & $val ){
        if( $name == 'robots' )
			$val = strip_tags( $val );
            
        if( $name == 'type_author' )
			$val = strip_tags( $val );

		if( $name == 'author_name' )
			$val = strip_tags( $val );

        if( $name == 'logo' )
			$val = strip_tags( $val );

        if( $name == 'delimiter' )
			$val = strip_tags( $val );

        if( $name == 'title_page_end' )
			$val = strip_tags( $val );

		if( $name == 'title_blog_end' )
			$val = strip_tags( $val );

        if( $name == 'count_descr' )
			$val = strip_tags( $val );

        if( $name == 'checkbox' )
		    $val = intval( $val );
	}
	return $options;
}

function sanitize_callback_lang( $options ){
	foreach( $options as $option ){
		$option = strip_tags( $option );
	}
	return $options;
}

function seo_mini_get_options($key) {
    $set_options = get_option('seo_mini_option');
    return $set_options[$key];
}
