 <!-- Crysandrea engine by Tyler Diaz (https://github.com/Pixeltweak/Crysandrea) --><div id="zoomit">
    <div class="ta-left">
        <h2>
            <img src="/images/icons/unlock.png" alt="" width="22" height="22" />
            Account Activated
        </h2>
        <?php if ($form == 'completed'): ?>
            <?php $this->load->view('partials/notices/success.php', array('data' => 'First, you will need to sign out, and then sign back into your accounts, refreshing your activation token and allowing you the ability to post. Please make sure to do this for every device or browser you are already signed in on.<br/><br/>
Next, your account\'s \'un-verified\' notification will be removed from the sidebar… you can now post! Woo hoo!<br/><br/>
Be sure to visit the <strong><a href="/forum/view/3">Introductions forum</a></strong> to introduce yourself to the community, they’d love to have you! Also, visit the <strong><a href="/forum/view/33">Tutorial Base</a></strong> for help navigating and exploring on Sapherna.<br/><br/>
<center>Welcome to <text style="text-decoration:underline;">Sapherna</text>! Please enjoy your stay!</center>',
                'header' => 'Yay, you have activated your account! In order to post, there\'s just a few more steps...')); ?>
            <?php endif; ?>
            <?php $form = 'completed' ?>
    </div>
</div>
