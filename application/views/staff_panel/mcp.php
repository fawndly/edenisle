<?php
    $this->load->view('layout/feature_navigation', array('routes' => $routes, 'active_url' => $active_url, 'core' => 'staff_panel'));
?>

<h3>Staff Log In Manager</h3>
<a href="https://www.timeclockwizard.com/free-time-clock-app/Login.aspx?client=sapherna" id="btnTimeClockLogin" style="display: inline-block; zoom: 1; display: inline;vertical-align: baseline; margin: 12px 2px; outline: none; cursor: pointer; text-align: center;text-decoration: none; font: 14px/100% Arial, Helvetica, sans-serif; padding: .5em 2em .55em; text-shadow: 0 1px 1px rgba(0,0,0,.3); -webkit-border-radius: .5em; -moz-border-radius: .5em; border-radius: .5em; -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.2); -moz-box-shadow: 0 1px 2px rgba(0,0,0,.2);box-shadow: 0 1px 2px rgba(0,0,0,.2); color:White;background-color:#548dd4;font-weight:bold;" rel="nofollow">Time Clock Login</a><br><br>


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
