 <?php $friendsOnline = count($this->system->userdata['online_friends']); ?>

 <div id="sidebar_content">
    <div class="user_card">
        <a href="/avatar" class="avatar">
            <img src="/images/avatars/<?= $this->session->userdata['user_id'] ?>_headshot.png?<?= $user['last_saved_avatar'] ?>" alt="" title="My avatar" width="56" height="53" class="avatar_headshot"/>
        </a>
        <div class="user_info">
            <div class="username"><a href="/profile"><?= $user['username'] ?></a></div>
            <ul id="usercard_navigation">
                <li><a href="/user/<?= $user['username'] ?>">My Profile</a></li>
                <li id="my_settings"><a href="/account">Settings</a></li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>

    <div id="my_currency">
        <div class="currency_content">
            <div class="currency_row">
                <div class="currency ores_count"><div class="currency_desc"><span class="curr_icon icon-ore"></span>Ores</div><?= number_format($user['user_Ores']) ?></div>
                <div class="currency gem_count"><div class="currency_desc"><span class="curr_icon icon-sapphire"></span>Sapphires</div><?= number_format($user['user_sapphires']) ?></div>
            </div>
            <?php if($this->config->item('special_currency')['enabled']) { ?>
                        <div class="currency_row" style="margin-top: 6px; text-align: center">
                                <div class="currency gem_count"><div class="currency_desc"><span class="curr_icon" style="background: url('<?=$this->config->item('special_currency')['image'] ?>') no-repeat; background-size: 100%;"></span><?=($user['special_currency'] == '1'? $this->config->item('special_currency')['name']['singular'] : $this->config->item('special_currency')['name']['plural']) ?></div><?= number_format($user['special_currency']) ?></div>
                        </div>
                        <?php } ?>
        </div>
    </div>

    <ul id="sidebar_navigation">
        <li id="avatar"><a href="/avatar">Avatar</a></li>
        <li id="friends"><a href="/friends">Friends <?php if ($friendRequest > 0): ?><span class="bubble-small bubble-top"><?= $friendRequest ?></span><?php endif ?></a></li>
        <li id="mailbox"><a href="/mailbox">Mailbox <?php if ($unread_mail > 0): ?><span class="bubble-small bubble-top"><?= $unread_mail ?></span><?php endif ?></a></li>
        <li id="games"><a href="/games">Games</a></li>
        <li id="market"><a href="/market">Market <span class="bubble-small bubble-notice bubble-top">BETA</span></a></li>
        <li id="trades"><a href="/trades">Trades <?php if ($active_trades > 0): ?><span class="bubble-small bubble-top"><?= $active_trades ?></span><?php endif ?></a></li>
        <?php if ($this->system->is_staff()): ?>
            <?php $unread_tickets = $this->db->get_where('staff_tickets', array('status' => 'pending'))->num_rows(); ?>
            <li id="staff_mcp"><a href="/staff_panel/mcp">Staff Control Panel</a></li>
            <li id="tickets"><a href="/staff_panel/tickets">Tickets<?php if ($unread_tickets): ?><span class="bubble-small bubble-top" title="Open tickets"><?= $unread_tickets ?></span><?php endif ?></a></li>
        <?php endif ?>
    </ul>
    
    <?php if ($friendsOnline > 0): ?>
        <div class="widget" id="friends_online">
            <h3><?= $friendsOnline . ' ' . (($friendsOnline > 1) ? 'friends' : 'friend') ?> online!</h3>
            <?php foreach ($this->system->userdata['online_friends'] as $friend): ?>
                <a href="/user/<?= urlencode($friend['username']) ?>" title="<?= $friend['username'] ?>">
                    <img src="/images/avatars/<?= $friend['friend_id'] ?>_headshot.png" alt="" width="25" height="25" />
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif ?>
    
    <?php if($user['user_level'] == "verify") : ?>
    <div class="sidebar-alert">
        <h4 style="font-size:15px;line-height: 1.3;">Account not verified!</h4>
        <p>Are you excited to post yet?! Log into your email for your verification link.</p>
        <p>Don't forget to check your spam inbox!</p>
        <p>Click here to re-send a validation e-mail <a href="/auth/resend/">Resend!</a></p>
        <p>If you still haven't received an email, please make sure you have given us the correct email address by checking your <a href="/account/">Account Settings</a>.</p>

    </div>
    <?php endif ?>
    
    <div id="signout_box">
        <a href="/auth/signout">Sign out of Sapherna</a>
    </div>
</div>

<!--<ul class="instant_notifications">
    <li class="instant_notification hide" id="notification_template">
        <a href="#" class="remove_notification">&times;</a>
        <a href="#" class="notification_bubble">
            <img src="" alt="" width="35" height="35" class="avatar_headshot" />
            <div class="notification_content"></div>
        </a>
    </li>
    <li class="instant_notification clearfix hide" id="notification_queue">
        <a href="#" class="more_instant_notifications">Show <span id="extended_notification_count">0</span> more notifications &#x25BE;</a>
    </li>
</ul>-->
