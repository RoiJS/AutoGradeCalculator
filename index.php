<html>
	<body>
		<form id="frm-upload" >
			<table>
				<tr>
					<td>No of activities:</td>
					<td><input type="number" maxlength="2" id="no_of_forms" style="width:50px;margin-right:10px;"><input id="btn-add-activity" type="button" value="Go"></td>
				</tr>
				<tbody id="display-no-of-activites">
					
				</tbody>
				<tr>
					<td align="right">Select file:</td>
					<td> <input type="file" name="file" id="file"></td>
				</tr>
				<tr>
					<td align="right"></td>
					<td> <input type="submit" id="btn-submit"></td>
				</tr>
			</table>
		</form>
		<form id="frm-attendance" >
			<table>
				<tr>
					<td>Cell no.</td>
					<td><input type="text" id="cell_no" style="width:50px;margin-right:10px;"></td>
				</tr>
				<tr>
					<td>No. of students</td>
					<td><input type="text" id="highest_row" style="width:50px;margin-right:10px;"></td>
				</tr>
				<tbody id="display-no-of-activites">
					
				</tbody>
				<tr>
					<td align="right">Select file:</td>
					<td> <input type="file" name="file" id="file"></td>
				</tr>
				<tr>
					<td align="right"></td>
					<td> <input type="submit" id="btn-submit"></td>
				</tr>
			</table>
		</form>
		
		
		<script src="jquery/jquery-1.8.2.min.js"></script>
		<script src="processFunctions.js"></script>
		<script>
			$(document).ready(function(){
				
				$("body").delegate("#frm-upload","submit",function(e){
					e.preventDefault();
					
					var activity_items = [];
					var file = $("#file").val();
					var formdata = new FormData($(this)[0]);
					
					$(".forms").each(function(i){
						activity_items.push($("#form" + i).val() != "" ? parseInt($("#form" + i).val()) : 0); 
					});
				
					formdata.append("action", "compute_grade");
					formdata.append("activity_items", activity_items);
					
					if(confirm("Are you sure to compute students grade?")){
						var compute = ajax(formdata, true,"","","post");
						location.reload();
					}
					
				});
				
				
				$("body").delegate("#frm-attendance","submit",function(e){
					e.preventDefault();
					
					var cell_no = $("#cell_no").val();
					var highest_row = $("#highest_row").val();
					var formdata = new FormData($(this)[0]);
					
					formdata.append("action", "monitor_attendance");
					formdata.append("cell_no", cell_no);
					formdata.append("highest_row", highest_row);
					
					if(confirm("Are you sure to monitor attendance?")){
						var compute = ajax(formdata, true,"","","post");
						console.log(compute);
						//location.reload();
					}
					
				});
				
				$("body").delegate("#btn-add-activity","click",function(){
					var no_of_forms = $("#no_of_forms").val();
					
					var forms = ajax({action : "add_forms", no_of_forms : no_of_forms},true);
					$("#display-no-of-activites").html(forms);
				});
			});
		</script>
	</body>
</html>