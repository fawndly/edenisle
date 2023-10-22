<?php $this->load->view('games/games_navigation', array('routes' => $routes, 'active_url' => $active_url)); ?>
<style>
.block{text-align:center;padding:0 0 10px;}
imageblock{padding:25px}
</style>
<div class="block">
<a href="../forest"><img src="../../../global/css/images/elements/sample_forest.png" class="imageblock"  /> </a>
<a href="../mine"><img src="../../../global/css/images/elements/sample_mine.png" class="imageblock" /> </a><br />
<?php if ($this->system->is_staff()): ?>
<a href="../combiner">
<?php endif ?>
</div>  
