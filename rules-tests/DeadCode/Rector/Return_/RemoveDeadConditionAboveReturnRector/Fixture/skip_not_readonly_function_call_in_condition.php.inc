<?php
$Entity = new Entity();
saveMyEntity($Entity);

function saveMyEntity($Entity) {
  if($Entity->save()) {
    return true;
  }

  return true;
}
