<?php

namespace Rector\Core\Tests\Issues\IndentationCrash\Fixture;

class R extends Reports {

  public function search(&$rates, $type, $cpt) {
    for ($i = 0; $i < sizeof($rates); $i++) {
      if ($rates[$i]["code"] == $cpt) {
        switch ($type) {
          case "md": return $rates[$i]["md_amt"];
          case "np": return $rates[$i]["np_amt"];
          case "phd": return $rates[$i]["phd_amt"];
          case "ma": return $rates[$i]["ma_amt"];
          default: return 0.0;
        }
      }
    }

    return 0.0;
  }

}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\IndentationCrash\Fixture;

class R extends Reports {

  public function search(&$rates, $type, $cpt) {
    $ratesCount = count($rates);
    for ($i = 0; $i < $ratesCount; $i++) {
      if ($rates[$i]["code"] == $cpt) {
        switch ($type) {
          case "md": return $rates[$i]["md_amt"];
          case "np": return $rates[$i]["np_amt"];
          case "phd": return $rates[$i]["phd_amt"];
          case "ma": return $rates[$i]["ma_amt"];
          default: return 0.0;
        }
      }
    }

    return 0.0;
  }

}

?>
