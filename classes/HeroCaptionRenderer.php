<?php
require_once(dirname(__FILE__) . '/ImageRenderer.php');
require_once(dirname(__FILE__) . '/TimeAgoRengerer.php');

class HeroCaptionRenderer {
  function render($post, $opts = array()) {
    $permalink = get_permalink($post);
    $mediaId     = get_post_thumbnail_id($post);
    $alt = $post->post_title;
    $titleTag = isset($opts['titleTag']) ? $opts['titleTag'] : 'h3';

    return '
        <div class="herocaption">
          ' . $this->renderTag($post) . '
          <' . $titleTag . ' class="title">' . $post->post_title . '</' . $titleTag . '>
          <div class="subline">
            <span class="author">' . $this->getAuthorName($post) . '</span>
            ' . TimeAgoRengerer::getTimeAgoKatoPL($post, ' &nbsp;◉') . '
          </div>
        </div>';
  }

  function renderTag($post) {
    $mainCategoryName = $this->getMainCategoryName($post);
    if(empty($mainCategoryName)) {
      return '';
    }
    return '<div class="tag">' . $mainCategoryName . '</div>';
  }

  function getMainCategoryName($post) {
    $categories = get_the_category($post->ID);
    if(count($categories) == 0) {
      return '';
    }
    return $categories[0]->name;
  }

  function getAuthorName($post) {
    return get_the_author_meta('display_name', $post->post_author);
  }
}
