<?php
    #Crysandrea engine by Tyler Diaz (https://github.com/Pixeltweak/Crysandrea)
?>

<h3 style="text-align:center; font-size:17px; color:#444; margin:16px 5px 20px;">
    <img src="<?= $routes[$active_url]['icon'] ?>" alt="" width="24" height="24" style="margin-top:-2px"/>
    <?= $routes[$active_url]['title'] ?> &nbsp;<span style="font-weight:normal; color:#888; font-size:13px;"><?= $routes[$active_url]['description'] ?></span>
</h3>

<ul class="nav nav-tabs">
    <?php foreach ($routes as $url => $data): ?>
        <li class="<?= ($active_url === $url ? 'active' : '') . ' ' . ($data['enabled'] ? '' : 'disabled') ?>">
            <a href="<?= (($data['enabled']) ? "/account/{$url}" : '#') ?>" title="<?= ($data['enabled'] ? $data['description'] : 'This feature is under construction') ?>"><?= $data['title'] ?></a>
        </li>
    <?php endforeach ?>
</ul>
