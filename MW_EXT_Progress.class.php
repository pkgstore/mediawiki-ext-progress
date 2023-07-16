<?php

namespace MediaWiki\Extension\PkgStore;

use MWException;
use OutputPage, Parser, Skin;

/**
 * Class MW_EXT_Progress
 */
class MW_EXT_Progress
{
  /**
   * Register tag function.
   *
   * @param Parser $parser
   *
   * @return void
   * @throws MWException
   */
  public static function onParserFirstCallInit(Parser $parser): void
  {
    $parser->setFunctionHook('progress', [__CLASS__, 'onRenderTag']);
  }

  /**
   * Render tag function.
   *
   * @param Parser $parser
   * @param string $value
   * @param string $max
   * @param string $width
   *
   * @return string|null
   */
  public static function onRenderTag(Parser $parser, string $value = '', string $max = '', string $width = ''): ?string
  {
    // Argument: value.
    $getValue = MW_EXT_Kernel::outClear($value ?? '' ?: '');
    $outValue = $getValue;

    // Argument: max.
    $getMax = MW_EXT_Kernel::outClear($max ?? '' ?: '');
    $outMax = $getMax;

    // Argument: width.
    $getWidth = MW_EXT_Kernel::outClear($width ?? '' ?: '50');
    $outWidth = $getWidth;

    // Check progress value, set error category.
    if (!ctype_digit($getValue) || !ctype_digit($getMax) || !ctype_digit($getWidth) || $getValue > $getMax) {
      $parser->addTrackingCategory('mw-progress-error-category');

      return null;
    }

    // Set progress status.
    if ($getValue > 0 && $getValue <= 33) {
      $outStatus = '00';
    } elseif ($getValue > 33 && $getValue <= 99) {
      $outStatus = '01';
    } elseif ($getValue == 100) {
      $outStatus = '02';
    } else {
      $outStatus = '';
    }

    // Out HTML.
    $outHTML = '<div style="width: ' . $outWidth . '%;" class="mw-progress navigation-not-searchable"><div class="mw-progress-body">';
    $outHTML .= '<div class="mw-progress-count mw-progress-count-status-' . $outStatus . '">' . $outValue . '%</div>';
    $outHTML .= '<div class="mw-progress-content">';
    $outHTML .= '<progress class="mw-progress-bar mw-progress-bar-status-' . $outStatus . '" value="' . $outValue . '" max="' . $outMax . '"></progress>';
    $outHTML .= '</div></div></div>';

    // Out parser.
    return $parser->insertStripItem($outHTML, $parser->getStripState());
  }

  /**
   * Load resource function.
   *
   * @param OutputPage $out
   * @param Skin $skin
   *
   * @return void
   */
  public static function onBeforePageDisplay(OutputPage $out, Skin $skin): void
  {
    $out->addModuleStyles(['ext.mw.progress.styles']);
  }
}
