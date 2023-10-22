 <!-- Crysandrea engine by Tyler Diaz (https://github.com/Pixeltweak/Crysandrea) --><div class="feature_header">
    <h2 class="friends_icon"><?php echo $page_title ?></h2>
    <ul class="feature_navigation">
        <?php foreach ($routes as $url => $data): ?>
        <li class="<?php echo ($active_url === $url ? 'active' : '') ?>">
            <a href="/games/<?php echo $url ?>"><?php echo $data ?></a>
        </li>
        <?php endforeach ?>
    </ul>
</div>
