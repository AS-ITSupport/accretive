<?php
// $Id: site_map.admin.inc,v 1.1.2.7 2009/06/29 19:12:54 frjo Exp $

/**
 * @file
 * Original author: Nic Ivy
 * Now maintained by by Fredrik Jonsson fredrik at combonet dot se
 */

/**
 * Menu callback; presents the sitemap settings page.
 */
function site_map_admin_settings() {

  if (module_exists('locale')) {
    $langs = locale_language_list();
  }
  // Field to set site map message. Nifty language support from the catcha module, thanks.
  if ($langs) {
    $form['site_map_message'] = array(
      '#type' => 'fieldset',
      '#title' => t('Site map message'),
      '#description' => t('Define a message to be displayed above the site map.'),
    );
    foreach ($langs as $lang_code => $lang_name) {
      $form['site_map_message']["site_map_message_$lang_code"] = array(
        '#type' => 'textarea',
        '#title' => t('For language %lang_name (code %lang_code)', array('%lang_name' => $lang_name, '%lang_code' => $lang_code)),
        '#default_value' => _sitemap_get_message($lang_code),
        '#cols' => 60,
        '#rows' => 5,
      );
    }
  }
  else {
    $form['site_map_message'] = array(
      '#type' => 'textarea',
      '#title' => t('Site map message'),
      '#default_value' => _sitemap_get_message(),
      '#cols' => 60,
      '#rows' => 5,
      '#description' => t('Define a message to be displayed above the site map.'),
    );
  }

  $form['site_map_content'] = array(
    '#type' => 'fieldset',
    '#title' => t('Site map content'),
  );
  $form['site_map_content']['site_map_show_front'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show front page'),
    '#default_value' => variable_get('site_map_show_front', 1),
    '#description' => t('When enabled, this option will include the front page in the site map.'),
  );
  $form['site_map_content']['site_map_show_blogs'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show active blog authors'),
    '#default_value' => variable_get('site_map_show_blogs', 1),
    '#description' => t('When enabled, this option will show the 10 most active blog authors.'),
  );
  $book_options = array();
  if (module_exists('book')) {
    foreach (book_get_books() as $book) {
      $book_options[$book['nid']] = $book['title'];
    }
  }
  $form['site_map_content']['site_map_show_books'] = array(
    '#type' => 'select',
    '#title' => t('Books to include in the site map'),
    '#default_value' => variable_get('site_map_show_books', array()),
    '#options' => $book_options,
    '#multiple' => TRUE,
    '#description' => t('Ctrl-click (Windows) or Command-click (Mac) to select more than one value.'),
  );
//   $form['site_map_content']['site_map_books_expanded'] = array(
//     '#type' => 'checkbox',
//     '#title' => t('Show books expanded'),
//     '#default_value' => variable_get('site_map_books_expanded', 1),
//     '#description' => t('When enabled, this option will show all children pages for each book.'),
//   );

  $menu_options = array();
  $menu_options = menu_get_menus();

  if ($langs) {
    foreach ($langs as $lang_code => $lang_name) {
      $form['site_map_content']["site_map_show_menus_$lang_code"] = array(
        '#type' => 'select',
        '#title' => t('Menus to include in the site map for !lang (code !code)', array('!lang' => $lang_name, '!code' => $lang_code)),
        '#default_value' => variable_get("site_map_show_menus_$lang_code", array()),
        '#options' => $menu_options,
        '#multiple' => TRUE,
        '#description' => t('Ctrl-click (Windows) or Command-click (Mac) to select more than one value.'),
      );
    }
  }
  else {
    $form['site_map_content']['site_map_show_menus'] = array(
      '#type' => 'select',
      '#title' => t('Menus to include in the site map'),
      '#default_value' => variable_get('site_map_show_menus', array()),
      '#options' => $menu_options,
      '#multiple' => TRUE,
      '#description' => t('Ctrl-click (Windows) or Command-click (Mac) to select more than one value.'),
    );
  }
  $form['site_map_content']['site_map_show_faq'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show FAQ content'),
    '#default_value' => variable_get('site_map_show_faq', 1),
    '#description' => t('When enabled, this option will include the content from the FAQ module in the site map.'),
  );
  $vocab_options = array();
  if (module_exists('taxonomy')) {
    foreach (taxonomy_get_vocabularies() as $vocabulary) {
      $vocab_options[$vocabulary->vid] = $vocabulary->name;
    }
  }
  $form['site_map_content']['site_map_show_vocabularies'] = array(
    '#type' => 'select',
    '#title' => t('Categories to include in the site map'),
    '#default_value' => variable_get('site_map_show_vocabularies', array()),
    '#options' => $vocab_options,
    '#multiple' => TRUE,
    '#description' => t('Ctrl-click (Windows) or Command-click (Mac) to select more than one value.'),
  );
  $form['site_map_content']['site_map_css'] = array(
    '#type' => 'checkbox',
    '#title' => t('Do not include site map CSS file'),
    '#default_value' => variable_get('site_map_css', 0),
    '#description' => t('If you don\'t want to load the included CSS file you can check this box.'),
  );

  $form['site_map_taxonomy_options'] = array(
    '#type' => 'fieldset',
    '#title' => t('Categories settings'),
  );
  $form['site_map_taxonomy_options']['site_map_show_count'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show node counts by categories'),
    '#default_value' => variable_get('site_map_show_count', 1),
    '#description' => t('When enabled, this option will show the number of nodes in each taxonomy term.'),
  );
  $form['site_map_taxonomy_options']['site_map_categories_depth'] = array(
    '#type' => 'textfield',
    '#title' => t('Categories depth'),
    '#default_value' => variable_get('site_map_categories_depth', 'all'),
    '#size' => 3,
    '#maxlength' => 10,
    '#description' => t('Specify how many subcategories should be included on the categorie page. Enter "all" to include all subcategories,"0" to include no subcategories, or "-1" not to append the depth at all.'),
  );
  $form['site_map_taxonomy_options']['site_map_term_threshold'] = array(
    '#type' => 'textfield',
    '#title' => t('Category count threshold'),
    '#default_value' => variable_get('site_map_term_threshold', 0),
    '#size' => 3,
    '#description' => t('Only show categories whose node counts are greater than this threshold. Set to -1 to disable')
  );
  $form['site_map_taxonomy_options']['site_map_forum_threshold'] = array(
    '#type' => 'textfield',
    '#title' => t('Forum count threshold'),
    '#default_value' => variable_get('site_map_forum_threshold', -1),
    '#size' => 3,
    '#description' => t('Only show forums whose node counts are greater than this threshold. Set to -1 to disable')
  );

  $form['site_map_rss_options'] = array(
    '#type' => 'fieldset',
    '#title' => t('RSS settings'),
  );
  $form['site_map_rss_options']['site_map_rss_front'] = array(
    '#type' => 'textfield',
    '#title' => t('RSS feed for front page'),
    '#default_value' => variable_get('site_map_rss_front', 'rss.xml'),
    '#description' => t('The RSS feed for the front page, default is rss.xml.'),
  );
  $form['site_map_rss_options']['site_map_show_rss_links'] = array(
    '#type' => 'select',
    '#title' => t('Include RSS links'),
    '#default_value' => variable_get('site_map_show_rss_links', 1),
    '#options' => array(0 => t('None'), 1 => t('Include on the right side'), 2 => t('Include on the left side')),
    '#description' => t('When enabled, this option will show links to the RSS feeds for each category and blog.'),
  );
  $form['site_map_rss_options']['site_map_rss_depth'] = array(
    '#type' => 'textfield',
    '#title' => t('RSS feed depth'),
    '#default_value' => variable_get('site_map_rss_depth', 'all'),
    '#size' => 3,
    '#maxlength' => 10,
    '#description' => t('Specify how many subcategories should be included in the RSS feed. Enter "all" to include all subcategories or "0" to include no subcategories.'),
  );

  return system_settings_form($form);
}
