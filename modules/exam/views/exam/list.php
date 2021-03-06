    <div class="r pagecontent">
        <div class="pageTop">
            <div class="pageTitle l">replace_here_page_title</div>
            <div class="pageDesc r">replace_here_page_description</div>
            <div class="clear"></div>
        </div><!-- pageTop -->
        <div class="topbar">
            <?php if( Acl::instance()->is_allowed('exam_delete')){?>
                <a onclick="$('#exam').submit();" class="pageAction r alert">Delete selected...</a>
            <?php }?> 
            <?php if( Acl::instance()->is_allowed('exam_create')){?>
                <?php echo $links['add']?>
            <?php }?>    
            <?php if(Acl::instance()->has_access('examgroup')){?>
            <?php echo $links['examgroup']?>
            <?php } ?>
            <?php if(Acl::instance()->has_access('examresult')){?>
            <?php echo $links['examresult']?>
            <?php } ?>
            <span class="clear">&nbsp;</span>
        </div><!-- topbar -->
        <?php if ($success) {  ?>
            <div class="formMessages w90">     
            <span class="fmIcon good"></span> <span class="fmText" ><?php echo $success ?></span>
            <span class="clear">&nbsp;</span>
            </div>
        <?php } ?>
        <form name="exam" id="exam" method="POST" action="<?php echo $links['delete'] ?>">
        <table class="vm10 datatable fullwidth">
            <?php echo $table['heading']?>
            <tr class="filter" >
                 <td><input style="width: 120px;" type="hidden" id="filter_url" value="<?php echo $filter_url ?>" /></td>
                 <td><input type="text" name="filter_name" value="<?php echo $filter_name ?>" /></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td><input style="width: 25px;" type="text" name="filter_total_marks" value="<?php echo $filter_total_marks ?>" /></td>
                 <td><input style="width: 25px;" type="text" name="filter_passing_marks" value="<?php echo $filter_passing_marks ?>" /></td>
                 <td></td>
                 <td valign="middle"><a class="button" id="trigger_filter" href="#">Filter</a></td>
            </tr>
            <?php foreach($table['data'] as $exam){ ?>
            <tr>
                <td><input type="checkbox" class="selected" name="selected[]" value="<?php echo $exam->id ?>" /></td>
                <td><?php echo $exam->name ?></td>
                <td><?php echo $exam->examgroup->name ?></td>
                <td><?php echo date('d M Y h:i A', $exam->event->eventstart) ?></td>
                <td><?php echo $exam->course->name ?></td>
                <td><?php echo $exam->total_marks ?></td>
                <td><?php echo $exam->passing_marks ?></td>
                <td><?php echo ($exam->reminder)?'Yes':'No'; ?></td>
                <td>
                    <p><?php if( Acl::instance()->is_allowed('exam_edit')){?>
                        <?php echo Html::anchor('/exam/edit/id/'.$exam->id, 'View/Edit')?>
                    <?php }?></p>
                </td>
            </tr>
            <?php }?>
            <?php if($count > 0){ ?>
                <tr class="pagination">
                    <td class="tar pagination" colspan="9">
                        <?php echo $pagination ?>
                    </td>
                </tr>
                <?php } else { ?>
                <tr>
                    <td colspan="9" align="center">
                        No Records Found
                    </td>
                </tr>
                <?php } ?>
        </table>
        </form>
    </div>
    <div class="clear"></div>
    
