<?php
class ImageRenderer {
  function renderImageHero($opts) {
    $mediaId = $opts['mediaId'];
    $alt = $opts['alt'];
    $elementId = $opts['elementId'];
    $hrefAttr = isset($opts['href']) ? 'href="' . $opts['href'] . '"' : '';
    $tag = isset($opts['tag']) ? $opts['tag'] : 'a';
    $caption = isset($opts['caption']) ? $opts['caption'] : '';
    $cssClass = isset($opts['cssClass']) ? $opts['cssClass'] : '';
    $rootIdAttr = isset($opts['attachIdToRoot']) && $opts['attachIdToRoot'] == false ? '' : 'id="'. $elementId .'"';

    $bgStyles = '<style>#'. $elementId .' { background-image: url(' . $this->getFallbackDataSrc($mediaId) . '); background-position: center; background-repeat: no-repeat; background-size: cover; display: block; }</style>';
    return
        '<' . $tag . ' class="' . $cssClass . '" ' . $hrefAttr . ' ' . $rootIdAttr . '>'
        . $this->renderImgByAttachmentId($mediaId, $alt, $opts)
        . $caption
        . '</' . $tag . '>'
        . $bgStyles;
  }

  function renderImgByAttachmentId($id, $alt, $opts = array()) {
    $showSrcset = isset($opts['showSrcset']) ? $opts['showSrcset'] : true;
    $srcset_min_size = isset($opts['srcset_min_size']) ? $opts['srcset_min_size'] : 'siejmy_640';
    $default_size = isset($opts['default_size']) ? $opts['default_size'] : 'siejmy_1024';
    $attrs = isset($opts['attrs']) ? $opts['attrs'] : '';
    $fallbackContent = isset($opts['fallbackContent']) ? $opts['fallbackContent'] : '';

    $img = wp_get_attachment_image_src($id, $default_size);
    if(empty($img)) {
      return '';
    }
    $srcset = wp_get_attachment_image_srcset( $id, $srcset_min_size );

    $srcW = $img[1];
    $srcH = $img[2];
    $w = isset($opts['width']) ? $opts['width'] : $srcW;
    $h = isset($opts['height']) ? $opts['height'] : $srcH;
    $layout = isset($opts['layout']) ? $opts['layout'] : 'responsive';
    $mediaAttr = isset($opts['media']) ? 'media="' . $opts['media'] . '"' : '';

    if(isset($opts['layoutMode']) && $opts['layoutMode'] == 'auto-width') {
      $w = $srcW/$srcH*$h;
    }

    return
          '<amp-img'
        . ' src="' . $img[0] .'" '
        . ($showSrcset ? ' srcset="' . esc_attr( $srcset ) . '" ' : '')
        . ' alt="' . $alt . '"'
        . ' width="' . $w . '" height="' . $h . '"'
        . ' layout="' . $layout . '" noloading'
        . ' ' . $attrs
        . ' ' . $mediaAttr . '>'
        . $fallbackContent
        . '</amp-img>'
      ;
  }

  function getFallbackDataSrc($id) {
    $blurryLocalPath = $this->getBlurryImgLocalPath($id);
    if(empty($blurryLocalPath)) return '<!-- no fallback: no blurry local path -->';
    $optimalImgBlob = $this->getImageAsOptimalPng($blurryLocalPath);
    if(empty($optimalImgBlob)) return '<!-- no fallback: no optimal png blob -->';
    return $this->blobToBase64($optimalImgBlob, 'image/png');
  }

  function getBlurryImgLocalPath($id) {
    $blurry_size = 'siejmy_blurry';
    $attachedFileLocalPath = get_attached_file($id);
    if(empty($attachedFileLocalPath)) return '';
    $uploadDir = dirname($attachedFileLocalPath);
    $metadata = wp_get_attachment_metadata($id);
    if(empty($metadata)) return '';
    if(!isset($metadata['sizes'][$blurry_size])) return '';
    $blurrySizeMeta = $metadata['sizes'][$blurry_size];
    if(empty($blurrySizeMeta)) return '';
    $blurryFilename = $blurrySizeMeta['file'];
    if(empty($blurryFilename)) return '';
    return $uploadDir . '/' . $blurryFilename;
  }

  function getImageAsOptimalPng($path) {
    if(!file_exists($path)) return '';
    $img = new Imagick(realpath($path));
    $profiles = $img->getImageProfiles("icc", true);
    $img->stripImage();

    if(!empty($profiles)) {
       $img->profileImage("icc", $profiles['icc']);
    }
    $img->setImageFormat('png');
    $img->setImageCompressionQuality(9);
    $blob = $img->getImageBlob();
    $img->destroy();
    return $blob;
  }

  function blobToBase64($blob, $type) {
    return 'data:' . $type . ';base64,' . base64_encode($blob);
  }
}

