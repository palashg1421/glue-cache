<style>
</style>
<div class="wrap">
    <h1>
        <?php _e('Glue Cache Settings', 'gluecache'); ?>
    </h1>
    <div class="gc-content">
        <form action="" method="post">
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label for="gc_do_console">
                                <?php _e('Show cached message on console', 'gluecache'); ?>
                            </label>
                        </th>
                        <td>
                            <input
                                type="checkbox"
                                id="gc_do_console"
                                name="gc_do_console"
                                value='1'
                                <?php echo $settings['gc_do_console'] == 1   ? 'checked' : '' ?>
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php wp_nonce_field('gluecache_nonce'); ?>
            <div class="button_group">
                <p class="submit">
                    <input type="submit" name="saveGlueSettings" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'gluecache'); ?>">
                </p>
                <p class="submit">
                    <input type="submit" name="purgeGlueCache" class="button button-danger" value="<?php _e('Purge Cache', 'gluecache'); ?>" />
                </p>
            </div>
        </form>
    </div>
</div>