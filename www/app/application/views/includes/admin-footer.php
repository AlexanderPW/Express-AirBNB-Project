</div>
</div>

<div id="footer">
</div>

<div class="modal fade" id="change_password_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title">Update Password/Email?</h2>
            </div>
            <div class="modal-body">
                <div class="alert-container"></div>
                <p>You can update the username, email address and password below.  If you leave the password fields blank, no password update will occur.</p>

                <form class="form" id="change_password_form">
                    <div class="form-group">
                        <label>Username<span class="required">*</span></label>
                        <input type="text" name="username" id="change-password-username" value="<?=get_user()->username?>" class="form-control"  data-rule-required="true" data-rule-minlength="4"/>
                    </div>
                    <div class="form-group">
                        <label>Email Address<span class="required">*</span></label>
                        <input type="email" name="email" id="change-password-email-address" value="<?=get_user()->email?>" class="form-control"  data-rule-required="true" data-rule-email="true"/>
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="password">Password</label>
                        <input type="password" class="form-control" id="change-password-password" placeholder="Password" name="password" data-rule-minlength="5">
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="confirm_password">Confirm Password</label>
                        <input type="password" class="form-control" id="change-password-confirm-password" placeholder="Confirm Password" name="confirm_password" data-rule-equalTo="#change-password-password">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" data-loading-text="Saving...">Update</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
app.user = <?= get_user_json() ?>;
app.site_title = '<?= $this->config->item('site_title')?> Administration';
</script>
<? $this->carabiner->display('js'); ?>
</body>
</html>