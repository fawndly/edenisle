<div class="feature_header">
<h2 class="market_icon"><?php echo (isset($navigation_header) ? $navigation_header : $page_title) ?> <span class="label label-warning">Beta</span></h2>
    <?php if ($this->session->userdata('user_id')): ?>
        <ul class="feature_navigation">
            <?php foreach ($routes as $url => $data): ?>
            <li class="<?= ($active_url === $url ? 'active' : '') ?>">
              <a href="/<?=$this->uri->rsegment(1, 0)?>/<?= $url ?>"><?= $data ?></a>
            </li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
</div>