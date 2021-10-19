<div class="wrap">
    <h1><?php _e('Glue Cache Settings', 'gluecache'); ?></h1>
    <div class="gc-content">
        <form action="" method="post">
            <table class='form-table'>
                <tbody>
                    <tr>
                        <th scope='row'>
                            <label for="purge_on_deletion">
                                <?php _e( 'Purge all cache on deletion', 'gccache' ); ?>
                            </label>
                        </th>
                        <td>
                            <input
                                type="checkbox"
                                id="purge_on_deletion"
                                name="purge_on_deletion"
                                class='regular-text'
                                value='1'
                                <?php echo isset( $settings['purge_on_deletion'] ) ? 'checked="checked"' : '' ?>
                            />
                        </td>
                    </tr>
                    <tr>
                        <th scope='row'>
                            <label for="purge_on_deletion">
                                <?php _e( 'Show message on console', 'gccache' ); ?>
                            </label>
                        </th>
                        <td>
                            <input
                                type="checkbox"
                                id="show_console_msg"
                                name="show_console_msg"
                                class='regular-text'
                                value='1'
                                <?php echo isset( $settings['show_console_msg'] ) ? 'checked="checked"' : '' ?>
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
            <p>
                <input type="submit" name="gc_save_setting" id="submit" class="button button-primary" value="Save Changes">
            </p>
        </form>
    </div>
</div>