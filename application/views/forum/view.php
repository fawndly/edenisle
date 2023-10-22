<?php
$staff = ($this->system->is_staff()) ? true : false;
?>

<style type="text/css">
#topic_listing .main_link {
    font-size:14px;
    font-weight:bold;
    font-family:helvetica;
    color:#196c94;
}
#main_forum_header {
    padding:0px 15px 12px;
    margin:0 -10px;
    border-bottom:1px solid #e9e9e9;
    overflow:hidden;
}
.header_canaster {
    margin-left:4px;
}
#main_forum_header h4 {
    line-height:1.1;
    margin:4px 0 0 0;
    font-family:Helvetica;
    font-size:22px;
}
#main_forum_header .move_back {
    line-height:1;
    font-size:11px;
}
a.quiet_link {
    color:#444
}
</style>
<?php
$isLogged = $this->session->userdata('user_id');

if ($isLogged && $token != NULL && mt_rand(1, 100) <= $this->config->item('drop_item')['probability'] ) {
        $x=mt_rand(3,97);
        $y=mt_rand(3,97);
?>
<a href="/drop/grab/<?=$token ?>"><img src="<?=$this->config->item('drop_item')['image'] ?>" style="position:fixed; top:<?=$x?>%; right:<?=$y?>%; z-index:999;"/></a>
<?php } ?>

<div id="main_forum_header">
    <div class="left header_canaster">
        <h4><?= $page_title ?></h4>
        <a href="/forum" class="move_back">&lsaquo; Return to the forums</a>
    </div>

    <?php if($forum_id == 1 && ! $this->system->is_staff()): ?>
    <?php else: ?>

    <span class="right" style="margin-top:5px">
        <a href="<?=site_url('forum/new_topic/'.$forum_id);?>" class="main_button">Create a topic</a>
    </span>

    <?php if($staff == true): ?>
    <form action="<?=site_url('forum/group_change')?>" method="post" id="group_change" name="group_change">
    <div id="group_edit" class="right">
        <input type="hidden" value="<?= current_url() ?>" id="redirect_url" name="redirect_url" />
        <select class="large-input" name="do" id="do">
            <option value="none" selected>Do with selected...</option>
            <optgroup label="Thread locking">
                <option value="lock">Lock</option>
                <option value="unlock">Unlock</option>
            </optgroup>
            <optgroup label="Sticky-ness">
                <option value="sticky">Sticky</option>
                <option value="unsticky">Unsticky</option>
            </optgroup>
            <optgroup label="Moving thread">
                <option value="move_to">Move to...</option>
            </optgroup>
        </select>

        <?php $category = $this->db->select('forum_id, forum_name')->get('forums')->result_array(); ?>

        <select id="forum_id" name="forum_id">
            <option value="none">Forum name...</option>
            <?php foreach($category as $forum){ ?>
                <option value="<?= $forum['forum_id'] ?>"><?= $forum['forum_name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <?php endif; ?>
<?php endif; ?>
</div>

<?php if(isset($topics[0])): ?>
    <table cellpadding="0" cellspacing="0" class="clean" id="topic_listing">
        <thead>
            <tr>
                <th>Topic title</th>
                <th width="190">Latest post
                    <?php if($staff == true): ?>
                        <div class="checkbox_holder">
                            <input class="multi_select" id="select_all_topics" type="checkbox" name="select_all_topics" value="0" style="top: -14px;" />
                        </div>
                    <?php endif; ?>
                </th>
            </tr>
        </thead>
        <?php foreach($topics as $topic): ?>
            <tr class="<?=cycle('', 'alt')?> <?=$topic['topic_type']?>">
                <td class="selectable">
                    <img src="/images/avatars/<?= $topic['topic_author'] ?>_headshot.png?1340621489" alt="" style="float:left; width:42px;height:42px;margin:-5px 5px 0 -6px;">
                    <?php if($topic['topic_status'] == "locked") echo '<img src="/images/icons/lock.png" alt="Locked:">'; ?>
                    <?php if($topic['topic_type'] != "") echo '<strong>'.ucfirst($topic['topic_type']).': </strong>'; ?>
                    <?= (strpos(strtolower($topic['topic_title']), 'think') || strpos(strtolower($topic['topic_title']), 'bandwagon') ? '<span class="label label-success">Wagon</span>' : '') ?>
                    <?= anchor('topic/view/'.$topic['topic_id'], stripslashes( $topic['topic_title'] ), 'class="main_link"') ?>
                    <br />
                    By: <a href="/user/<?= urlencode($topic['username']) ?>" class="quiet_link"><?= $topic['username']?></a> &ndash; <?= number_format($topic['total_posts']) ?> <?= (($topic['total_posts'] > 1) ? 'replies' : 'reply') ?>
                </td>
                <td>
                    <span class="large"><?= human_time($topic['last_post'])?></span>
                    <br />
                    by <?= substr($topic['last_post_username'], 0, 18)?> |
                    <?= anchor('topic/view/'.$topic['topic_id'].'/'. get_topic_page($topic['total_posts']).'/#footer', 'View post &rsaquo;'); ?>
                    <?php if($staff == true): ?>
                    <div class="checkbox_holder">
                        <input class="multi_select" type="checkbox" name="topic_id[]" value="<?=$topic['topic_id']?>" />
                    </div>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    <?php else: ?>
        <br />
        <div class="empty_notice">
            This forum is new so new one has made a topic yet. You should <a href="/forum/new_topic/<?= $forum_id ?>">be the first</a>!
        </div>
        <br />
    <?php endif; ?>
    <div class="row">
        <span class="left large breath"><?= $this->pagination->create_links();?></span>
        <span class="right" class="breath_top"><?=anchor('forum/new_topic/'.$forum_id, 'Create a topic', 'class="main_button"');?></span>
    </div>
<?php if($staff == true): ?>
    </form>
    <link rel="stylesheet" type="text/css" href="/global/css/crysandrea.staff.styles.css" />
<?php endif; ?>
