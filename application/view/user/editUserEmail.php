<!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

        <h2>Change your email address</h2>

        <form action="<?php echo Config::get('URL'); ?>user/editUserEmail_action" method="post">
            <label>
                New email address: <input type="text" name="user_email" required />
            </label>
            <input type="submit" value="Submit" />
        </form>