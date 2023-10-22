<?php
    $this->load->view('layout/feature_navigation', array('routes' => $routes, 'active_url' => $active_url, 'core' => 'staff_panel'));
?>

<h3>Staff Toolkit</h3>
<ul class="menuItens">
    <li><a href="/staff_panel/tickets">Tickets <?php if ($pending_tickets > 0): ?><span class="bubble-small bubble-bottom" title="Pending Tickets"><?php echo $pending_tickets ?></span><?php endif ?></a></li>
    <li><a href="/staff_panel/users">User</a></li>
    <li><a href="/staff_panel/infractions">Warnings</a></li>
    <li><a href="/pcp">Modify Items</a></li>
    <li><a href="/pcp/createitem">Create Items</a></li>
    <li><a href="/staff_panel/honeying">Developer Testing Area</a></li>
    <li><a href="/pcp/layers">Layer References</a></li>
</ul>

<div class="clearfix"></div><br>

<h3>Staff Forums</h3>
<ul class="menuItens">
    <li><a href="/forum/view/18">Future of Sapherna</a></li>
    <li><a href="/forum/view/19">Moderator Discussion</a></li>
    <li><a href="/forum/view/20">Pixelist Circle</a></li>
    <li><a href="/forum/view/21">Recycle Bin</a></li>
</ul>
