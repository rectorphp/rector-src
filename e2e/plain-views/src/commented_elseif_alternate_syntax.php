<div class="price-box">
    <?php if (($_min = $this->getMinAmount()) && ($_max = $this->getMaxAmount()) && ($_min == $_max)): ?>
        <span class="price" id="product-price-<?php echo $_id ?><?php echo $this->getIdSuffix() ?>">
            <?php echo Mage::helper('core')->currency($_min, true, false) ?>
        </span>
    <?php elseif (($_min = $this->getMinAmount()) && $_min != 0): ?>
        <span class="label"><?php echo Mage::helper('brainvire_giftcard')->__('From') ?></span>
        <span class="price" id="min-product-price-<?php echo $_id ?><?php echo $this->getIdSuffix() ?>">
            <?php echo Mage::helper('core')->currency($_min,true,false) ?>
        </span>
    <?php /*elseif ($_max = $this->getMaxAmount()): ?>
        <span class="label"><?php echo Mage::helper('brainvire_giftcard')->__('Up To') ?></span>
        <span class="price" id="max-product-price-<?php echo $_id ?><?php echo $this->getIdSuffix() ?>">
            <?php echo Mage::helper('core')->currency($_max,true,false) ?>
        </span>
    <?php */endif; ?>
</div>