 <!-- Crysandrea engine by Tyler Diaz (https://github.com/Pixeltweak/Crysandrea) --><?php $this->load->view('account/account_navigation', array('routes' => $routes, 'active_url' => $active_url)); ?>

<div style="margin:15px 80px; position:relative;">
    <?php if(validation_errors()): ?>
        <?php $this->load->view('partials/notices/error.php', array('data' => validation_errors())) ?>
    <? endif; ?>

    <?php foreach ($success as $key => $success_notice): ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong><?php echo repeater('More ', $key).($key == 0 ? 'G' : 'g') ?>ood news!</strong> <?php echo $success_notice ?>
        </div>
    <?php endforeach ?>

    <div style="margin-top:15px;">
        <?php echo form_open('/account/signature', array('id' => 'save_signature')) ?>
        
            <div class="control-group">
                <label class="control-label" for="forum_signature">Your forum signature: <strong style="background:#ffc; font-size:12px;">(<span id="chars_left"><?php echo 450-strlen($user['user_signature']) ?></span> characters left)</strong>:</label>
                <div class="controls">
                    <textarea name="user_signature" id="forum_signature" cols="30" rows="8" class="input-xxlarge" style="resize:vertical;"><?php echo stripslashes($user['user_signature']) ?></textarea>
                </div>
                 <div class="control-group">
                <label class="control-label" for="title">Forum Title</label>
              <div class="controls"><input type="text" id="title" name="title" disabled value="<?php echo $user['user_title'] ?>" /><br /><small class="help-inline"><a href="#" data-dropdown="#dropdown-2" id="change_title_tip">Want to change your title?</a></small></div>
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
