<?php
$mona_product_image_quantity = get_field( 'mona_product_image_quantity' );
$mona_product_image_quantity = $mona_product_image_quantity > 0 ? $mona_product_image_quantity : 64;
?>
<div class="prodt-uploadimg mb-12">
    <p class="tit mb-16"><span class="fw-6"><?php echo __( 'Upload ảnh', 'monamedia' ); ?></span>
        (<?php echo __( 'Chọn', 'monamedia' ); ?> <?php echo $mona_product_image_quantity; ?> <?php echo __( 'hình ảnh để In Ảnh
        Cho Con có thể hỗ trợ in giúp bạn nhé', 'monamedia' ); ?>)</p>
    <div class="uploadimg flex">
        <div class="uploadimg-box">
            <div class="uploadimg-wrap">
            </div>
        </div>
        <div class="uploadimg-add uploadimg-item">
            <div class="uploadimg-item-inner">
                +
            </div>
        </div>
        <div class="uploadimg-num0f"><span class="num">0</span>/ <span class="total"
                                                                       data-max-quan="<?php echo $mona_product_image_quantity; ?>"><?php echo $mona_product_image_quantity; ?></span>
        </div>
        <input type="file" name="images" multiple="multiple" accept="image/jpg, image/jpeg, image/png" hidden>
    </div>
</div>