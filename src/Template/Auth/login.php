<be-head>
    <?php
    $wwwUrl = \Be\Be::getProperty('App.Bookmark')->getWwwUrl();
    ?>
    <script src="<?php echo $wwwUrl; ?>/js/Auth/login.js"></script>
    <script>
        const authLoginUrl = "<?php echo beUrl('Bookmark.Auth.login'); ?>";
    </script>
</be-head>


<be-page-content>
    <div class="be-row">
        <div class="be-col-0 be-md-col-2 be-lg-col-4 be-xl-col-6">
        </div>
        <div class="be-col-24 be-md-col-20 be-lg-col-16 be-xl-col-10">

            <h4 class="be-h4">登录书签</h4>

            <form id="auth-login-form">
                <div class="be-floating be-mt-150">
                    <input type="password" name="password" class="be-input" placeholder="密码" />
                    <label class="be-floating-label">密码 <span class="be-c-red">*</span></label>
                </div>

                <div class="be-mt-150">
                    <button type="submit" class="be-btn be-btn-main be-btn-lg be-mt-150 be-w-100">登录</button>
                </div>
            </form>
        </div>
    </div>
</be-page-content>