<style>
    .dl_attr_groups .disable {
        background: #ccccff;
    }
</style>
<div class="item-conf">
    <?php $group_stt = 0; foreach ($product->variations as $key=>$details) { ?>
    <div class="item-prop">
        <div class="item-label"><?php echo $details['title'] ?></div>
        <div class="btn-groups dl_attr_groups" data-toggle="buttons">
            <?php foreach ($details['values'] as $slug => $option) { ?>
            <label class="btn btn-outline-secondary">
                <input data-group="{{ $group_stt }}" name="<?php echo $key ?>" class="attribute <?php echo $key ?>" value="<?php echo $slug ?>" type="radio"<?php if ($product->$key == $option) echo ' checked'; ?> autocomplete="off"><?php echo $option ?>
            </label>
            <?php } ?>
        </div>
    </div>
    <?php $group_stt++; } ?>
</div>
<input type="hidden" id="click_group" value="0">

<script>
    $(document).ready(function() {
        change_attribute(true);
    });
</script>

<div style="display:none">
    <?php foreach ($product->variation_matrix as $key=>$details) { ?>
    <img src="<?php echo $details['image'] ?>" id="<?php echo $key ?>" data-status="<?php echo $details['status'] ?>" data-asin="<?php echo $details['id'] ?>" data-price="<?php echo $details['price'] ?>" data-title="<?php echo $details['title'] ?>">
    <?php } ?>
</div>

<div id="status-old" class="item-conf" style="display:none">
    <div class="item-prop">
        <div class="item-label">Status</div>
        <div class="btn-groups" data-toggle="buttons">
            <label class="btn btn-outline-secondary">
                <input class="attribute" type="radio" value="" checked autocomplete="off">Used
            </label>
        </div>
    </div>
</div>