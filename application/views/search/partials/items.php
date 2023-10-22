<!-- Crysandrea engine by Tyler Diaz (https://github.com/Pixeltweak/Crysandrea) --><!-- overflow: hidden; -->
<div class="search_item">
    <!-- float: left; -->
    

    <!-- float: right -->
    <div class="item_info">
        <h4><?= $name ?></h4>
        <span>Buy from <a href="/shops/<?= $shop_id ?>">shops</a></span>
        <?php if (!empty($marketplace_item_id)): ?>
        <span>Available in <a href="/market/<?= $marketplace_item_id ?>">marketplace</a></span>
        <?php endif; ?>
        
    </div>
</div>
