<?php if(!defined('BASEPATH')){ require_once("index.html");exit; } ?>

<!-- Main content -->
<section class="content">
    <?php require_once(DIR_INCLUDES.'alerts.php'); ?>
    <form action="<?php echo CURRENT_URL; ?>" class="form-validate" method="post">
        <!-- Default box -->
        <div class="box box-default box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Change Password</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Current Password <span class="text-red">*</span></label>
                            <input type="password" name="current_password" id="current_password" value="" class="form-control" required="required" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>New Password <span class="text-red">*</span></label>
                            <input type="password" name="new_password" id="new_password" value="" class="form-control" required="required" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Confirm Password <span class="text-red">*</span></label>
                            <input type="password" name="confirm_password" id="confirm_password" value="" class="form-control" required="required" />
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="overlay"><i class="fa fa-spinner fa-spin"></i></div>
            <div class="box-footer">
                <input type="hidden" name="action" id="action" value="save" />
                <button type="submit" class="btn btn-default btn-sm text-olive"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
        <!-- /.box -->
    </form>
            
</section>
<!-- /.content -->