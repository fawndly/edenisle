<?php $this->load->view('layout/feature_navigation', array('routes' => $routes, 'active_url' => $active_url, 'core' => 'shops')); ?>
<br />
<div class="well clearfix">
    <form class="pull-left" action="/shops/sellback" style="margin:0;">
        <select name="sort_by" id="sort_by" style="vertical-align:top">
            <option value="none">Sort by...</option>
            <option value="price" <?php echo $this->input->get('sort_by') == 'price' ? 'selected' : '' ?>>Price</option>
            <option value="name" <?php echo $this->input->get('sort_by') == 'name' ? 'selected' : '' ?>>Alphabetically</option>
            <option value="date" <?php echo $this->input->get('sort_by') == 'date' ? 'selected' : '' ?>>Date purchased</option>
        </select>
        <button class="btn">Sort</button>
    </form>
    <div class="pull-right" style="margin-top:6px;">
        <input type="checkbox" id="confirmation_notice"> <label for="confirmation_notice" style="display:inline;">Turn off alert confirmation <i class="icon-warning-sign"></i></label>
    </div>
</div>
<div class="clearfix">
    <br clear="all" />
    <?php foreach ($items as $item): ?>
        <div class="media span5">
            <a class="pull-left" href="#">
                <img src="/images/items/<?php echo $item['thumb'] ?>" alt="">
            </a>
            <div class="media-body">
                <div class="media">
                    <?php echo $item['name'] ?><br />
                    Sells for: <img src="/images/icons/little_Ores.png" alt="" style="margin-top:-4px;"> <?php echo $item['price'] >> 1 ?><br />
                    <form action="/shops/sellback" style="margin-top:2px;" method="POST" class="sell_item_form">
                        <input type="hidden" name="item_id" value="<?php echo $item['item_id'] ?>" />
                        <button type="submit" class="btn btn-small">Sell item</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>
