<h2>Todo List</h2>

<form action="" method="post">
    <?php wp_nonce_field('simple-todo-nonce-action', 'simple-todo-nonce');?>
    <input type="hidden" name="id" value="<?php isset($todo->id) ? esc_html_e($todo->id) : '';?>">
    <div class="form-group">
        <label for="exampleFormControlInput1">Title</label>
        <input type="text" class="form-control" name="title" value="<?php isset($todo->title) ? esc_html_e($todo->title) : '';?>" placeholder="title">
    </div>
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Description</label>
        <textarea class="form-control" name="description" rows="3">
             <?php isset($todo->description) ? esc_html_e($todo->description) : '';?>
        </textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    <hr>
</form>


<table class="table">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Title</th>
      <th scope="col">Description</th>
	  <th scope="col">Shortcode</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
	<?php 
	   foreach($todos as $todo){
	?>
    <tr>
      <th scope="row"><?php esc_html_e($todo->id);?></th>
      <td><?php esc_html_e($todo->title);?></td>
      <td><?php esc_html_e($todo->description);?></td>
      <td>[simple_todo_id="<?php esc_attr_e($todo->id);?>"] </td>
      <td>
	  	<a href="<?php echo admin_url('admin.php?page=simple-todo&action=edit&id='. esc_html($todo->id));?>" class="btn btn-primary">Edit</a>
		<a href="<?php echo admin_url('admin.php?page=simple-todo&action=delete&id='. esc_html($todo->id));?>" onclick="return confirm('Are you sure ?')" class="btn btn-danger">Delete</a>
	  </td>
    </tr>
	<?php } ?>
  </tbody>
</table>