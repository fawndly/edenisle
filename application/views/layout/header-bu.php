<!DOCTYPE html>
<html lang="en" id="crysandrea">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <title><?= (isset($page_title) && strlen($page_title) > 1 ? $page_title." - " : '') ?>Sapherna</title>
        <meta name="author" content="">
        <meta name="description" content="Sapherna connects you to a world filled with people from all over the planet gathered to chat, play, and share their creativity by showing off some of the most unique avatars on the web.">
        <meta name="keywords" content="Sapherna, avatar community, roleplaying game, games, virtual avatar">
        <meta name="robots" content="FOLLOW, INDEX" />
        <meta name="keywords" content="Sapherna, forums, avatar, hangout" />
        
        <?php foreach ($styles as $stylesheet): ?><link rel="stylesheet" href="<?= $stylesheet ?>" type="text/css"><?php endforeach ?>
        <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico?new" />
    </head>
    <body class="<?= $page_body ?>">
        <div id="structure" class="clearfix">
            <div id="sidebar">
                <h1 id="logo"><a class="hide-text" href="/home?ref=logo" title="Home">Sapherna</a></h1>
                <?php
                    if ($this->session->userdata('user_id')) 
                        $this->load->view('layout/sidebar_signed_in');
                    else
                        $this->load->view('layout/sidebar_signed_out');
                ?>
            </div>
            <div id="content_holder">
                <div id="content_header">
                    <ul id="top_navigation">
                        <li id="home"><a href="/home">Home</a></li>
                        <li id="forums"><a href="/forum">Forums</a></li>
                        <li id="shops"><a href="/shops">Shops</a></li>
                        <li id="donate"><a href="/donate">Donate</a></li>
                    </ul>
                    <?php //if ($this->session->userdata('user_id')): ?>
                    <?php if (0): ?>
                        <input type="text" id="main_search" placeholder="Looking for something?" />
                    <?php endif ?>
                </div>
                <div id="content">
