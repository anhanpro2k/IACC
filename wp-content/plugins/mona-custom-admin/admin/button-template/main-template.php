<div class="mbutton-inner-main">
    <h4 class="mbutton-active text-title">
        <?php echo __( 'Kích hoạt Nút', 'monamedia' ) ?>
    </h4>
    <label class="mbutton-switch" for="mona-admin-button-active">
        <input type="checkbox" id="mona-admin-button-active"
            name="mona_button_active" value="yes"
            <?php checked( (new Mona_Admin_Button())::mona_get_setting( (new Mona_Admin_Button())->active_name ), 'yes' ) ?> />
        <span class="--slider"></span>
    </label>
</div>
