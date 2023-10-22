 <!-- Crysandrea engine by Tyler Diaz (https://github.com/Pixeltweak/Crysandrea) --><div id="zoomit">
    <div class="ta-left">
        <h2>
            <img src="/images/icons/unlock.png" alt="" width="22" height="22" />
            Forgot your password? We can help!
        </h2>
        <?php if ($sent): ?>
            <?php $this->load->view('partials/notices/success.php', array('data' => 'You should soon receive an email on <strong>'.$this->input->post('email').'</strong> with instructions on how to reset your password. In case you haven\'t received it, try checking your spam box or resending a new password reset link. If you\'re having trouble receiving the email and are still logged in somewhere, please submit a Staff Support ticket.', 'header' => 'Your email has been sent!')); ?>
        <?php else: ?>
            <?php if(validation_errors()): ?>
                <?php $this->load->view('partials/notices/error.php', array('data' => validation_errors())) ?>
            <?php endif; ?>

            <form class="form-horizontal" method="POST" action="/auth/forgot_password" style="margin:15px 15px 0 30px;">
                <div class="next_up" style="margin:0px 0 15px;">Submit your email and we'll send step-by-step instructions on how to reset your account's password.</div>
                <div class="control-group">
                    <label class="control-label" for="email">Enter your email:</label>
                    <div class="controls"><input type="text" id="email" name="email" /></div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <button type="submit" class="main_button" id="reset_password_btn" autocomplete="off" data-toggle="button" data-loading-text="Sending instructions...">Send me the instructions &rsaquo;</button>
                    </div>
                </div>
            </form>
        <?php endif ?>
    </div>
</div>
