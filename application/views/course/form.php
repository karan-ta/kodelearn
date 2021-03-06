	
	<div class="r pagecontent">
		<div class="pageTop withBorder">
			<div class="pageTitle l">replace_here_page_title</div>
			<div class="pageDesc r">replace_here_page_description</div>
			<div class="clear"></div>
		</div><!-- pageTop -->
		
		<?php echo $form->startform(); ?>

		<div class="vm10">
			<?php echo $form->save->element(); ?>
			<span class="clear h2">&nbsp;</span>
		</div> <!-- vm10 -->

		<br/>
		
		<div id="tabs">
		  <ul>
		      <li><a href="#form-details"> Course Details</a></li>
		      <li><a href="#assign-students"> Students</a></li>
		      <li><a href="#assign-teachers"> Teachers</a></li>
		  </ul>
		
		<div id="form-details">
		<table class="formcontainer vm40">
			<tr>
				<td><?php echo $form->name->label(); ?></td>
				<td><?php echo $form->name->element(); ?>
				<span class="form-error"><?php echo $form->name->error(); ?></span></td>
			</tr>
			<tr>
				<td><?php echo $form->description->label(); ?></td>
				<td><?php echo $form->description->element(); ?>
				<span class="form-error"><?php echo $form->description->error(); ?></span></td>
			</tr>
			<tr>
				<td><?php echo $form->access_code->label(); ?></td>
				<td><?php echo $form->access_code->element(); ?>
				<span class="form-error"><?php echo $form->access_code->error(); ?></span></td>
			</tr>
            <tr>
                <td><?php echo $form->start_date->label(); ?></td>
                <td><?php echo $form->start_date->element(); ?>
                <span class="form-error"><?php echo $form->start_date->error(); ?></span></td>
            </tr>
            <tr>
                <td><?php echo $form->end_date->label(); ?></td>
                <td><?php echo $form->end_date->element(); ?>
                <span class="form-error"><?php echo $form->end_date->error(); ?></span></td>
            </tr>
			<tr>
				<td></td>
				<td></td>
			</tr>
		</table>
		</div>
		
		<div id="assign-students">
			<p class="bm40">
				Add users from Batch: 
				<select id="batch_id">
				  <option value="0">Select Batch</option>
				  <?php foreach($batches as $batch){ ?>
					  <option value="<?php echo $batch->id ?>"><?php echo $batch->name ?></option>
				  <?php }?>
				</select>
				<a class="button" href="#" id="add_users"> Add</a>
				<p class="tip">Please select the checkbox to assign users to course</p>
			</p>
			<p class="tip" style="display:none;" id="loading">Please wait...Loading Users</p>
    		<div id="assign-students-ajax">	
                <?php echo $users ?>		  
    		</div>
		</div>
		
		<div id="assign-teachers">
			<p class="bm40">Please select Teachers who will teach this course:</p>
    		<div id="assign-teachers-table">	
                <?php echo $teachers_table; ?>		  
    		</div>
		</div>
		<?php echo $form->endForm(); ?>
	</div>	
	</div><!-- pagecontent -->
	
	<div class="clear"></div>
	
<script type="text/javascript"><!--
KODELEARN.modules.add('assign_users' , (function () {    
    
    return {
        init: function () { 
    	   $( "#tabs" ).tabs();
    	   $('#add_users').click(function(){
	    	   $('#loading').fadeIn();
        	   var batch_id = $('#batch_id').val();
        	   var course_id = '<?php echo $course_id ?>';
        	   if(batch_id){
                   $.post(KODELEARN.config.base_url + "course/get_users", { "batch_id": batch_id, "course_id": course_id },
                   function(data){
        	       	  $('#assign-students-ajax').html(data.response);
        	       	  $('#loading').fadeOut();     	       	  
                   }, "json");
        	   }
    	   });

        }
    }; 
})());
//--></script>
	
