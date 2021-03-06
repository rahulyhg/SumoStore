<?php echo $header; ?>

<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?>
    <a href="<?php echo $breadcrumb['href']; ?>">
        <?php echo $breadcrumb['text']; ?>
    </a>
    <?php } ?>
</div>

<div id="pad-wrapper">

    <?php if ($error_warning) { ?>
        <div class="alert alert-warning">
            <i class="icon-warning-sign"></i>
            <?php echo $error_warning; ?>
        </div>
    <?php } ?>
    <?php if ($success) { ?>
        <div class="alert alert-success">
            <i class="icon-ok-sign"></i>
            <?php echo $success; ?>
        </div>
    <?php } ?>
  
    <div class="row">
        <div class="col-md-4">
            <h4><img src="img/icons/cats-24.png" alt="" /> <?php echo $heading_title; ?></h4>
        </div>
        <div class="col-md-8">
            <div class="buttons pull-right">
                <a href="<?php echo $insert; ?>" class="btn btn-sm btn-primary"><?php echo $button_insert; ?></a>
                <a onclick="$('#form').submit();" class="btn btn-sm btn-primary"><?php echo $button_delete; ?></a>
            </div>
        </div>
    </div>
    <div class="clearfix"><br /></div>
    
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs">
                <li>
                    <a href="<?php echo $url_users?>">
                        <?php echo $text_users?>
                    </a>
                </li>
                <li class="active">
                    <a href="<?php echo $url_permissions?>">
                        <?php echo $text_permissions?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="clearfix"><br /></div>
    
    <div class="row">
        <div class="col-md-7">
            <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="table table-striped dataTable">
                    <thead>
                        <tr>
                            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
                            <td><?php echo $column_name; ?></td>
                            <td><?php echo $column_action; ?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($user_groups) { ?>
                    <?php foreach ($user_groups as $user_group) { ?>
                    <tr>
                        <td style="text-align: center;"><?php if ($user_group['selected']) { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $user_group['user_group_id']; ?>" checked="checked" />
                        <?php } else { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $user_group['user_group_id']; ?>" />
                        <?php } ?></td>
                        <td><?php echo $user_group['name']; ?></td>
                        <td><?php foreach ($user_group['action'] as $action) { ?>
                        <a href="<?php echo $action['href']; ?>"><i class="table-edit"></i></a>
                        <?php } ?></td>
                    </tr>
                    <?php } ?>
                    <?php } else { ?>
                    <tr>
                        <td class="center" colspan="3"><?php echo $text_no_results; ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){
    $('.dataTable').dataTable({
        "sPaginationType": "full_numbers",
        "oLanguage": DATATABLES_LANG,
        "bSort": false,
        "iDisplayLength": 10,
        "bAutoWidth": false
    });
})
</script>
<?php echo $footer; ?> 