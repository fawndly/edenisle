                </div>
                <div id="footer">
                    <a href="#logo" class="logo" title="&#9786; Back to top">Sapherna</a>
                    <span title="Create a support ticket" class="right" id="helpme"><a href="/ticket/" class="help">Staff support</a> </span>
                    <div class="first">
                        <a href="/?ref=footer">Home</a> &bull;
                        <a href="/forum?ref=footer">Forum</a> &bull;
                        <a href="/games?ref=footer">Games</a> &bull;
                        <a href="/avatar?ref=footer">Avatar</a> &bull;
                        <a href="/shops?ref=footer">Shops</a> &bull;
                        <a href="/donate?ref=footer">Donate</a> &bull;
                        <a href="/general/credits">Credits</a> 
                    </div>
                    <br />
                    <div>
                        &copy;2016 Sapherna, All rights reserved (<a href="/general/tos" target="_blank" >Code of Conduct</a> &bull; <a href="/general/privacy" target="_blank" >Your Privacy</a>)
                    </div>
                </div>
            </div>
            <!--<div id="search_results">
                <h3>Top Results:</h3>
                <ul></ul>
                <div id="search_additional">
                    <select name="search_type" id="search_type" class="left">
                        <option value="items">Items</option>
                        <option value="users">Users</option>
                        <option value="topic_titles">Topics</option>
                        <option value="mailbox">Mailbox</option>
                    </select>
                    
                </div>
            </div>-->
        </div>
        <?php foreach ($scripts as $script): ?>
        <script src="<?php echo $script ?>" type="text/javascript" charset="utf-8"></script>
        <?php endforeach ?>

        <?php if ($this->session->userdata('user_id')): ?>
        <?php endif ?>

        <?php if ($event): ?>
        <div id="eventGrab" style="position:absolute; z-index:9999; display: none;">
            <form action="/events/collect" method="POST">
                <input type="hidden" name="url" value="/<?php echo $this->uri->uri_string() ?>" />
                <input type="image" src="/images/event/easter_egg.png" />
            </form>
        </div>
        <?php endif ?>
        <div id="jumpToBox">
            <div class="jumpToBox-arrow"></div>
            <span>Go to page:</span>
            <input type="text" name="page" id="jumpToInput" class="numbersOnly"/>
            <a href="#">Go!</a>
        </div>
    </body>
</html>
