<div class="post-grid" id="<?= $post['post_id'] ?>">
    <div class="left">
        <a href="<?= site_url('user/'.urlencode($post['username'])) ?>">
            <img src="/images/avatars/<?= $post['user_id'] ?>.png" alt="" class="topic_avatar" width="180" height="270" />
        </a>
    </div>
    <div class="post-toolbar">
        <span class="left">Posted <?= _datadate($post['post_time'])?></span>
        <span class="right">
        <a href="#message" onclick="quote_post(<?= urlencode($post['post_id']);?>);" title="Quote post">Quote</a> |
            <?= anchor('user/'.urlencode($post['username']), 'View Profile') ?>
            <?= user_online($post['last_action']) ?>
        </span>
    </div>
    <div class="post-content">
        <span class="post_author">
            <a href="#message" class="reply_at" title="@<?= $post['username']?>:"><?= $post['username'] ?></a> said:
        </span>
        <?= display_ribbons($post, $this->system->userdata) ?>
        <?= parse_bbcode(stripslashes((nl2br($post['post_body'])))); ?>
    </div>
    <div class="user_signature">
        <?= parse_bbcode(stripslashes(nl2br($post['user_signature']))) ?>
    </div>
</div>
