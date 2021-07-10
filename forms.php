<?php for($i = 0; $i < $no_of_forms; $i++){ ?>
<tr>
	<td align="right">Enter Item:</td>
	<td><input class="forms" id="form<?php echo $i;?>" type="number" maxlength="3" style="width:50px;margin-right:10px;"></td>
</tr>
<?php }?>