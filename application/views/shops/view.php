<?php
    $this->load->view('layout/feature_navigation', array('routes' => $routes, 'active_url' => $active_url, 'core' => 'shops'));
?>
<?php if ($this->session->flashdata('success')): ?>
    <?= notice('It\'s yours!', $this->session->flashdata('success'), 'success') ?>
<?php endif ?>
<style type="text/css">
    .item_thumbnail{
        position: relative;
        display: inline-block;
        width:64px;
        border:1px solid #0a5cc7;
        text-align:center;
        margin:3px 4px;
        border-radius:4px;
        font-size:14px;
        padding-bottom: 12px;
    }
    
    .item_thumbnail:hover{
        border-color: orange;
        text-decoration: none;      
    }
    
    .inner-info {
        padding: 4px;
    }
    
    .sapphire_price {
        position: absolute;
        bottom: 0;
        background: #0a5cc7;
        text-align: center;
        color: #fff;
        width: 100%;
    }
    
    .item_thumbnail:hover .sapphire_price{
        background: orange;
    }
</style>

<?php if ($this->input->get('purchase')): ?>
    <?php $this->load->view('partials/notices/success', array('header' => 'Your item has been purchased', 'data' => 'You should go to your inventory and try out your new item!')) ?>
<?php endif ?>

<div class="clearfix" style="margin:10px 0;">
    <?php if ($shop_items) : ?>
        <?php foreach ($shop_items as $item): ?>
        <a href="<?= '/shops/view_item/'.$item['shop_item_id'] ?>" class="item_thumbnail" title="<?= $item['name'] ?>">
            <div class="inner-info">
                <img src="/images/items/<?= $item['thumb'] ?>" alt="" />
                <img src="/images/icons/little_Ores.png" style="margin-top:-2px;" width="14" height="14" /> <?= number_format($item['price']) ?>
                <?php if ($item['second_price']): ?>
                    <br/>
                    <img src="/images/icons/bog.png" style="margin-top:-2px;" width="14" height="14" /> <?= number_format($item['second_price']) ?>
                <?php endif ?>
                <?php if ($item['third_price']): ?>
                    <br/>
                    <img src="<?= $this->config->item('special_currency')['image'] ?>" style="margin-top:-2px;" width="14" height="14" /> <?= number_format($item['third_price']) ?>
                <?php endif ?>
            </div>
            <?php if ($item['sapphire_price']): ?>
                <div class="sapphire_price">
                    <img src="/images/icons/sapphire.png" style="margin-top:-2px;" width="14" height="14" /> <?= number_format($item['sapphire_price']) ?>
                </div>
            <?php endif ?>
        </a>

        <?php endforeach ?>
        <div class="clearfix"></div>
        <hr/>
        <span><?= $this->pagination->create_links(); ?></span>
    <?php else : ?>
        <?php //if ($shop_data['shop_id'] == 11) : //temporary solution :) ?>
            <div class="npc-dialog">
                <div class="npc-image">
                    <img src="/images/npc/npc_silhouette.png"/>
                </div>
                <div class="npc-speechbaloon">
                    <span class="npc-name"><?= ucfirst($shop_data['shop_keeper']) ?>:</span>
                    <p>Hello there <strong><?= ($username) ? $username : 'stranger' ?></strong>!</p>
                    <p>I'm really sorry but <i>"<?= $shop_data['shop_name'] ?>"</i> is closed for now...</p>
                    <p>Why don't you return some other time maybe?</p>
                </div>
            </div>
        <?php //endif ?>
    <?php endif ?>
</div>

