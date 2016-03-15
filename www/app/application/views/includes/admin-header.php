<!DOCTYPE html>
<html <? if(isset($angular_app)) { ?>ng-app="<?=$angular_app?>"<? } ?>>
<head>
    <title><?=$this->config->item('site_title') . " Administration - " . $page_title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/assets/images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/assets/images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/assets/images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="/assets/images/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="/assets/images/ico/favicon.png">

    <script type="text/javascript">
        var SITE_URL = '<?= site_detect_url() ?>';
    </script>
    <? $this->carabiner->display('css'); ?>
    <? $this->carabiner->display('header_js'); ?>
</head>
<body id="admin">

<header id="header">
    <div class="container">
        <a href="/" class="brand pull-left" title="<?=$this->config->item('site_title')?>"><?=$this->config->item('site_title')?></a>

        <div class="user-meta pull-right">
            <a id="logout-link" href="<?= site_detect_url('app/logout')?>">Logout</a>
            <a id="change-email-password" href="#change_password_modal" data-toggle="modal">Change Email/Password</a>

            <div class="user-name">
                <h4><?= get_user_name()?></h4>
            </div>

        </div>
    </div>
    <div class="clearfix"></div>
</header>

<div id="page">