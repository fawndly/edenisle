<?php
    function timeLeft($time) {
        $date = strtotime($time);

        $rem = $date - time();
        $day = floor($rem / 86400);
        $hr  = str_pad(floor(($rem % 86400) / 3600), 2, "0", STR_PAD_LEFT);
        $min = str_pad(floor(($rem % 3600) / 60), 2, "0", STR_PAD_LEFT);
        $sec = str_pad(floor($rem % 60), 2, "0", STR_PAD_LEFT);

        return "{$day} D, {$hr}:{$min}:{$sec}";
    }
?>
<style type="text/css">
    #forum_panes{margin:2px 1px 0}
    #forum_panes>div{display:none;overflow:hidden;background:#aaa;padding:10px 8px 13px;min-height:214px;-webkit-border-radius:4px 4px 0 0;border-radius:4px 4px 0 0}
    #forum_panes>div:first-child{display:block}#forum_panes div a{display:block;width:200px;padding:12px;min-height:70px;margin:4px;border:2px solid #777;float:left;-webkit-border-radius:6px;border-radius:6px}
    #forum_panes div a:hover{text-decoration:none;background-color:rgba(255,255,255,.9)}
    #forum_panes div a:active{opacity:.8;color:#888;box-shadow:inset 0 0 4px 1px rgba(0,0,0,.45)}
    #forum_panes div a h3{font-size:14px;margin:0;line-height:1.6}
    #forum_panes div a p{font-size:11px;margin:0;line-height:1.4}
    #forum_tabs{list-style:none;overflow:hidden;margin:0 0 0 1px}
    #forum_tabs li{float:left;margin:0}
    #forum_tabs li a{display:block;float:left;width:181px;background:#ccc;font-size:16px;font-weight:700;line-height:32px;text-shadow:1px 0 3px 0 rgba(0,0,0,.3)}
    #forum_tabs li a:hover{text-decoration:underline;color:#ccc}
    #forum_tabs li a span{display:block;font-size:16px;padding:16px 0 14px 22px;font-weight:700;height:32px;box-shadow:inset 0 3px 1px 0 rgba(0,0,0,.15);border-right:1px solid #000}
    #forum_tabs li:first-child a{-webkit-border-radius:0 0 0 4px;border-radius:0 0 0 4px}
    #forum_tabs li:last-child a{width:181px;-webkit-border-radius:0 0 4px;border-radius:0 0 4px}
    #forum_tabs li#market span{border-right:0;-webkit-border-radius:0 0 4px;border-radius:0 0 4px}
    #forum_tabs li#sapherna span{-webkit-border-radius:0 0 0 4px;border-radius:0 0 0 4px}
    #forum_tabs li#community span{background:#612FA2;color:#CBABD5;border-color:#351749}div#community_pane{background:linear-gradient(to bottom,#BC90EC 0,#612FA2 100%);background:-moz-linear-gradient(top,#BC90EC 0,#612FA2 100%);background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,#BC90EC),color-stop(100%,#612FA2));background:-webkit-linear-gradient(top,#BC90EC 0,#612FA2 100%);background:-o-linear-gradient(top,#BC90EC 0,#612FA2 100%);background:-ms-linear-gradient(top,#BC90EC 0,#612FA2 100%);background:linear-gradient(to bottom,#BC90EC 0,#612FA2 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#BC90EC', endColorstr='#612FA2', GradientType=0)}
    #forum_tabs li#community a.current span{box-shadow:none;color:#D7C8E8;background:#612FA2;background:-moz-linear-gradient(top,#612FA2 1%,#512A85 25%,#391D5E 81%,#34214E 100%);background:-webkit-gradient(linear,left top,left bottom,color-stop(1%,#612FA2),color-stop(25%,#512A85),color-stop(81%,#391D5E),color-stop(100%,#34214E));background:-webkit-linear-gradient(top,#612FA2 1%,#512A85 25%,#391D5E 81%,#34214E 100%);background:-o-linear-gradient(top,#612FA2 1%,#512A85 25%,#391D5E 81%,#34214E 100%);background:-ms-linear-gradient(top,#612FA2 1%,#512A85 25%,#391D5E 81%,#34214E 100%);background:linear-gradient(to bottom,#612FA2 1%,#512A85 25%,#391D5E 81%,#34214E 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#612FA2', endColorstr='#34214E', GradientType=0)}div#community_pane a{background:#E8DCF1;border-color:#2B0982}
    #forum_tabs li#market span{background:#62AFC7;color:#E7F8FD}div#market_pane{background:#F58D8D;background:-moz-linear-gradient(top,#DCF5F5 1%,#62AFC7 100%);background:-webkit-gradient(linear,left top,left bottom,color-stop(1%,#DCF5F5),color-stop(100%,#62AFC7));background:-webkit-linear-gradient(top,#DCF5F5 1%,#62AFC7 100%);background:-o-linear-gradient(top,#DCF5F5 1%,#62AFC7 100%);background:-ms-linear-gradient(top,#DCF5F5 1%,#62AFC7 100%);background:linear-gradient(to bottom,#DCF5F5 1%,#62AFC7 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#DCF5F5', endColorstr='#62AFC7', GradientType=0)}
    #forum_tabs li#market a.current span{box-shadow:none;color:#DBF9FF;background:#990C0C;background:-moz-linear-gradient(top,#62AFC7 1%,#4B9FB9 25%,#2B6B80 81%,#154757 100%);background:-webkit-gradient(linear,left top,left bottom,color-stop(1%,#62AFC7),color-stop(25%,#4B9FB9),color-stop(81%,#2B6B80),color-stop(100%,#154757));background:-webkit-linear-gradient(top,#62AFC7 1%,#4B9FB9 25%,#2B6B80 81%,#154757 100%);background:-o-linear-gradient(top,#62AFC7 1%,#4B9FB9 25%,#2B6B80 81%,#154757 100%);background:-ms-linear-gradient(top,#62AFC7 1%,#4B9FB9 25%,#2B6B80 81%,#154757 100%);background:linear-gradient(to bottom,#62AFC7 1%,#4B9FB9 25%,#2B6B80 81%,#154757 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#62AFC7', endColorstr='#154757', GradientType=0)}div#market_pane a{background:#DCF1F1;border-color:#248BB3}
    #forum_tabs li#sapherna span{background:#125DBD;color:#B2DFF8;border-color:#142342}div#sapherna_pane{background:#BDDDE4;background:-moz-linear-gradient(top,#BDDDE4 0,#125DBD 95%);background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,#BDDDE4),color-stop(95%,#125DBD));background:-webkit-linear-gradient(top,#BDDDE4 0,#125DBD 95%);background:-o-linear-gradient(top,#BDDDE4 0,#125DBD 95%);background:-ms-linear-gradient(top,#BDDDE4 0,#125DBD 95%);background:linear-gradient(to bottom,#BDDDE4 0,#125DBD 95%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#BDDDE4', endColorstr='#125DBD', GradientType=0)}
    #forum_tabs li#sapherna a.current span{box-shadow:none;color:#d9edff;background:#125DBD;background:-moz-linear-gradient(to bottom,#125DBD 1%,#094EA7 25%,#113869 81%,#0A356B 100%);background:-webkit-gradient(linear,left top,left bottom,color-stop(1%,#125DBD),color-stop(25%,#094EA7),color-stop(81%,#113869),color-stop(100%,#0A356B));background:-webkit-linear-gradient(top,#16a4c4 1%,#149bb6 25%,#1293aa 81%,#108ea2 100%);background:-o-linear-gradient(to bottom,#125DBD 1%,#094EA7 25%,#113869 81%,#0A356B 100%);background:-ms-linear-gradient(to bottom,#125DBD 1%,#094EA7 25%,#113869 81%,#0A356B 100%);background:linear-gradient(to bottom,#125DBD 1%,#094EA7 25%,#113869 81%,#0A356B 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#125DBD', endColorstr='#0A356B', GradientType=0)}div#sapherna_pane a{background:#d1e2ed;border-color:#4092b4}
    #forum_tabs li#art span{background:#D65488;color:#FDDCE9;border-color:#CC4178}div#art_pane{background:#83E0A8;background:-moz-linear-gradient(top,#F1C5E0 1%,#D65488 100%);background:-webkit-gradient(linear,left top,left bottom,color-stop(1%,#F1C5E0),color-stop(100%,#D65488));background:-webkit-linear-gradient(top,#F1C5E0 1%,#D65488 100%);background:-o-linear-gradient(top,#F1C5E0 1%,#D65488 100%);background:-ms-linear-gradient(top,#F1C5E0 1%,#D65488 100%);background:linear-gradient(to bottom,#F1C5E0 1%,#D65488 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#F1C5E0', endColorstr='#D65488', GradientType=0)}
    #forum_tabs li#art a.current span{box-shadow:none;color:#FFD1F0;background:#0A743C;background:-moz-linear-gradient(top,#D65488 1%,#B84875 25%,#8F2A52 81%,#752042 100%);background:-webkit-gradient(linear,left top,left bottom,color-stop(1%,#D65488),color-stop(25%,#B84875),color-stop(81%,#8F2A52),color-stop(100%,#752042));background:-webkit-linear-gradient(top,#D65488 1%,#B84875 25%,#8F2A52 81%,#752042 100%);background:-o-linear-gradient(top,#D65488 1%,#B84875 25%,#8F2A52 81%,#752042 100%);background:-ms-linear-gradient(top,#D65488 1%,#B84875 25%,#8F2A52 81%,#752042 100%);background:linear-gradient(to bottom,#D65488 1%,#B84875 25%,#8F2A52 81%,#752042 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#D65488', endColorstr='#752042', GradientType=0)}div#art_pane a{background:#F6E8F3;border-color:#802C6B}
    #forum_tabs li a.current{background:#aaa}#forum_tabs li a.current span{padding-bottom:16px;box-shadow:none}#forum_tabs li a span img{margin-right:7px;margin-top:-2px}
</style>
<?php
$isLogged = $this->session->userdata('user_id');

if ($isLogged && $token != NULL && mt_rand(1, 100) <= $this->config->item('drop_item')['probability'] ) {
        $x=mt_rand(3, 97);
        $y=mt_rand(3, 97);
?>
<a href="/drop/grab/<?=$token ?>"><img src="<?=$this->config->item('drop_item')['image'] ?>" style="position:fixed; top:<?=$x?>%; right:<?=$y?>%; z-index:999;"/></a>
<?php } ?>
<?php $this->load->view('forum/forum_navigation', array('routes' => $routes, 'active_url' => $active_url)); ?>

<div id="forum_panes">
    <?php foreach ($forums as $category_name => $forum_array): ?>
    <div id="<?= strtolower($category_name) ?>_pane">
        <?php foreach ($forum_array as $forum_data): ?>
            <a href="/forum/view/<?= $forum_data['forum_id'] ?>" id="forum_<?= $forum_data['forum_id'] ?>">
                <h3><?= $forum_data['forum_name'] ?> <img src="/images/icons/new.png" alt="" style="display:none" /></h3>
                <p><?= $forum_data['forum_description'] ?></p>
            </a>
        <?php endforeach ?>
    </div>
<?php endforeach ?>
</div>

<ul id="forum_tabs">
<?php foreach ($forums as $category_name => $forum_array): ?>
    <li id="<?= strtolower($category_name); ?>">
        <a href="#<?= strtolower($category_name); ?>" class="<?= $category_name == 'sapherna' ? 'current' : '' ?>">
            <span><img width="32" height="32" src="/images/forum_icons/<?= strtolower($category_name); ?>.png" /><?= ucfirst($category_name); ?></span>
        </a>
    </li>
<?php endforeach ?>

</ul>
<br />

<div class="row">
    <div class="grid_4">
        <div style="margin-bottom: 6px;">
            <h5 style="margin: 0;">There are currently <?= count($users_online)?> online <?= (count($users_online) > 1) ? 'users' : 'user'; ?> browsing the site. </h5>
            <?php foreach ($users_online as $user_key => $user): ?>
                <a href="/user/<?= urlencode($user['username']) ?>" style="<?= user_style($user['user_level']) ?>"><?= $user['username'] ?></a><?= ($user_key != count($users_online)-1 ? ', ' : '') ?>
            <?php endforeach ?>
            <h5 style="margin-top: 12px;"><?= count($users_posting)?> <?= (count($users_online) > 1) ? 'users' : 'user'; ?> have posted in forums recently.</h5>
        </div>
        <div>
            <?php if (count($recent_users)): ?>
                <h6 style="margin: 0;"><?= (count($recent_users) > 1) ? 'These users' : 'This user'; ?> have joined us today.</h6>
                <?php foreach ($recent_users as $user_key => $user): ?>
                    <a href="/user/<?= urlencode($user['username']) ?>"><?= $user['username'] ?></a><?= ($user_key != count($recent_users)-1 ? ', ' : '') ?>
                <?php endforeach ?>
            <?php endif ?>
        </div>
    </div>
    <div class="grid_2">
        <div style="height: 1px; background-color: #b2d898; padding-left:5px; font-family:arial; margin:5px 0 13px 0; clear:both;">
          <span style="background-color: #F7F7F7; position: relative; top: -0.73em; padding:0 6px; text-transform:uppercase; color:#476d20; font-size:11px; font-weight:bold;">
            <img src="/images/icons/statistics.png" alt="" style="margin-top:-4px"> Sapherna's Statistics
          </span>
        </div>
        <div style="margin:6px 0 0px 15px; font-size:14px;">Total posts: <strong><?= number_format($total_posts) ?></strong></div>
        <div style="margin:6px 0 0px 15px; font-size:14px;">Total users: <strong><?= number_format($total_users) ?></strong></div>
        <div style="margin-top: 16px; display: none">
            <div style="height: 1px; background-color: #b2d898; padding-left:5px; font-family:arial; margin:5px 0 13px 0; clear:both;">
              <span style="background-color: #F7F7F7; position: relative; top: -0.73em; padding:0 6px; text-transform:uppercase; color:#476d20; font-size:11px; font-weight:bold;">
                <img src="https://cdn0.iconfinder.com/data/icons/feather/96/clock-128.png" alt="" style="margin-top:-3px; width: 8px; height: 8px;"> Milestones
              </span>
            </div>
            <div style="margin:6px 0 0px 15px; font-size:14px;">
                50 users online
                <div id="time1"><strong><?=timeLeft("September 23, 2016 12:00 PM")?></strong></div>
            </div>
            <div style="margin:6px 0 0px 15px; font-size:14px;">
                70 users online
                <div id="time2"><strong><?=timeLeft("September 27, 2016 12:00 PM")?></strong></div>
            </div>
        </div>
    </div>
</div>
<br clear="all" />
