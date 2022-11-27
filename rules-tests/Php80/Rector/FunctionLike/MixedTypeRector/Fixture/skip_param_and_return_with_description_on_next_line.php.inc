<?php

declare(strict_types = 1);

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

use \Drupal\Core\Url;

final class ParamTest {

  /**
   * A dummy method to test rector param removal.
   *
   * @param \Drupal\Core\Url $url
   *   A URL object.
   *
   * @return string
   *   A fixed string.
   */
  public function dummy(Url $url): string {
    return 'dummy';
  }

}

?>

