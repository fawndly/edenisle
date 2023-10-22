<?php $this->load->view('market/navigation', array('routes' => $routes, 'active_url' => $active_url)); ?>

<style type="text/css">
    #inventory_items { max-height: 400px; overflow-y: scroll; }
    .selected_item {
        background: #ff0;
        outline: 4px solid #ff0;
    }
    .nav-tabs li a { font-size: 12px; padding: 6px 8px; }
    .def_structure { overflow:hidden }
    .def_structure div.def_content {
        overflow:hidden;
        padding:10px 0;
        border-bottom:1px solid #ddd;
    }

    .def_structure div h3 {
        float:left;
        width:170px;
        font-weight:normal;
        font-size:16px;
        color:#888;
        padding:3px 0 0 20px;
        font-family:'Nunito', "Lucida Grande", "arial", sans-serif;
    }
    div.def_content > div {
        width:536px;
        float:left;
    }
    div.def_content input.button_sync {
        font-size:16px;
        padding:7px 8px;
        vertical-align:top;
        width:270px;
    }
    .push_up { padding-top:5px; }
    .bare { margin:0; padding:0; }
    .tab-pane a { display: inline-block; }
</style>

<div class="def_structure">
    <div class="def_content">
        <h3>Choose an item</h3>
        <div class="clearfix">
            <?php if (count($inventory) > 0): ?>
            <ul class="nav nav-tabs" id="avatar_items">
                <?php foreach ($inventory as $tab_name => $tab_array): ?>
                    <?php if (strtolower($tab_name) == 'bugs'): ?>
                        <li><a href="#<?= strtolower($tab_name) ?>"><i class="icon-leaf"></i> <?= $tab_name ?></a></li>
                    <?php else: ?>
                        <li><a href="#<?= strtolower($tab_name) ?>"><?= $tab_name ?></a></li>
                    <?php endif ?>
                <?php endforeach ?>
            </ul>

            <div class="tab-content" id="inventory_items">
                <?php foreach ($inventory as $tab_name => $tab_array): ?>
                    <div class="tab-pane" id="<?= strtolower($tab_name) ?>">
                        <?php foreach ($tab_array as $item): ?>
                            <?php if ($item['amount'] > 1): ?>
                                <?php while ($item['amount']--): ?>
                                    <?= $item['element'] ?>
                                <?php endwhile ?>
                            <?php else: ?>
                                <?= $item['element'] ?>
                            <?php endif ?>
                        <?php endforeach ?>
                    </div>
                <?php endforeach ?>
            </div>
        
            <?php else: ?>
                <div class="well">You have no inventory</div>
            <?php endif ?>
        </div>
    </div>

    <div class="def_content hide" id="chosen_item">
        <h3>Chosen item</h3>
        <div class="push_up">
            <img src="http://127.0.0.1:8888/images/items/c59da3b60624344c0ec1dad1eea6707a.png" id="item-image" width="60" height="60" />
            &nbsp;<strong id="item-name">Item name</strong>
        </div>
    </div>
        
    <div class="def_content">
        <h3>Listing details</h3>
        <div class="push_up">
            <form action="/<?=$this->uri->rsegment(1, 0)?>/post_sell" autocomplete="off" class="bare" method="POST">
                <p>
                    <label>Listing type:</label>
                    <select name="listing_type" id="listing_type">
                        <option value="buynow" selected>Buy now</option>
                        <option value="bid">Bid</option>
                    </select>
                </p>
                <div class="listing-settings buynow-settings">
                </div>
                <div class="listing-settings bid-settings hide">
                    <p>
                        <label>Minimum bid increase:</label>
                        <input type="text" class="button_sync" name="min_bid_increase" placeholder="10" />
                    </p>
                    <p>
                        <label>Bid duration:</label>
                        <select name="bid_duration" id="bid_duration">
                            <?php foreach ($bid_time_options_in_mins as $label => $time): ?>
                                <option value="<?php echo $label ?>"><?php echo $label ?></option>
                            <?php endforeach ?>
                        </select>
                    </p>
                </div>
                <p>
                    <label>Listing price (in ores):</label>
                    <input type="text" class="button_sync" name="ore_price" placeholder="450" />
                </p>
                <input type="hidden" id="market_item_key" name="item_id" />
                <button type="submit" class="main_button" id="create_trade_btn" autocomplete="off" data-toggle="button" data-loading-text="Off it goes...">Put item for sale</button>
            </form>
        </div>
    </div>

</div>