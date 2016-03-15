<div class="body login">
    <div class="container">

        <? if ($this->session->flashdata('success')) { ?>
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2"></div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                    <div class="alert alert-success"><?= $this->session->flashdata('success')?></div>
                </div>
            </div>
        <? } ?>

        <div class="row">
            <div class="col-lg-4 col-md-2 col-sm-2"></div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div id="login-box">
                    <div class="spacer-50"></div>
                    <p>
                        <img src="/assets/images/ECH-logo-large.png" style="width:100%;"/>
                    </p>
                    <div class="spacer-30"></div>

                    <form method="post">
                        <div class="form-group">
                            <div class="input-group username">
                                <label class="sr-only" for="login-username">Username</label>
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" id="login-username" placeholder="username" name="username">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group password">
                                <label class="sr-only" for="login-username">Password</label>
                                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                <input type="password" class="form-control" id="login-password" placeholder="password" name="password">
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <button class="pull-right btn btn-primary btn-lg">Signin</button>
                        <div class="clearfix"></div>
                    </form>
                </div>


                <div class="clearfix"></div>
                <? if ($this->session->flashdata('error')) { ?>
                    <div class="alert alert-danger"><?= $this->session->flashdata('error')?></div>
                <? } ?>

                <div class="clearfix"></div>

            </div>

        </div>
    </div>
</div>