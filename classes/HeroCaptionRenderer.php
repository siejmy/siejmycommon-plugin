<?php
require_once(dirname(__FILE__) . '/ImageRenderer.php');
require_once(dirname(__FILE__) . '/TimeAgoRenderer.php');

class HeroCaptionRenderer {
  function render($post, $opts = array()) {
    $permalink = get_permalink($post);
    $mediaId     = get_post_thumbnail_id($post);
    $alt = $post->post_title;
    $titleTag = isset($opts['titleTag']) ? $opts['titleTag'] : 'h2';

    return '
        <div class="herocaption">
          ' . $this->renderTag($post) . '
          <' . $titleTag . ' class="title">' . $this->renderFitTitle($post->post_title) . '</' . $titleTag . '>'
           . TimeAgoRenderer::getTimeAgoKatoPL($post, array(
              'prepend' => '<span class="author" rel="author">' . $this->getAuthorName($post) . '</span>',
              'cssClass' => 'subline',
            ))
        . '</div>';
  }

  function renderTag($post) {
    $mainCategoryName = $this->getMainCategoryName($post);
    if(empty($mainCategoryName)) {
      return '';
    }
    return '<div class="tag">' . $mainCategoryName . '</div>';
  }

  function renderFitTitle($text) {
    return '<amp-fit-text layout="fill" min-font-size="20" max-font-size="500">'
            . $text
         . '</amp-fit-text>';
  }

  function getMainCategoryName($post) {
    $categories = get_the_category($post->ID);
    if(count($categories) == 0) {
      return '';
    }
    return $categories[0]->name;
  }

  function getAuthorName($post) {
    $guest_author = get_post_meta($post->ID, 'guest_author', true);
    if(!empty($guest_author)) {
      return $guest_author;
    }
    return get_the_author_meta('display_name', $post->post_author);
  }
}
