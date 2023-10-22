 <!-- Crysandrea engine by Tyler Diaz (https://github.com/Pixeltweak/Crysandrea) -->
<style type="text/css" media="screen">
a.reveal_spoiler {
    background: #1e406d; /* Old browsers */
    padding:5px 12px;
    border-radius:3px;
    color:#afbecf;
}
a.reveal_spoiler:hover {
    text-decoration:none;
    background: #22498e; /* Old browsers */
    color:white;
}
a.reveal_spoiler:active {
    text-decoration:none;
    background: #122341; /* Old browsers */
    color:#7d90a9;
}
.spoiler_value {
    display:none;
    margin-top:10px;
    background:rgba(255, 255, 255, 0.7);
    padding:4px 8px;
    border-radius:4px;
}
.post-grid { float:left; margin:0 0 7px; padding:0 0 5px; clear:both; overflow:hidden; }
.post-grid .topic_avatar { float:left }

.post-content {
    background: #edf6ff; /* Old browsers */
    background: -moz-linear-gradient(top,  #edf6ff 1%, #e0f1ff 11%, #e0f1ff 85%, #d7e7f4 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(1%,#edf6ff), color-stop(11%,#e0f1ff), color-stop(85%,#e0f1ff), color-stop(100%,#d7e7f4)); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(top,  #edf6ff 1%,#e0f1ff 11%,#e0f1ff 85%,#d7e7f4 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(top,  #edf6ff 1%,#e0f1ff 11%,#e0f1ff 85%,#d7e7f4 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(top,  #edf6ff 1%,#e0f1ff 11%,#e0f1ff 85%,#d7e7f4 100%); /* IE10+ */
    background: linear-gradient(to bottom,  #edf6ff 1%,#e0f1ff 11%,#e0f1ff 85%,#d7e7f4 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#edf6ff', endColorstr='#d7e7f4',GradientType=0 ); /* IE6-9 */
    border: 1px solid #c1cfdb;
    border-top: 1px solid #cfdbe6;
    float:left;
    margin:0 0 5px 5px;
    margin-right:-20px;
    border-radius:8px;
    -moz-border-radius:8px;
    -webkit-border-radius:8px;
    padding:4px 12px 20px 15px;
    width:510px;
    position:relative;
    min-height:70px;
    box-shadow:0 2px 1px 1px rgba(0, 0, 0, 0.075);
    line-height:1.5
}
.post-content img { max-width:510px; max-height:900px; }
.post-toolbar { color:#aaa; float:left; line-height:25px; padding:5px 15px 0; font-size:0.923em; width:513px }
.post_author { display:block; padding:6px 0 3px; font-size:1.154em; font-weight:bold; }
.post_author a { color:#111; border-radius:3px; padding:0 1px }
.post_author a:hover { background: #0f7d99; /* Old browsers */
background: -moz-linear-gradient(top,  #0f7d99 0%, #00617b 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#0f7d99), color-stop(100%,#00617b)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  #0f7d99 0%,#00617b 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  #0f7d99 0%,#00617b 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  #0f7d99 0%,#00617b 100%); /* IE10+ */
background: linear-gradient(to bottom,  #0f7d99 0%,#00617b 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#0f7d99', endColorstr='#00617b',GradientType=0 ); /* IE6-9 */
 color:white; text-decoration:none; }
.post_author a:active {
    background: #035369; /* Old browsers */
    background: -moz-linear-gradient(top,  #035369 0%, #116b81 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#035369), color-stop(100%,#116b81)); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(top,  #035369 0%,#116b81 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(top,  #035369 0%,#116b81 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(top,  #035369 0%,#116b81 100%); /* IE10+ */
    background: linear-gradient(to bottom,  #035369 0%,#116b81 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#035369', endColorstr='#116b81',GradientType=0 ); /* IE6-9 */
    color:#ccc;
}

.ribbons { position:absolute; right:0px; top:0px; z-index:2; -webkit-transition: opacity 200ms ease; -moz-transition: opacity 200ms ease; -ms-transition: opacity 200ms ease; -o-transition: opacity 200ms ease; transition: opacity 200ms ease;}
.ribbons:hover .ribbon { opacity:0.2; }
.quote-1 { border-left:2px solid rgba(0, 0, 0, 0.15); font-size:0.923em; margin:2px 5px 10px 4px; padding:8px 8px 10px 10px; background:rgba(0, 0, 0, 0.1) url(/global/css/images/icons/quote_icon.png)no-repeat right bottom; }
.user_signature {
    width:535px;
    float:right;
    text-align:center;
    overflow:hidden;
    max-height:275px;
}
.post-content:after, .post-content:before {
    right: 100%;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
}

.post-content:after {
    border-color: rgba(224, 241, 255, 0);
    border-right-color: #e0f1ff;
    border-width: 8px;
    top: 60px;
    margin-top: -8px;
}
.post-content:before {
    border-color: rgba(193, 207, 219, 0);
    border-right-color: #c1cfdb;
    border-width: 9px;
    top: 60px;
    margin-top: -9px;
}

#user_posts_head {
    background:#81B860;
    border-bottom:1px solid #518A3A;
    height:40px;
    overflow:hidden;
    -moz-border-radius-topright:5px;
    -moz-border-radius-topleft:5px;
    -webkit-border-top-right-radius:5px;
    -webkit-border-top-left-radius:5px;
    color:#fff;
    text-shadow:-1px -1px 0px #62933E
}
.t_locked {
    text-align:center;
    background:#eee;
    border:1px solid #ddd;
    padding:15px 0 0;
    margin-bottom:15px;
    -moz-border-radius:6px;
    -webkit-border-radius:6px;
    color:#777;
}
.t_locked h4{
    color:#444;
}
.t_signed_out {
    text-align:center;
    background:#ffd;
    border:1px solid #ee7;
    padding:15px 0 10px;
    margin-bottom:15px;
    -moz-border-radius:6px;
    -webkit-border-radius:6px;
    color:#757754;
    font-size:12px;
}
.t_signed_out h4{
    color:#444;
    margin:0;
    font-size:14px;
    line-height:2;
}

#go_to_page_label{
    color: #0076AB;
    font-weight: bold;
    padding-left: 5px;
}

    .user_online {
        background:#E1FFCE;
        color:#547823;
        -moz-border-radius:10px;
        -webkit-border-radius:10px;
        padding:2px 8px;
        font-size:11px;
    }
    .delete_post {
        font-weight:bold;
        background:#FFCDCF;
        color:#5D0405 !important;
        font-size:11px;
        padding:1px 6px 2px;
        -moz-border-radius:10px;
        -webkit-border-radius:10px;
    }
    a.bookmark {
        background:#174386 url(/global/css/images/icons/bookmark_icon_hack.png) left center;
        padding-left:20px;
        font-size:12px;
        margin-top:8px;
        margin-right:5px;
        -webkit-transition: all 200ms ease;
        -moz-transition: all 200ms ease;
        -ms-transition: all 200ms ease;
        -o-transition: all 200ms ease;
        transition: all 200ms ease;
    }
    a.bookmark:hover {
        background-color:orange;
        color:orange;
    }
    .bug_error, .bug_success { overflow:hidden; padding:3px; -webkit-border-radius:5px; -moz-border-radius:5px; border-radius:5px }
    .bug_error { background:#FFD8D9; color:#5C2D28; border:2px solid #ffa5a7; }
    .bug_success { background:#C2E79A; color:#3C601D; border:2px solid #9FC676 }

    .new_topic_posts {
        padding:10px 15px;
        margin:10px 0;
        cursor: pointer;
        display: none;
    }
    .success_bookmark {
        padding:8px 4px 0;
        font-size:12px;
        color:green;
    }
    .success_bookmark img {
        margin-top:-1px;
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

<div class="feature_header">
    <h2 class="friends_icon"><a href="/forum/view/<?php echo $topic['forum_id'] ?>"><?php echo $topic['short_name'] ?></a>: <?php echo stripslashes(sanitize($topic['topic_title'])) ?></h2>

    <?php if ($suscribed): ?>
        <a href="#" class="right bookmark hide">Bookmark this topic</a>
        <span class="right success_bookmark"><img src="/images/icons/tiny_success.png" alt="" /> Bookmarked (<a href="#" class="remove_bookmark">remove</a>)</span>
    <?php else: ?>
        <a href="#" class="right bookmark">Bookmark this topic</a>
        <span class="right success_bookmark hide"><img src="/images/icons/tiny_success.png" alt="" /> Bookmarked (<a href="#" class="remove_bookmark">remove</a>)</span>
    <?php endif ?>
</div>

<?php if ($total_posts > $posts_per_page): ?>
    <div class="topic_head_bottom" style="margin-top:-5px; border-bottom:1px solid #ddd;">
        <?php echo $this->pagination->create_links(); ?>
    </div>
<?php endif ?>

<!-- <div class="alert">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>Warning!</strong> Topics are going under some minor tuning. Gears might fly out, it won't last too long!
</div>
 -->
<div style="margin-top:10px; overflow:hidden;" id="topic_post_container">
    <?php $this->load->view('forum/partials/topic_posts'); $user['user_level']=$this->session->userdata('user_level');?>
</div>

<span><?php echo $this->pagination->create_links(); ?></span>

<?php if($topic['topic_status'] == "locked"): ?>
<div class="grid_10">
    <div class="t_locked">
        <h4>This topic has been locked</h4>
        <p>In other words, no replies can be posted to this topic.</p>
    </div>
</div>
<?php elseif($user['user_level'] == "verify"): ?>
<div class="grid_10">
    <div class="t_signed_out">
        <h4>You can't post here yet!</h4>
        <p>In other words, you need to make sure that you verified your email!</p>
    </div>
</div>
<?php elseif( ! $this->session->userdata('user_id')): ?>
<div class="grid_10">
    <div class="t_signed_out">
        <h4>It'll be a lot more fun with you!</h4>
        <p>Having your voice on our little forum would be awesome. Don't have an account? <?=anchor('signup', 'Sign up now!')?></p>
    </div>
</div>
<?php else: ?>

<div class="success new_topic_posts">There has been <span id="total_new_posts">1 new post</span> since you last refreshed. (click this notice to load them)</div>

<div class="grid_10 clearfix">
    <form action="/forum/topic_reply/<?php echo $topic['topic_id'] ?>" method="post" id="send_post_message" accept-charset="utf-8">
        <textarea name="message" tabindex="1" class="input" id="message" style="width:98%; font-family:'lucida grande', arial, sans-serif; height:90px;" placeholder="What would you like to say?"></textarea>
        <span class="right">
        <a href="#show_bbcode" data-toggle="modal" id="bbcode_popup">BBCode</a>
            <input type="submit" tabindex="2" class="main_button" value="Submit your post" id="submit_post">
        </span>
    </form>
</div>
<? endif; ?>

<?php if ($this->system->is_staff()): ?>
    <hr>
    <strong>Staff only tools:</strong>
    <div class="clearfix">

        <form style="display:inline-block" action="/forum/toggle_lock" method="POST">
            <div class="control-group">
                <input type="hidden" name="topic_id" value="<?php echo $topic['topic_id'] ?>" />
                <input type="hidden" name="url" value="<?php echo $_SERVER['REQUEST_URI'] ?>" />
                <?php if ($topic['topic_status'] == 'unlocked'): ?>
                    <button class="btn"><i class="icon-lock"></i> Lock Topic</button>
                <?php else: ?>
                    <button class="btn btn-primary"><i class="icon-white icon-lock"></i> Unlock Topic</button>
                <?php endif ?>
            </div>
        </form>

        <form style="display:inline-block" action="/forum/move_topic" method="POST">
            <div class="control-group">
                <input type="hidden" name="topic_id" value="<?php echo $topic['topic_id'] ?>" />
                <input type="hidden" name="url" value="<?php echo $_SERVER['REQUEST_URI'] ?>" />
                <?php $category = $this->db->select('forum_id, forum_name')->get('forums')->result_array(); ?>

                <select id="forum_id" name="forum_id" style="vertical-align:top; width:170px;">
                    <option value="none">Forum name...</option>
                    <?php foreach($category as $forum): ?>
                    <option value="<?php echo $forum['forum_id'] ?>"><?php echo $forum['forum_name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="btn"><i class="icon-share-alt"></i> Move Topic</button>
            </div>
        </form>


        <form class="pull-right" action="/forum/spotlight_topic" method="POST">
            <div class="control-group">
                <input type="hidden" name="topic_id" value="<?php echo $topic['topic_id'] ?>" />
                <input type="hidden" name="url" value="<?php echo $_SERVER['REQUEST_URI'] ?>" />
                <button class="btn btn-warning"><i class="icon-star icon-white"></i> Make topic spotlight</button>
            </div>
        </form>

    </div>
<?php endif ?>

<script type="text/javascript">
var topic = {
    id: <?php echo $topic['topic_id'] ?>,
    status: '<?php echo $topic['topic_status'] ?>',
    last_post: <?php echo $topic['total_posts'] ?>,
    submitting: false,
    post_html: "",
    posters: <?php echo (json_encode(array_values(array_unique($authors)), JSON_NUMERIC_CHECK)); ?>
}
</script>
<!-- Modal -->
<div id="show_bbcode" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="show_bbcode" aria-hidden="true" ><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="myModalLabel" class="donate_small_header" style="margin-top:0">Sapherna's BBcode</h3></div><div class="modal-body"><table class='bbtable' width="100%"><tr class='header'><th style='width: 50%' >You Type</th><th style='width: 50%' >We Show</th></tr><tr class='subhead bar'><th colspan='2'>Alignment</th></tr><tr class='row1'><td class='altrow'><div>[align=center]</div>center<div>[/align]</div></td><td align="center">Center</td></tr><tr class='row1'><td class='altrow'><div>[align=right]</div>Right<div>[/align]</div></td><td align="right">Right</td></tr><tr class='row1'><td class='altrow'><div>[align=left]</div>Left<div>[/align]</div></td><td align="left">Left</td></tr><tr class='subhead bar'><th colspan='2'>Font Styles</th></tr><tr class='row1'><td class='altrow'><div><b>[b]</b></div>This text is bold<div><b>[/b]</b></div></td><td><strong class='bbc'>This text is bold</strong></td></tr><tr class='row1'><td class='altrow'><div><b>[i]</b></div>This text is italicized<div><b>[/i]</b></div></td><td><i>This text is italicized</i></td></tr><tr class='row1'><td class='altrow'><div><b>[u]</b></div>This text is underlined<div><b>[/u]</b></div></td><td><u>This text is underlined</u></td></tr><tr class='row1'><td class='altrow'><div><b>[font=Tahoma]</b></div>This text uses Tahoma font-family styling<div><b>[/font]</b></div></td><td><span style='font-family: Tahoma'>This text uses Tahoma font-family styling</span></td></tr><tr class='row1'><td class='altrow'><div><b>[size=8]</b></div>This is small text<div><b>[/size]</b></div></td><td><span style='font-size: 8px;'>This is small text</span></td></tr><tr class='row1'><td class='altrow'><div><b>[size=30]</b></div>This is large text<div><b>[/size]</b></div></td><td><span style='font-size: 30px;'>This is large text</span></td></tr><tr class='row1'><td class='altrow'><div><b>[!]</b></div>Highlighted Text<div><b>[/!]</b></div></td><td><span style="background-color: #FFFF00">Highlighted Text</span></td></tr><tr class='subhead bar'><th colspan='2'>Font Colors</th></tr><tr class='row1'><td class='altrow'><div><b>[background=red]</b></div>Red background behind this text<div><b>[/background]</b></div></td><td><span style='background-color: red'>Red background behind this text</span></td></tr><tr class='row1'><td class='altrow'><div><b>[color=blue]</b></div>This text is blue<div><b>[/color]</b></div></td><td><span style='color: blue'>This text is blue</span></td></tr><tr class='subhead bar'><th colspan='2'>Code</th></tr><tr class='row1'><td class='altrow'><div><b>[code]</b></div>$text='Some long code here';<div><b>[/code]</b></div></td><td><code>$text='Some long code here';</code></td></tr><tr class='subhead bar'><th colspan='2'>URL</th></tr><tr class='row1'><td class='altrow'><div><b>[url=http://google.com]</b></div> Google <div><b>[/url]</b></div></td><td><a href="http://google.com" target="_blank">Google</a></td></tr><tr class='row1'><td class='altrow'><div><b>[url]</b></div>http://google.com<div><b>[/url]</b></div></td><td><a href="http://google.com" target="_blank">http://google.com</a></td></tr><!--<tr class='subhead bar'><th colspan='2'>Email address *Coming Soon*</th></tr><tr class='row1'><td class='altrow'>Please email <div><b>[email=admin@mysite.com]</b></div>the admin<div><b>[/email]</b></div><br/><br/>My email address is <div><b>[email]</b></div>my@emailaddress.com<div><b>[/email]</b></div></td><td>Please email <a href='mailto:admin@mysite.com' class='bbc_url' title='External link' rel='nofollow external'>the admin</a><br/><br/>My email address is <a href='mailto:' class='bbc_url' title='External link' rel='nofollow external'>my@emailaddress.com</a></td></tr>--><tr class='subhead bar'><th colspan='2'>Horizontal Rule</th></tr><tr class='row1'><td class='altrow'><div>This is</div><b>[hr]</b><div>A horizontal rule</div></td><td>This is<hr/> A horizontal rule</td></tr><tr class='subhead bar'><th colspan='2'>Images</th></tr><tr class='row1'><td class='altrow'><div><b>[img]</b></div><b>http://sapherna.com/../logo.png</b><div><b>[/img]</b></div></td><td><img src='http://sapherna.com/global/css/images/elements/logo.png'/></td></tr><tr class='row1'><td class='altrow'><div><b>[imgright]</b></div><b>http://sapherna.com/../logo.png</b><div><b>[/imgright]</b></div></td><td><img src='http://sapherna.com/global/css/images/elements/logo.png' align="right"/></td></tr><tr class='row1'><td class='altrow'><div><b>[imgcenter]</b></div><b>http://sapherna.com/../logo.png</b><div><b>[/imgcenter]</b></div></td><td align="center"><span style="text-align: center;" ><img style="clear:both;" src="http://sapherna.com/global/css/images/elements/logo.png" alt="Image"/></span></td></tr><tr class='row1'><td class='altrow'><div><b>[imgleft]</b></div><b>http://sapherna.com/../logo.png</b><div><b>[/imgleft]</b></div></td><td><img src='http://sapherna.com/global/css/images/elements/logo.png' align="left"/></td></tr><tr class='subhead bar'><th colspan='2'>Media</th></tr><tr class='row1'><td class='altrow'><div><b>[media]</b></div>http://www.youtube.com/watch?v=kxopViU98Xo<div><b>[/media]</b></div></td><td><iframe id="ytplayer" class="EmbeddedVideo" type="text/html" width="200" height="190" src="http://youtube.com/embed/kxopViU98Xo?html5=1" frameborder="0" allowfullscreen webkitallowfullscreen/></iframe></td></tr><!--<tr class='subhead bar'><th colspan='2'>Member *Coming Soon*</th></tr><tr class='row1'><td class='altrow'><div><b>[member=Honeying]</b></div> runs this site.</td>
<td><a hovercard-ref="member" hovercard-id="1" data-bb="noparse" class="_hovertrigger url fn name bbc_member" href='#' title='Member profile'><span itemprop="name">Honeying</span></a> runs this site.</td></tr><tr class='subhead bar'><th colspan='2'>Post Link *Coming Soon*</th></tr><tr class='row1'><td class='altrow'><div><b>[post=1]</b></div>Click me!<div><b>[/post]</b></div></td><td><a href='#' class='bbc_url' title=''>Click me!</a></td></tr>--><tr class='subhead bar'><th colspan='2'>Quotes</th></tr><tr class='row1'><td class='altrow'><div><b>[quote]</b></div>Quoted post content here <div><b>[/quote]</b></div></td><td><div class="quote"><strong>Anonymous quote:</strong><br><span class="quote">Quoted post content here</span></div></td></tr><tr class='row1'><td class='altrow'><div><b>[quote=Frankmusik]</b></div>Quoted post content here<div><b>[/quote]</b></div></td><td><div class="quote-1"><strong>Frankmusik said:</strong><br>Quoted post content here</div></td></tr><tr class='subhead bar'><th colspan='2'>Spoiler</th></tr><tr class='row1'><td class='altrow'><div><b>[spoiler]</b></div>Some Hidden Text<div><b>[/spoiler]</b></div></td><td><div class="spoiler_container"><a href="#" class="reveal_spoiler">Reveal hidden text <span class="reveal_arrow">&darr;</span></a><div class="spoiler_value">Some Hidden Text</div></div></td></tr><tr class='row1'><td class='altrow'><div><b>[spoiler=Spoiler]</b></div>Some Hidden Text<div><b>[/spoiler]</b></div></td><td><div class="spoiler_container"><a href="#" class="reveal_spoiler">Spoiler <span class="reveal_arrow">&darr;</span></a><div class="spoiler_value">Some Hidden Text</div></div></td></tr><tr class='subhead bar'><th colspan='2'>Strikethrough</th></tr><tr class='row1'><td class='altrow'><div><b>[strike]</b></div>Striked out text<div><b>[/strike]</b></div></td><td><del class='bbc'>Striked out text</del></td></tr><tr class='row1'><td class='altrow'><div><b>[s]</b></div>Striked out text<div><b>[/s]</b></div></td><td><del class='bbc'>Striked out text</del></td></tr><tr class='subhead bar'><th colspan='2'>Subscript</th></tr><tr class='row1'><td class='altrow'>Carbon Dioxide's chemical composition is CO<div><b>[sub]</b></div>2<div><b>[/sub]</b></div></td><td>Carbon Dioxide's chemical composition is CO<sub class='bbc'>2</sub></td></tr><tr class='subhead bar'><th colspan='2'>Superscript</th></tr><tr class='row1'><td class='altrow'>The mathematical way to write &quot;x squared&quot;is x<div><b>[sup]</b></div>2<div><b>[/sup]</b></div></td><td>The mathematical way to write &quot;x squared&quot;is x<sup class='bbc'>2</sup></td></tr><!--<tr class='subhead bar'><th colspan='2'>Topic Link *Coming Soon*</th></tr><tr class='row1'><td class='altrow'><div><b>[topic=1]</b></div>Click me!<div><b>[/topic]</b></div></td><td><a href='http://#' class='bbc_url' title=''>Click me!</a></td></tr>--></table></div><div class="modal-footer"><script>function openWin(){window.open('http://sapherna.com/general/bbcode','','scrollbars=no,width=770,height=670,alwaysRaised=yes');}</script><a href="#" onclick="openWin()">Pop-out BBCode Page</a></div></div><style type="text/css">h3.donate_small_header{font-size:14px;color:#444;text-align:center;margin-top:25px}#bbcode_popup{background:#086B99;width:50px;text-align:center;color:#fff;padding:5px 10px;border-radius:4px;font-size:12px;vertical-align:middle;}#bbcode_popup:hover{background:#2A8BC1;text-decoration:none}#bbcode_popup:active{color:#ddd;background:#13618D;text-decoration:none;box-shadow:inset 0 2px 2px rgba(0,0,0,.1)}th{font-size: 15px;font-weight: bold;padding: 8px 6px;border: 1px solid #f3f3f3;background: #C1DBE7;color: #1d3652;}td{padding: 10px;border: 1px solid #f3f3f3;}
</style>
