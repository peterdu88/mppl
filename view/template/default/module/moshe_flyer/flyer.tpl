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

            <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons"><a onclick="location = '<?php echo $listingCategory; ?>';" class="button"><span><?php echo $button_category; ?></span></a><a onclick="location = '<?php echo $flyer; ?>';" class="button"><span><?php echo $button_flyer; ?></span></a><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
        </div>
        <div class="content">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="form">
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_flyerpage_thumb; ?></td>
                        <td><input type="text" name="flyer_thumb_width" value="<?php echo $flyer_thumb_width; ?>" size="3" />
                            x
                            <input type="text" name="flyer_thumb_height" value="<?php echo $flyer_thumb_height; ?>" size="3" />
                            <?php if ($error_flyerpage_thumb) { ?>
                                <span class="error"><?php echo $error_flyerpage_thumb; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_flyerpage_image; ?></td>
                        <td><input type="text" name="flyer_normal_image_width" value="<?php echo $flyer_normal_image_width; ?>" size="3" />
                            x
                            <input type="text" name="flyer_normal_image_height" value="<?php echo $flyer_normal_image_height; ?>" size="3" />
                            <?php if ($error_flyerpage_normal_image) { ?>
                                <span class="error"><?php echo $error_flyerpage_normal_image; ?></span>
                            <?php } ?></td>
                    </tr>

                    <tr>
                        <td><?php echo $entry_directory; ?><br>
                            The file will be saved in the directory which you saved.
                            The default directory is <i>"moshe_flyer"</i>
                        </td>
                        <td>
                            <?php echo DIR_DOWNLOAD;?><input type="text" readonly="readonly" name="moshe_flyer_file_directory" value="<?php echo $moshe_flyer_file_directory; ?>" />/
                        </td>
                    </tr>

                    <tr>
                        <td><?php echo $entry_module_headline; ?></td>
                        <td><?php if ($flyer_headline_module) { ?>
                                <input type="radio" name="flyer_headline_module" value="1" checked="checked" />
                                <?php echo $text_yes; ?>
                                <input type="radio" name="flyer_headline_module" value="0" />
                                <?php echo $text_no; ?>
                            <?php } else { ?>
                                <input type="radio" name="flyer_headline_module" value="1" />
                                <?php echo $text_yes; ?>
                                <input type="radio" name="flyer_headline_module" value="0" checked="checked" />
                                <?php echo $text_no; ?>
                            <?php } ?></td>
                    </tr>
                </table>
                <table id="module" class="list">
                    <thead>
                    <tr>
                        <td class="left"><?php echo $entry_limit; ?></td>
                        <td class="left"><?php echo $entry_layout; ?></td>
                        <td class="left"><?php echo $entry_position; ?></td>
                        <td class="left"><?php echo $entry_status; ?></td>
                        <td class="left"><?php echo $entry_headline; ?></td>
                        <td class="left"><?php echo $entry_numchars; ?></td>
                        <td class="right"><?php echo $entry_sort_order; ?></td>
                        <td style="width: 150px;"></td>
                    </tr>
                    </thead>
                    <?php $module_row = 0; ?>
                    <?php foreach ($modules as $module) { ?>
                        <tbody id="module-row<?php echo $module_row; ?>">
                        <tr>
                            <td class="left"><input type="text" name="moshe_flyer_module[<?php echo $module_row; ?>][limit]" value="<?php echo $module['limit']; ?>" size="1" /></td>
                            <td class="left"><select name="moshe_flyer_module[<?php echo $module_row; ?>][layout_id]">
                                    <?php foreach ($layouts as $layout) { ?>
                                        <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                                            <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select></td>
                            <td class="left"><select name="moshe_flyer_module[<?php echo $module_row; ?>][position]">
                                    <?php if ($module['position'] == 'content_top') { ?>
                                        <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                                    <?php } else { ?>
                                        <option value="content_top"><?php echo $text_content_top; ?></option>
                                    <?php } ?>
                                    <?php if ($module['position'] == 'content_bottom') { ?>
                                        <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                                    <?php } else { ?>
                                        <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                                    <?php } ?>
                                    <?php if ($module['position'] == 'column_left') { ?>
                                        <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                                    <?php } else { ?>
                                        <option value="column_left"><?php echo $text_column_left; ?></option>
                                    <?php } ?>
                                    <?php if ($module['position'] == 'column_right') { ?>
                                        <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                                    <?php } else { ?>
                                        <option value="column_right"><?php echo $text_column_right; ?></option>
                                    <?php } ?>
                                </select></td>
                            <td class="left"><select name="moshe_flyer_module[<?php echo $module_row; ?>][status]">
                                    <?php if ($module['status']) { ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select></td>
                            <td class="left"><select name="moshe_flyer_module[<?php echo $module_row; ?>][headline]">
                                    <?php if ($module['headline']) { ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select></td>
                            <td class="left"><input type="text" name="moshe_flyer_module[<?php echo $module_row; ?>][numchars]" value="<?php echo $module['numchars']; ?>" size="3" /></td>
                            <?php if (isset($error_module_chars[$module_row])) { ?>
                            <span class="error"><?php echo $error_module_chars[$module_row]; ?></span>
                            <?php } ?></td>
                            <td class="right"><input type="text" name="moshe_flyer_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
                            <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
                        </tr>
                        </tbody>
                        <?php $module_row++; ?>
                    <?php } ?>
                    <tfoot>
                    <tr>
                        <td colspan="7"></td>
                        <td class="left"><a onclick="addModule();" class="button"><span><?php echo $button_add_module; ?></span></a></td>
                    </tr>
                    </tfoot>
                </table>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
    var module_row = <?php echo $module_row; ?>;

    function addModule() {
        html  = '<tbody id="module-row' + module_row + '">';
        html += '  <tr>';
        html += '    <td class="left"><input type="text" name="moshe_flyer_module[' + module_row + '][limit]" value="5" size="1" /></td>';
        html += '    <td class="left"><select name="moshe_flyer_module[' + module_row + '][layout_id]">';
        <?php foreach ($layouts as $layout) { ?>
        html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
        <?php } ?>
        html += '    </select></td>';
        html += '    <td class="left"><select name="moshe_flyer_module[' + module_row + '][position]">';
        html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
        html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
        html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
        html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
        html += '    </select></td>';
        html += '    <td class="left"><select name="moshe_flyer_module[' + module_row + '][status]">';
        html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
        html += '      <option value="0"><?php echo $text_disabled; ?></option>';
        html += '    </select></td>';
        html += '    <td class="left"><select name="moshe_flyer_module[' + module_row + '][headline]">';
        html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
        html += '      <option value="0"><?php echo $text_disabled; ?></option>';
        html += '    </select></td>';
        html += '    <td class="left"><input type="text" name="moshe_flyer_module[' + module_row + '][numchars]" value="" size="3" /></td>';
        html += '    <td class="right"><input type="text" name="moshe_flyer_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
        html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
        html += '  </tr>';
        html += '</tbody>';

        $('#module tfoot').before(html);

        module_row++;
    }
    //--></script>
<?php echo $footer; ?>