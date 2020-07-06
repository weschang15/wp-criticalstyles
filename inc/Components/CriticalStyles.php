<?php

namespace CriticalStyles\Plugin\Components;

use WP_Post;
use CriticalStyles\Plugin\Classes\Plugin;
use CriticalStyles\Plugin\Meta\MetaBuilder;
use CriticalStyles\Plugin\Classes\Component;

/**
 * CriticalStyles class
 *
 * Object responsible for containing and handling of Critical CSS. This class will allow admins to insert CCSS for specific pages via metabox,
 * and automatically enqueue said CCSS via Autoptimize filter hooks. This class will also allow us to integrate with criticalstyles.com in the near
 * future.
 *
 * @author Wesley Chang
 * @version 1.0.0
 * @since 1.5.2
 */
class CriticalStyles implements Component
{
  /**
   * Option name for post meta that contains CCSS details
   *
   * @var string
   */
  const CRITICAL_STYLES = '_criticalstyles';

  const NONCE_ACTION = 'criticalstyles_save';

  const NONCE_NAME = 'criticalstyles_nonce';

  public function register(): void
  {
    add_filter('autoptimize_filter_css_defer_inline', [$this, 'inline_ccss']);
    // Action responsible for creating new metabox
    add_action('add_meta_boxes', [$this, 'fields'], 10, 2);
    add_action('save_post', [$this, 'save'], 10, 2);
  }

  public function fields(string $post_type = null, WP_Post $post = null): void
  {
    if (\is_null($post)) {
      return;
    }

    // If user doesn't have unfiltered html capability, don't show this box.
    if (!\current_user_can('unfiltered_html')) {
      return;
    }

    \add_meta_box('criticalstyles', 'Critical Styles', [$this, 'render']);
  }

  public function render(WP_Post $post = null)
  {
    $metaBuilder = new MetaBuilder();

    $data = $metaBuilder
      ->select()
      ->setPostId($post->ID)
      ->meta(self::CRITICAL_STYLES);

    Plugin::partial('templates/criticalstyles-metabox.php', [
      'data' => $data,
      'action' => self::NONCE_ACTION,
      'name' => self::NONCE_NAME
    ]);
  }

  public function save(int $post_id = null, WP_Post $post = null): void
  {
    if (!isset($_POST['criticalstyles'])) {
      return;
    }

    // If user doesn't have unfiltered html capability, don't try to save.
    if (
      !\current_user_can('unfiltered_html') ||
      !\current_user_can('edit_post', $post_id)
    ) {
      return;
    }

    // Verify the nonce.
    if (
      !isset($_POST[self::NONCE_NAME]) ||
      !\wp_verify_nonce($_POST[self::NONCE_NAME], self::NONCE_ACTION)
    ) {
      return;
    }

    // Don't try to save the data under autosave, ajax, or future post.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
    }

    if (\wp_is_post_revision($post)) {
      return;
    }

    // Merge user submitted options with fallback defaults.
    $next = \wp_parse_args($_POST['criticalstyles'], [
      '_styles' => null,
      '_updated_at' => \current_time('M d, Y H:i:s')
    ]);

    $metaBuilder = new MetaBuilder();

    $select = $metaBuilder
      ->select()
      ->setPostId($post_id)
      ->meta(self::CRITICAL_STYLES);

    // Updated only if styles value is either not present or different from previous
    if (empty($select) || $select['_styles'] !== $next['_styles']) {
      $metaBuilder
        ->upsert()
        ->setPostId($post_id)
        ->meta(self::CRITICAL_STYLES, $next)
        ->save();
    }
  }

  public function inline_ccss(string $default = null)
  {
    $metaBuilder = new MetaBuilder();

    $meta = $metaBuilder
      ->select()
      ->setPostId(get_the_ID())
      ->meta(self::CRITICAL_STYLES);

    return $meta['_styles'] ?: $default;
  }
}