<script type="text/javascript">
    var RealtimeSetup = {
        userId: "<?php echo $this->system->userdata['user_id'] ?>",
        host: "<?php echo $realtime_setup['host'] ?>",
        port: "<?php echo $realtime_setup['port'] ?>",
        token: "<?php echo $realtime_setup['token'] ?>",
        channel: "<?php echo $realtime_setup['channel'] ?>"
    }
</script>
<?php $this->load->view('market/navigation', array('routes' => $routes, 'active_url' => $active_url)); ?>
<style type="text/css">
    .listing-desc { font-size:13px; color:#000; line-height:1.8; }
</style>

<?php if ($this->input->get('just_missed_it')): ?>
    <?= notice('You were just a second late!', 'A more fortunate user managed to purchase that item before you.', 'notice') ?>
<?php endif ?>

<?php if ($this->session->flashdata('success')): ?>
    <?= notice('It\'s yours!', $this->session->flashdata('success'), 'success') ?>
<?php endif ?>

<form method="GET" class="form-inline" style="margin:15px 5px;">
    <?php echo form_input('search[item_name]', $this->input->get('search')['item_name'], 'placeholder="Search by item name"') ?>
    <?php echo form_dropdown('search[offer_type]', [
        NULL => "Any listing type",
        "buynow" => "Buy now only",
        "bid" => "Bid only",
        "previously_bid" => "Previously bid",
    ], $this->input->get('search')['offer_type']); ?>

    <button class="btn">Search</button>
</form>

<table cellpadding="0" cellspacing="0" class="clean" id="market_listing">
    <thead>
        <tr>
            <th>Listing</th>
            <th width="140">Actions</th>
        </tr>
    </thead>
    <?php foreach($items as $item_offer): ?>
        <tr class="<?=cycle('', 'alt')?>" id="offer_<?php echo $item_offer['id'] ?>">
            <td class="selectable">
                <img src="/images/items/<?= $item_offer['item_thumbnail'] ?>" alt="" style="float:left; width:42px;height:42px;margin:-5px 5px 0 -6px;">
                <div class="listing-desc">
                <?php if($item_offer['sell_type'] == 'bid' && $item_offer['last_bidder_id'] == $this->session->user_id): ?>
                    <span class="label label-warning">Your bid!</span>
                <?php endif ?>                  

                <?= $item_offer['item_name'] ?> for 
                <?php if ($item_offer['ore_price']): ?>
                    <span class="total-price"><?php echo $item_offer['ore_price'] ?></span> ores
                <?php endif ?>
                <?php if ($item_offer['bid_increment']): ?>
                    / +<span class="min_bid_increment"><?php echo $item_offer['bid_increment'] ?></span> min bid
                <?php endif ?>
                </div>
                by <img src="/images/avatars/<?= $item_offer['user_id'] ?>_headshot.png" alt="" style="width:18px;height:18px;margin:-4px 0 0 0;"><?= anchor("/user/".urlencode($item_offer['username']), $item_offer['username']) ?>

                <?php if($item_offer['sell_type'] == 'bid'): ?>
                    &bull; 
                    <span class="time-left-for-bid" data-countdown-from="<?=(strtotime($item_offer['finishes_at'])-time())?>">
                        <?php echo $item_offer['finishes_at'] ?>
                    </span>
                    <?php if ($item_offer['last_bidder_id']): ?>
                        &bull; <span class="last_bid_by">Last bid by <img src="/images/avatars/<?= $item_offer['last_bidder_id'] ?>_headshot.png" alt="" style="width:18px;height:18px;margin:-4px 0 0 0;"><?= anchor("/user/".urlencode($item_offer['bidder_username']), $item_offer['bidder_username']) ?></span>
                    <?php endif ?>
                <?php endif ?>                  
            </td>
            <td>
                <?php if ($item_offer['cancled']): ?>
                    Listing canceled
                <?php elseif ($item_offer['purchased']): ?>
                    Paid <?php echo $item_offer['ore_price'] ?> ores<br />
                    <a href="<?php echo '/user/'.strtolower($item_offer['purchaser_username']) ?>">
                        <img src="/images/avatars/<?= $item_offer['purchased_by'] ?>_headshot.png" alt="" style="width:18px;height:18px;margin:-4px 0 0 0;"> <?php echo $item_offer['purchaser_username'] ?>
                    </a>
                <?php elseif ($item_offer['user_id'] == $this->session->user_id): ?>
                    <?php if($item_offer['sell_type'] == 'buynow'): ?>
                        <form action="/<?=$this->uri->rsegment(1, 0)?>/retract_item" autocomplete="off" class="bare" method="POST">
                            <input type="hidden" value="<?=$item_offer['id']?>" name="offer_id" />
                            <button type="submit" class="btn btn-small" data-toggle="button" data-loading-text="Removing...">Retract item</button>
                        </form>
                    <?php elseif($item_offer['sell_type'] == 'bid'): ?>
                        <button type="submit" class="btn btn-small disabled" data-toggle="button">Unable to retract</button>
                    <?php endif ?>                  
                <?php elseif($item_offer['sell_type'] == 'buynow'): ?>
                    <form action="/<?=$this->uri->rsegment(1, 0)?>/purchase_item" autocomplete="off" class="bare" method="POST">
                        <input type="hidden" value="<?=$item_offer['id']?>" name="offer_id" />
                        <button type="submit" class="btn btn-primary btn-small" data-toggle="button" data-loading-text="Buying..." />Buy item</button>
                    </form>
                <?php elseif($item_offer['sell_type'] == 'bid'): ?>
                    <form action="/<?=$this->uri->rsegment(1, 0)?>/bid_on_item" autocomplete="off" class="bare form-inline bid-form" method="POST">
                        <input type="hidden" value="<?=$item_offer['id']?>" name="offer_id" />
                        <input type="text" class="input-mini new_bid_offer" value="<?=$item_offer['ore_price'] + $item_offer['bid_increment'] ?>" name="offer_price" />
                        <button type="submit" class="btn btn-warning btn-small" data-toggle="button" data-loading-text="...">Bid</button>
                    </form>
                <?php endif ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<div class="row">
    <span class="left large breath"><?= $this->pagination->create_links();?></span>
</div>
