<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/news.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
        </div>
        <div class="content">
            <div id="tabs" class="htabs">
                <a href="#tab_general"><?php echo $tab_general; ?></a>
                <a href="#tab_data"><?php echo $tab_data; ?></a>
                <a href="#tab-image"><?php echo $tab_image; ?></a>
            </div>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <div id="tab_general">
                    <div id="languages" class="htabs">
                        <?php foreach ($languages as $language) { ?>
                            <a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
                        <?php } ?>
                    </div>

                    <?php foreach ($languages as $language) { ?>
                        <div id="language<?php echo $language['language_id']; ?>">
                            <table class="form">
                                <tr>
                                    <td><?php echo $entry_category; ?></td>

                                    <td><select name="flyer_description[<?php echo $language['language_id']; ?>][category_id]">
                                            <?php foreach ($flyer_category_data[$language['language_id']] as $category) { ?>

                                                <option value="<?php echo $category['category_id']; ?>"
                                                    <?php if (isset($flyer_description[$language['language_id']]['category_id'])){
                                                        if($flyer_description[$language['language_id']]['category_id']== $category['category_id']){ ?> selected="selected" <?php }
                                                    }?> ><?php echo $category['title']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="required">*</span> <?php echo $entry_title; ?></td>
                                    <td><input name="flyer_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($flyer_description[$language['language_id']]) ? $flyer_description[$language['language_id']]['title'] : ''; ?>" />
                                        <?php if (isset($error_title[$language['language_id']])) { ?>
                                            <span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
                                        <?php } ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $entry_meta_description; ?></td>
                                    <td><textarea name="flyer_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5"><?php echo isset($flyer_description[$language['language_id']]) ? $flyer_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
                                </tr>
                                <tr>
                                    <td><span class="required">*</span> <?php echo $entry_description; ?></td>
                                    <td><textarea name="flyer_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($flyer_description[$language['language_id']]) ? $flyer_description[$language['language_id']]['description'] : ''; ?></textarea>
                                        <?php if (isset($error_description[$language['language_id']])) { ?>
                                            <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
                                        <?php } ?></td>
                                </tr>
                            </table>
                        </div>
                    <?php } ?>
                </div>
                <div id="tab_data">
                    <table class="form">
                        <tr>
                            <td><?php echo $entry_keyword; ?></td>
                            <td><input type="text" name="keyword" value="<?php echo $keyword; ?>" /></td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_store; ?></td>
                            <td><div class="scrollbox">
                                    <?php $class = 'even'; ?>
                                    <div class="<?php echo $class; ?>">
                                        <?php if (in_array(0, $flyer_store)) { ?>
                                            <input type="checkbox" name="flyer_store[]" value="0" checked="checked" />
                                            <?php echo $text_default; ?>
                                        <?php } else { ?>
                                            <input type="checkbox" name="flyer_store[]" value="0" />
                                            <?php echo $text_default; ?>
                                        <?php } ?>
                                    </div>
                                    <?php foreach ($stores as $store) { ?>
                                        <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                        <div class="<?php echo $class; ?>">
                                            <?php if (in_array($store['store_id'], $flyer_store)) { ?>
                                                <input type="checkbox" name="flyer_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                                                <?php echo $store['name']; ?>
                                            <?php } else { ?>
                                                <input type="checkbox" name="flyer_store[]" value="<?php echo $store['store_id']; ?>" />
                                                <?php echo $store['name']; ?>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </div></td>

                        </tr>
                        <tr>
                            <td><?php echo $entry_status; ?></td>
                            <td><select name="status">
                                    <?php if ($status) { ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_top_show; ?></td>
                            <td><input type="checkbox" value ="1" name="top_show" <?php echo $top_show==1 ? "checked" : "";?>></td>
                        </tr>
                        <tr>
                            <td><span class="required">*</span><?php echo $entry_start_time; ?></td>
                            <td valign="top"><input type="text" name="flyer_start_time" value="<?php echo $flyer_start_time; ?>"  size="12" class="date" />
                                <?php if (isset($error_flyer_start_time)) { ?>
                                    <span class="error"><?php echo $error_flyer_start_time; ?></span>
                                <?php } ?>
                        </tr>
                        <tr>
                            <td><span class="required">*</span><?php echo $entry_end_time; ?></td>
                            <td valign="top"><input type="text" name="flyer_end_time" value="<?php echo $flyer_end_time; ?>"  size="12" class="date" />
                                <?php if (isset($error_flyer_end_time)) { ?>
                                    <span class="error"><?php echo $error_flyer_end_time; ?></span>
                                <?php } ?>
                        </tr>
                        <tr>
                            <td><?php echo $entry_image; ?></td>
                            <td valign="top"><input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
                                <img src="<?php echo $preview; ?>" alt="" id="preview" class="image" onclick="image_upload('image', 'preview');" /></td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_image_thumbnail; ?></td>
                            <td valign="top"><input type="hidden" name="image_thumbnail" value="<?php echo $image_thumbnail; ?>" id="image_thumbnail" />
                                <img src="<?php echo $image_thumbnail_preview; ?>" alt="" id="image_thumbnail_preview" class="image" onclick="image_upload('image_thumbnail', 'image_thumbnail_preview');" /></td>
                        </tr>

                        <tr>
                            <td><?php echo $entry_pdf_filename_label; ?></td>
                            <td>
                                <input type="text" class="flyer_pdf_file" style="width:100%" name="flyer_pdf_file" value="<?php echo $flyer_pdf_file; ?>" />
                                <p>
                                    <a id="button-upload" class="button"><?php echo $button_upload; ?></a>&nbsp;<a id="button-delete" class="button">
                                        <?php echo $button_delete; ?>
                                    </a>
                                </p>
                                <?php if ($error_pdf_filename) { ?>
                                    <span class="error"><?php echo $error_pdf_filename; ?></span>
                                <?php } ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_mask; ?></td>
                            <td><input type="text" name="mask" value="<?php echo $mask; ?>" />
                                <?php if ($error_mask) { ?>
                                    <span class="error"><?php echo $error_mask; ?></span>
                                <?php } ?></td>
                        </tr>
                    </table>
                </div>
                <div id="tab-image">
                    <table id="images" class="list">
                        <thead>
                        <tr>
                            <td class="left"><?php echo $entry_image; ?></td>
                            <td class="right"><?php echo $entry_sort_order; ?></td>
                            <td></td>
                        </tr>
                        </thead>
                        <?php $image_row = 0; ?>
                        <?php foreach ($flyer_images as $flyer_image) { ?>
                            <tbody id="image-row<?php echo $image_row; ?>">
                            <tr>
                                <td class="left"><div class="image"><img src="<?php echo $flyer_image['thumb']; ?>" alt="" id="thumb<?php echo $image_row; ?>" onclick="image_upload('image<?php echo $image_row; ?>', 'thumb<?php echo $image_row; ?>');" />
                                        <input type="hidden" name="flyer_image[<?php echo $image_row; ?>][image]" value="<?php echo $flyer_image['image']; ?>" id="image<?php echo $image_row; ?>" />
                                        <br />
                                        <a onclick="image_upload('image<?php echo $image_row; ?>', 'thumb<?php echo $image_row; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
                                <td class="right"><input type="text" name="flyer_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $flyer_image['sort_order']; ?>" size="2" /></td>
                                <td class="left"><a onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
                            </tr>
                            </tbody>
                            <?php $image_row++; ?>
                        <?php } ?>
                        <tfoot>
                        <tr>
                            <td colspan="2"></td>
                                <td class="left"><a onclick="addImage();" class="button"><?php echo $button_add_image; ?></a></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>

            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
    <?php foreach ($languages as $language) { ?>
    CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
        filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
        filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
        filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
        filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
        filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
        filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
    });
    <?php } ?>
    //--></script>
<script type="text/javascript"><!--
    function image_upload(field, preview) {
        $('#dialog').remove();

        $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

        $('#dialog').dialog({
            title: '<?php echo $text_image_manager; ?>',
            close: function (event, ui) {
                if ($('#' + field).attr('value')) {
                    $.ajax({
                        url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>',
                        type: 'GET',
                        data: 'image=' + encodeURIComponent($('#' + field).val()),
                        dataType: 'text',
                        success: function(data) {
                            $('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" class="image" onclick="image_upload(\'' + field + '\', \'' + preview + '\');" />');
                        }
                    });
                }
            },
            bgiframe: false,
            width: 700,
            height: 400,
            resizable: false,
            modal: false
        });
    };
    //--></script>
<script type="text/javascript"><!--
    $('#tabs a').tabs();
    $('#languages a').tabs();
    //--></script>
<?php echo $footer; ?>

<script type="text/javascript" src="view/javascript/jquery/ajaxupload.js"></script>
<script type="text/javascript"><!--
    new AjaxUpload('#button-upload', {
        action: 'index.php?route=module/moshe_flyer/upload&token=<?php echo $token; ?>',
        name: 'file',
        autoSubmit: true,
        responseType: 'json',
        onSubmit: function (file, extension) {
            $('#button-upload').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');
            $('#button-upload').attr('disabled', true);
        },
        onComplete: function (file, json) {
            $('#button-upload').attr('disabled', false);

            if (json['success']) {
                alert(json['success']);

                $('input[name=\'flyer_pdf_file\']').attr('value', json['filename']);
                $('input[name=\'mask\']').attr('value', json['mask']);
            }

            if (json['error']) {
                alert(json['error']);
            }

            $('.loading').remove();
        }
    });
    //remove file
    $('a#button-delete').click(function(event) {
        event.preventDefault();
        deletebutton = $(this);

        if ($("input[name='flyer_pdf_file']").val().trim().length) {
            filename = $("input[name='flyer_pdf_file']").val().trim();
        }
        else{
            filename = '';
        }

        if (filename.length) {
            $.ajax({
                async: true,
                url: 'index.php?route=module/moshe_flyer/removeFile&&token=<?php echo $token; ?>',
                type: 'POST',
                data:{filename:filename},
                dataType: 'json',
                beforeSend: function () {
                    deletebutton.after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');
                    deletebutton.attr('disabled', true);
                },

                success: function(json){
                    deletebutton.attr('disabled',false);
                    $('.loading').remove();
                    if (json['success']) {
                        alert(json['success']);
                        $("input[name='flyer_pdf_file']").attr('value','');
                    }
                    if (json['error']) {
                        alert(json['error']);
                    }
                },

                error: function(json) {
                    deletebutton.attr('disabled',false);
                    $('.loading').remove();
                    alert(json['error']);
                }
            });
        }
    });

    //--></script>

<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript">
<!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
//--><script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
    html  = '<tbody id="image-row' + image_row + '">';
    html += '  <tr>';
    html += '    <td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + image_row + '" onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');" /><input type="hidden" name="flyer_image[' + image_row + '][image]" value="" id="image' + image_row + '" /><br /><a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
    html += '    <td class="right"><input type="text" name="flyer_image[' + image_row + '][sort_order]" value="" size="2" /></td>';
    html += '    <td class="left"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
    html += '  </tr>';
    html += '</tbody>';

    $('#images tfoot').before(html);

    image_row++;
}
//--></script>