<?php $this->load->view('layout/feature_navigation', array('routes' => $routes, 'active_url' => $active_url, 'core' => 'shops')); ?>

<?php if ($this->input->get('purchase')): ?>
    <?php $this->load->view('partials/notices/success', array('header' => 'Your item has been purchased', 'data' => 'You should go to your inventory and try out your new item!')) ?>
<?php endif ?>
<?php $err = $this->input->get('error'); if ($err && $err == 2): ?>
    <?php $this->load->view('partials/notices/error', array('data' => 'You don\'t have enough sapphires.')) ?>
<?php endif ?>

<div>
    <img src="/avatar/preview_item/<?= $item_data['item_id'] ?>" alt="" style="float:left;" />
    <div style="overflow:hidden; padding:10px 5px 5px;">
        <img src="/images/items/<?= $item_data['thumb'] ?>" alt="" style="float:left; margin-right:10px;" /><br />
        <div style="overflow:hidden; float:left; width:300px;">
            <strong style="line-height:1.6; font-size:15px;"><?= $item_data['name'] ?></strong><br />
            Price: <strong>
                <?php if ($item_data['price'] > 0): ?>
                    <?= $item_data['price']." ".$item_data['item_currency'].
                    ($item_data['insect_id'] > 0 || $item_data['third_price'] > 0 ? "," : "") ?>
                <?php endif ?>
                <?php if ($item_data['insect_id'] > 0): ?>
                    <?= $item_data['second_price']." ".$item_data['insect_name'].
                    ($item_data['third_price'] > 0 ? "," : "") ?>
                <?php endif ?>
                <?php if ($item_data['third_price'] > 0): ?>
                     <?= $item_data['third_price']." ".
                        ( $item_data['third_price'] == 1 ? $this->config->item('special_currency')['name']['singular'] : $this->config->item('special_currency')['name']['plural'] )?>
                <?php endif ?>
            </strong>
        </div>
    </div>
    <?php if ($owns_item): ?>
        <small>You already own this item, would you like to purchase another?</small>
    <?php endif ?>
    <div style="margin:10px 0;">
        <form action="/shops/purchase_item/" method="POST" style="display: inline">
            <input type="hidden" name="item_id" value="<?= $item_data['shop_item_id'] ?>" />
            <button type="submit" class="main_button">Purchase item</button>
        </form>
        <?php if ($item_data['item_parent'] == 0 && $item_data['sapphire_price'] == 0 && $item_data['second_price'] == 0 && $item_data['third_price'] == 0): ?>
            <form action="/shops/purchase_item_family/" method="POST" style="display: inline">
                or 
                <input type="hidden" name="item_id" value="<?= $item_data['shop_item_id'] ?>" />
                <button type="submit" class="btn btn-warning">Purchase all color variations for <?=number_format(count($children) * $item_data['price'] )?> ores</button>
            </form>
        <?php endif ?>
        <?php if($item_data['sapphire_price']): ?>
        <form action="/shops/purchase_item_sapphire/" method="POST">
            <input type="hidden" name="item_id" value="<?= $item_data['shop_item_id'] ?>" />
            <button type="submit" class="main_button">
                <img src="/images/icons/sapphire.png" style="margin-top:-2px;" width="14" height="14" /> Purchase for <?=$item_data['sapphire_price']?> Sapphires
            </button>
        </form>
        <?php endif; ?>
    </div>
    <?php if ($this->system->is_staff()): ?>
        <br />
        <h3 style="color:#999; font-weight:normal; font-size:13px;">Staff data</h3>
        <strong class="label label-info">Item_id:</strong> <?= $item_data['item_id'] ?>
    <?php endif ?>
    <?php if (count($children) > 0): ?>
        <br />
        <h3 style="color:#999; font-weight:normal; font-size:13px;">Also avaliable in...</h3>
        <div style="overflow:hidden">
            <?php foreach ($children as $item): ?>
                <a href="/shops/view_item/<?= $item['shop_item_id'] ?>"><img src="/images/items/<?= $item['thumb'] ?>" title="<?= $item['name'] ?>" /></a>
            <?php endforeach ?>
        </div>
    <?php endif ?>
</div>
