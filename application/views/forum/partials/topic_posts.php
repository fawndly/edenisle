<?php $loaded_signatures = array(); ?>
<?php foreach($this->posts as $post): ?>
<div class="post-grid" id="<?= $post['post_id'] ?>">
    <div class="left">
        <a href="<?= site_url('user/'.urlencode($post['username'])) ?>">
            <img src="/images/avatars/<?= $post['user_id'] ?>.png?saved=<?= $post['last_saved_avatar'] ?>" alt="" class="topic_avatar" width="180" height="270" />
        </a>
    </div>
    <div class="post-toolbar">
        <span class="left"><?= _datadate($post['post_time'])?></span>
        <span class="right">
            <?= anchor('user/'.urlencode($post['username']), 'View Profile') ?>
            <?php if ($post['author_id'] == $this->session->userdata('user_id') || $this->system->is_staff()): ?>
                | <a href="/forum/edit_post/<?= $post['post_id'] ?>">Edit</a>
            <?php endif ?>
            <?php if ($this->system->is_staff()): ?>
                |
                <form action="/forum/delete_post" style="display:inline; margin:0; padding:0" method="POST" onsubmit="return confirm('Do you really want to delete this post?');">
                    <input type="hidden" name="url" value="<?= $_SERVER['REQUEST_URI'] ?>" />
                    <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>" />
                    <button type="submit" class="btn btn-link" style="color:#777; padding:0; margin:-2px 0 0; display:inline; font-size:12px;">Delete</button>
                </form>
            <?php endif ?>
            <?= user_online($post['last_action']) ?>
        </span>
    </div>
    <div class="post-content">
        <span class="post_author">
    <?= ($post['user_title'] ? "<span class='label'>".$post['user_title']."</span>" : "") ?>
    <a href="#message" class="reply_at" title="@<?= $post['username']?>:"><?= $post['username']; ?></a> said:
        </span>
        <?= display_ribbons($post, $this->system->userdata) ?>
        <?= parse_bbcode(stripslashes(nl2br($post['post_body']))); ?>
    </div>
    <div class="user_signature">
        <?php if (!in_array($post['username'], $loaded_signatures)): ?>
            <?php $loaded_signatures[] = $post['username'] ?>
            <?= parse_bbcode(stripslashes(nl2br($post['user_signature']))); ?>
        <?php endif ?>
    </div>
</div>
<?php $authors[] = $post['username'] ?>
<? endforeach; ?>
