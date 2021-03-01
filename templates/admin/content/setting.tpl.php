<?php if(!defined('BASEPATH')){ require_once("index.html");exit; } ?>

<!-- Main content -->
<section class="content">
    <?php require_once(DIR_INCLUDES.'alerts.php'); ?>
    
    <form action="<?php echo CURRENT_URL; ?>" method="post" enctype="multipart/form-data">
        <!-- Default box -->
        <div class="box box-default box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Setting</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <?php
                        foreach($records as $key=>$val){
                            ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $val['comment']; ?> <span class="text-red star">*</span></label>
                                    <input type="text" name="<?php echo $val['option']; ?>" id="<?php echo $val['option']; ?>" class="form-control" value="<?php echo $instance->db_output($val['value']); ?>" />
                                </div>
                            </div>
                            <?php
                        }
                    ?>
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