<?php
/**
 * Author: Wesley Chang
 */
namespace CriticalStyles\Plugin\Meta;

use CriticalStyles\Plugin\Meta\Operations\Insert;
use CriticalStyles\Plugin\Meta\Operations\Select;
use CriticalStyles\Plugin\Meta\Operations\Upsert;

/**
 * MetaFactory - class responsible for generating appropriate operation handlers
 */
final class MetaFactory
{
  /**
   * Factory function used to return a fresh instance of our Select operation handler
   *
   * @return Select
   */
  public static function createSelect()
  {
    return new Select();
  }

  /**
   * Factory function used to return a fresh instance of our Insert operation handler
   *
   * @return Insert
   */
  public static function createInsert()
  {
    return new Insert();
  }

  /**
   * Factory function used to return a fresh instance of our Upsert operation handler
   *
   * @return Upsert
   */
  public static function createUpsert()
  {
    return new Upsert();
  }
}