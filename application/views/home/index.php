<?php
?>
<style type="text/css">
    #dashboard-tabs {
        list-style:none;
        margin:0;
        padding:0;
        overflow:hidden;
    }

    #dashboard-tabs li {
        float: left;
        padding: 8px 12px;
        background: transparent no-repeat center bottom;
    }

    #dashboard-tabs li a { font-weight: bold; text-decoration: none;}
    #dashboard-tabs li.active { background-image:url(/images/icons/dashboard_ticker.png) }
    #dashboard-tabs li.active a {
        color:#10548b;
        opacity:0.8;
    }

    #dashboard-content > div { display:none; }
    #dashboard-content > div:first-child { display:block; }

    #dashboard-content {
        margin-top: -1px;
        border: 1px solid #ccc;
        border-radius: 10px;
        box-shadow: 1px 1px 1px #ccc;
    }

    #dashboard-content .small-headshot {
        float: left;
        width: 42px;
        height: 42px;
        margin: -3px 5px 0 -2px;
    }

    #dashboard_active_top {
        list-style:none;
        margin:0;
        padding:0;
    }

    .topics-control {
        width: 100%;
        background: #eee;
        border-bottom: 1px solid #ccc;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        padding-top: 2px;
    }

    .topics-control label {
        float: right;
        margin: 0 6px 2px 0;
        font-size: 11px;
        vertical-align: top;
        position: relative;
    }

    .topics-control label input {
        margin-top: 0;
        position: relative;
        top: 2px;
    }

    .list-information {
        margin: 0;
        list-style: none;
    }

    .list-information li { border-bottom:1px solid #ccc }
    .list-information li:last-child { border-bottom:none; }

    .list-information li a {
        display:block;
        overflow:hidden;
        padding: 8px 6px 6px 6px;
    }

    .list-information li a:hover {
        text-decoration:none;
        background:#e3f5fb;
        color:#0067af;
    }

    .list-information li a:last-child {
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    .list-information li a span.db_topic_title { font-size:15px; }
    .list-information li a p.db_topic_info {
        margin: 0;
        font-size: 12px;
        color: #aaa
    }

    .list-information li a:hover span.db_topic_title { text-decoration:underline }
    .list-information li a:hover p.db_topic_info { color:#555 }

    #dashboard_notifications {
        margin: 0 0 0 18px;
        padding: 6px;
    }

    .recent-notifications li {
        padding: 0 7px;
        border: 1px solid #F7F7F7;
        border-radius: 4px;
    }

    .recent-notifications li:hover {
        border-color: #ccc;
        background: #e3f5fb;
    }

    .recent-notifications li a {
        text-decoration: none;
        line-height: 16px;
    }

    .recent-notifications .little-face {
        display: inline-block;
    }

    .recent-notifications .little-face img {
        width: 25px;
        margin: 4px 0;
    }

    .recent-notifications .n-info {
        font-size: 9px;
        text-align: right;
    }

    .n-new {
        color: #fff;
        padding: 0 2px;
        background: #b94a48;
        border-radius: 4px;
    }

    .recent-notifications .n-info .n-time {
        margin-left: 2px;
        color: #aaa
    }

    .n-new {
        color: #fff;
        padding: 0 2px;
        background: #b94a48;
        border-radius: 4px;
    }

    .recent-notifications .n-info .n-time {
        margin-left: 2px;
        color: #aaa
    }
    
    .n-content assssss {
        display: block;
        width: 100%;
        height: 100%;
    }

    .btn16 {font-size: 10px !important; padding: 2px 4px;}
    .icon16 {width: 16px; height: 16px}
    .icon-read {background: url(/images/icons/icon-glasses.png)}
    .icon-delete {background: url(/images/icons/icon-delete.png)}
    
    .n-content {border-bottom: 1px solid #ccc}
    .n-head {float: left; width: 52px}
    .n-message {float: left; width: 310px}
    .n-message a {font-size: 11px}
    .n-buttons {float: right; width: 50px}
    .n-row {padding: 4px}
    .n-buttons .btn-group {float: right; padding: 6px}
    
    .oldAnn {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .oldAnn li a{
        margin-bottom: 6px;
        font-size: 10px;
        color: #777;
    }
</style>
<?php

$isLogged = $this->session->userdata('user_id');

if ($isLogged && $token != NULL && mt_rand(1, 100) <= $this->config->item('drop_item')['probability'] ) {
        $x=mt_rand(0,100);
        $y=mt_rand(0,100);
?>
<a href="/drop/grab/<?=$token ?>"><img src="<?=$this->config->item('drop_item')['image']?>" style=" position: fixed; top: <?echo $x?>%; right: <?echo $y?>%; z-index:999;"/></a>
<?php } ?>
<div class="clearfix">
    <div class="home-container home-container-left" id="tab_holder">
        <ul id="dashboard-tabs">
            <li class="active"><a href="#topic-updates">Active Topics</a></li>
            <li><a href="#user-notification">Notifications <?php if($total_unread): ?> <span class="n-new" id="n-total"><?=$total_unread?></span><?php endif; ?></a></li>
        </ul>
        <div id="dashboard-content">
            <div id="topic-updates">
                <div class="topics-control">
                    <label for="display_f-games"><input type="checkbox" id="display_f-games" checked="" /> Show Forum Games</label>
                    <label for="update-topics"><input type="checkbox" id="update-topics" checked="checked" /> Auto update&nbsp;</label>
                    <div class="clearfix"></div>
                </div>
                <ul class="list-information" id="topic-list">
                  <?php $this->load->view('partials/dashboard_topics', array('latest_topics' => $latest_topics)) ?>
                </ul>
            </div>
            <div id="user-notification">
                <div class="topics-control">
                    <label>
                        <a href="#" id="n-refresh">Refresh</a>&nbsp;
                        <a href="#" id="n-readall">Mark all as read</a>&nbsp;
                        <a href="#" id="n-deleteall">Dismiss all</a>
                    </label>
                    <div class="clearfix"></div>
                </div>
                <ul class="list-informationz" id="notification-list" style="list-style: none; padding: 0; margin: 0">
                    <?php if (count($notifications)): ?>
                        <?php foreach ($notifications as $row): ?>
                            <li class="n-content" data-n-id="<?=$row['id']?>">
                                <div class="n-head">
                                    <div class="n-row">
                                        <a href="<?= '/user/'.urlencode($row['username']) ?>">
                                            <img src="/images/avatars/<?=$row['author_id']?>_headshot.png" alt="" style="width: 100%" title="<?=$row['username']?>"/>
                                        </a>
                                    </div>
                                </div>
                                <div class="n-message">
                                    <div class="n-row">
                                        <a href="<?= $row['target'] ?>" class="notification-primary-link">
                                            <?=($row['type'] == 1) ? "<b>{$row['username']}</b> mentioned you in a post." : '' ?>
                                            <?=($row['type'] == 2) ? "<b>{$row['username']}</b> left a message on your profile." : '' ?>
                                            <?=($row['type'] == 3) ? "<b>{$row['username']}</b> sent you a friend request." : '' ?>
                                            <div class="n-info">
                                                <?php if (!$row['is_read']): ?><span class="n-new">New!</span><?php endif; ?>
                                                <span class="n-time"><?=$row['time']?></span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="n-buttons">
                                        <div class="btn-group">
                                            <?php if (!$row['is_read']): ?><a href="#" class="btn16 btn n-read" title="mark as read"><span class="icon16 icon-read"></span></a><?php endif; ?>
                                            <a href="#" class="btn16 btn n-delete" title="remove"><span class="icon16 icon-delete"></span></a>
                                        </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>
                        <?php endforeach ?>
                    <?php else: ?>
                        <li><div class="n-row">No notifications yet!</div></li>
                    <?php endif ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="home-container home-container-right right-notification">
        <div class="notification-container">
            <div class="sub-container">
                <h5>Spotlight Topic</h5>
                <div class="spotlight">
                    <div class="media-body">
                        <img class="media-object" src="/images/avatars/<?= $spotlight_topic['user_id'] ?>_headshot.png" alt="" width="42" height="42"/>
                        <?= anchor('/topic/view/'.$spotlight_topic['topic_id'], $spotlight_topic['topic_title']) ?><br>
                        <small style="color: #999">
                            By <strong class="media-heading"><?= anchor('/user/'.urlencode($spotlight_topic['username']), $spotlight_topic['username']) ?></strong> &#8901; <span><?= human_time($spotlight_topic['timestamp']) ?></span>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="notification-container">
            <div class="sub-container">
                <h5>Latest Announcement</h5>
                <?php if (isset($announcements[0])): ?>
                    <?php if (strtotime($announcements[0]['topic_time']) > time() - 86400): ?>
                        <span class="label label-important">New!</span>
                    <?php endif ?>
                    <a href="/topic/view/<?= $announcements[0]['topic_id'] ?>"><?= $announcements[0]['topic_title'] ?></a>
                <?php else: ?>
                    <span class="muted">No new announcements yet!</span>
                <?php endif ?>
            </div>
            <div class="sub-container">
                <h5>Older Announcements</h5>
                <ul class="oldAnn">
                <?php foreach($announcements as $k => $row): ?>
                <?php if ($k == 0) continue; ?>
                    <li><a href="/topic/view/<?=$row['topic_id'] ?>"><?=$row['topic_title']?></a></li>
                <?php endforeach ?>
                </ul>
            </div>
        </div>
        <div class="notification-container" style="display: none">
            <div class="">
                <h5>Recent Notifications</h5>
                <div class="recent-notifications">
                    <ul>
                    <?php if (count($notifications)): ?>
                        <?php foreach ($notifications as $key => $row): ?>
                            <li>
                                <a href="<?= '/user/'.urlencode($row['username']) ?>" class="little-face">
                                    <img src="/images/avatars/<?=$row['author_id']?>_headshot.png" alt="" title="<?=$row['username']?>"/>
                                </a>
                                <a href="<?= $row['target'] ?>" style="font-size: 11px" data-n-id="<?=$row['id']?>" class="n-link">
                                    <span style="text-align: left">
                                        <?=($row['type'] == 1) ? "<b>{$row['username']}</b> mentioned you in a post." : '' ?>
                                        <?=($row['type'] == 2) ? "<b>{$row['username']}</b> left a message on your profile." : '' ?>
                                        <?=($row['type'] == 3) ? "<b>{$row['username']}</b> sent you a friend request." : '' ?>
                                    </span>
                                    <div class="n-info">
                                        <?php if (!$row['is_read']): ?><span class="n-new">New!</span><?php endif; ?>
                                        <span class="n-time"><?=human_time($row['time'])?></span>
                                    </div>
                                </a>
                                <div class="clearfix"></div>
                            </li>
                        <?php if ($key > 3) break; endforeach; ?>
                    <?php else: ?>
                        <li>No notifications yet!</li>
                    <?php endif ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
