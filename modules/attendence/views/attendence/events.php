  
  <?php if($lecture_exam_data_all){?>
  <table class="vm10 datatable fullwidth">
        <tr>
            <th>
                Event
            </th>
            <th>
                Start Time
            </th>
            <th>
                End Time
            </th>
            <th>
                Action
            </th>
        </tr>
        
            <?php foreach($lecture_exam_data_all as $lecture_exam_data){ ?>
                <tr>
                    <td><?php echo $lecture_exam_data['name']; ?> (<?php echo $lecture_exam_data['eventtype']; ?>)</td>
                    <td><?php echo date('h:i A', $lecture_exam_data['eventstart']) ?></td>
                    <td><?php echo date('h:i A', $lecture_exam_data['eventend']) ?></td>
                    <td><a href="<?php echo 'attendence/add/id/'.$lecture_exam_data['id'].'/type/'.$lecture_exam_data['eventtype'].'/event_id/'.$lecture_exam_data['event_id']; ?>">Add</a></td>
                </tr>
                
            <?php }?>
         </table>   
         <?php
         } else {
                echo "No records found";
         } ?>
