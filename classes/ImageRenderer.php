<?php
class ImageRenderer {
  function renderImgByAttachmentId($id, $alt) {
    $srcset_min_size = 'siejmy_640';
    $default_size = 'siejmy_1024';
    $img = wp_get_attachment_image_src($id, $default_size);
    $srcset = wp_get_attachment_image_srcset( $id, $srcset_min_size );

    $w = $img[1];
    $h = $img[2];

    return
          '<amp-img'
         . ' src="' . $img[0] .'" '
         . ' srcset="' . esc_attr( $srcset ).'" '
        . ' alt="' . $alt . '"'
        . ' width="' . $w . '" height="' . $h . '"'
        . ' layout="responsive" noloading>'
        . '</amp-img>'
      ;
  }

  function getImgFallback($id, $w, $h, $alt) {
    $sTime = microtime(true);
    $srcData = $this->getFallbackDataSrc($id);
    if(empty($srcData)) return '<!-- no fallback: no data src fetched -->';
    $timeDebug = '<!-- generating fallback img took ' . (microtime(true) - $sTime) . 'us -->';
    return '<amp-img'
      . ' alt="' . $alt . '"'
      . ' width="' . $w . '"'
      . ' height="' . $h . '"'
      . ' src="' . $srcData . '"'
      . ' class="fallback-img"'
      . 'noloading></amp-img>' . $timeDebug;
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

