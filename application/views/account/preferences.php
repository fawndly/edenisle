<?php
    $this->load->view('account/account_navigation', array('routes' => $routes, 'active_url' => $active_url));
?>

<div style="margin:15px 80px; position:relative;">
    <?php if(validation_errors()): ?>
        <?php $this->load->view('partials/notices/error.php', array('data' => validation_errors())) ?>
    <? endif; ?>

    <?php foreach ($success as $key => $success_notice): ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong><?= repeater('More ', $key).($key == 0 ? 'G' : 'g') ?>ood news!</strong> <?= $success_notice ?>
        </div>
    <?php endforeach ?>

    <div style="margin-top:15px;">
        <?= form_open('/account/preferences', array('id' => 'save_preferences', 'class' => 'form-horizontal')) ?>
        
            <div class="control-group">
                <h5>Sidebar Navigation</h5>
                <label class="control-label" for="sidebar_font_shadow">Font Style</label>
                <div class="controls">
                    <?= form_dropdown('sidebar_font_shadow', array('0' => 'Light Text Shadow', '1' => 'Dark Text Shadow'), 1, 'id="sidebar_font_shadow"'); ?><br />
                </div>
            </div>
                <h5>Home page</h5>
            <div class="control-group">
                <label class="control-label" for="  forum_games">Forum Games</label>
                <div class="controls">
                    <?= form_dropdown(' forum_games', array('0' => 'Show by default', '1' => 'Hide by default'), 1, 'id="   forum_games"'); ?><br />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="active_topics_limit">Active topics limit</label>
                <div class="controls">
                    <?= form_dropdown('active_topics_limit', array('5' => '5', '7' => '7', '9' => '9'), 1, 'id="active_topics_limit"'); ?><br />
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <button type="submit" class="main_button" id="save_signature_btn" autocomplete="off" data-toggle="button" data-loading-text="Saving changes...">Save settings</button>
                </div>
            </div>
        </form>
    </div>
    <div id="dropdown-2" class="dropdown-menu has-tip" style="max-width:220px; padding:10px; line-height:1.4">
    <h5 style="background:#ff8; display:inline;">Changing your title will cost 100 currency.</h5>
    <p style="font-size:12px; margin-top:10px"><strong>Why?</strong> Apart from the amount of work it takes, this is done to discourage people from abusing title changes. The fee will be applied only when your title has been changed.</p>
    <?php if ($user['user_Ores'] >= 100): ?>
        <a href="#" id="change_title" class="main_button" style="color:white">OK, I still want to change it</a>
    <?php else: ?>
        <div style="text-align:center; color:#888; font-size:11px; margin-top:5px; border-top:1px solid #ccc; padding-top:5px;">You do not have enough currency for this.</div>
    <?php endif ?>
</div>
</div>
