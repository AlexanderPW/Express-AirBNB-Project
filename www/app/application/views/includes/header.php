<!DOCTYPE html>
<html <? if(isset($angular_app)) { ?>ng-app="<?=$angular_app?>"<? } ?>>
<head>
    <title><?=$this->config->item('site_title') . " - " . $page_title?></title>
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
<body>

<header id="header">
    <div class="container">
        <? if(get_user_id()) { ?>
            <a href="<?=site_url('dashboard')?>" class="brand pull-left" title="<?=$this->config->item('site_title')?>"><?=$this->config->item('site_title')?></a>
        <? } else { ?>
            <a href="<?=$this->config->item('domain_url')?>" class="brand pull-left" title="<?=$this->config->item('site_title')?>"><?=$this->config->item('site_title')?></a>
        <? } ?>
        <? if(get_user_id()) { ?>
            <a id="logout-link" href="<?= site_detect_url('accounts/logout')?>">Logout</a>

            <div class="btn-group pull-right light-group">
                <a href="<?= site_detect_url('dashboard')?>" class="btn btn-lg <? if(isset($page_active_dashboard)) { echo active_nav($page_active_dashboard); }?>" id="btn-dashboard">Dashboard</a>
                <a href="<?= site_detect_url('my_account')?>" class='btn btn-lg <? if(isset($page_active_my_account)) { echo active_nav($page_active_my_account); }?>' id="btn-my-acount">My Account</a>
            </div>
        <? } ?>
    </div>
    <div class="clearfix"></div>
</header>

<div id="page">