 <!DOCTYPE html><!-- Crysandrea engine by Tyler Diaz (https://github.com/Pixeltweak/Crysandrea) -->
<html lang="en">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Fawn">
        <meta name="description" content="Sapherna connects you to a world filled with people from all over the planet gathered to chat, play, and share their creativity by showing off some of the most unique avatars on the web.">
        <meta name="keywords" content="Sapherna, avatar community, roleplaying game, games, virtual avatar">
        <meta name="robots" content="FOLLOW, INDEX" />
        <meta name="copyright" content="Sapherna 2015" />
        <title>Sapherna</title>
        <link rel="stylesheet" href="/global/css/homepage.css" type="text/css">
        <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    </head>
    <body>

        <div class="header">
            <div class="centered-content grid">
                <div class="unit half align-center">
                    <h1 id="logo"><a href="/?ref=logo" class="hide-text" title="Home">Sapherna</a></h1>
                </div>
                    <div class="unit half align-center">
                    <form action="/auth/signin?ref=homepage" method="POST" id="login-form">
                        <div class="helper_bar">
                            <a href="/auth/forgot_password">I forgot my password</a>
                            &bull;
                            <a href="/auth/signup">I want to register!</a>
                        </div>
                        <input type="text" class="signin_data" placeholder="Username or Email" name="username" tabindex="1" />
                        <input type="password" class="signin_data" placeholder="Password" name="password" tabindex="2" />
                        <button type="submit" id="signin_button">Sign in</button>
                    </form>
                </div>
            </div>
            <div class="border-thin"></div>
        </div>
        <div class="main-content">
            <div class="centered-content grid">
                <div class="unit half announcements align-center">
                    <div class="content">
                        <h2>Announcements</h2>
                        <?php $latest_announcement = $this->db->query('SELECT topics.last_post,
                                                                              topics.topic_title,
                                                                              topics.topic_id,
                                                                              topics.forum_id,
                                                                              topics.topic_author,
                                                                              topics.topic_status,
                                                                              topics.topic_time,
                                                                              forums.forum_name
                                                                        FROM  topics
                                                                        JOIN forums ON topics.forum_id = forums.forum_id
                                                                        AND forums.forum_id = 1
                                                                        ORDER BY topics.topic_id DESC
                                                                        LIMIT 6')->result_array(); ?>
                        <?php foreach ($latest_announcement as $latest_announcement): ?>
                        <a href="/topic/view/<?php echo $latest_announcement['topic_id'] ?>"><?php echo $latest_announcement['topic_title'] ?></a><br />
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="unit half">
                    <h3>Sapherna connects you to a world filled with people from all over the planet gathered to chat, play, and share their creativity by showing off some of the most unique avatars on the web.</h3>
                    <h4>Thousands of awesome people already have their own avatar. Join today to see what all the fun is about!</h4>
                    <div class="register-sign">
                        <img src="global/css/images/bg/avatar.png" align="center" style="display: inline;">
                        <a href="/auth/signup" id="signup">Join for free</a>
                    </div>
                </div>
            </div>
        </div>
        <div id="footer">
            <div class="centered-content grid align-center">
                <div class="unit half">
                    <strong>Links for the curious:</strong> <a href="/forum">Forums</a> &bull; <a href="/shops">Shops</a> &bull; <a href="/market">Marketplace</a>  &bull; <a href="/donate">Monthly items</a>
                </div>
                <div class="unit half">
                    &copy;2015 Sapherna (<a href="/general/tos">Code of Conduct</a> &bull; <a href="/general/privacy">Private Policy</a>)
                </div>
            </div>
        </div>
        <div class="page-end">
        </div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="global/js/placeholder.js" type="text/javascript" charset="utf-8"></script>
    <script src="global/js/home/landing_page.js" type="text/javascript" charset="utf-8"></script>
</html>
