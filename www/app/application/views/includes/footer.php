    </div>
</div>

<div id="footer"></div>
<script type="text/javascript">
app.user = <?= get_user_json() ?>;
app.site_title = '<?= $this->config->item('site_title')?>';
</script>
<? $this->carabiner->display('js'); ?>
</body>
</html>