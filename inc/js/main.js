$=jQuery;

$(document).ready(function(){
	"use strict";
	
	/*---Enable plugin DataTable / Add filter, search, paginable and other-----*/
    $('#tasks_table').DataTable( {		
        "order": [[ 0, "desc" ]]
    } );
	/*---end---*/
	
	/*----Move table columns Date <> Freelancer ------*/
	$.moveColumn = function (table, from, to) {
		var rows = $('tr', table);
		var cols;
		rows.each(function() {
			cols = $(this).children('th, td');
			cols.eq(from).detach().insertBefore(cols.eq(to));
		});
	}
	var table = $('#tasks_table');
	$.moveColumn(table, 3, 2);
	/*----end----*/
	
	/*----Add link attributes for modal window-----*/
	$('a[href="javascript:;"]').attr('data-toggle', 'modal');
	$('a[href="javascript:;"]').attr('data-target', '#AddNewTask');
	/*----end----*/
	
	/*-------Add new task---------*/
	$("#addTask").click(function (e) {
		e.preventDefault();
		$.post('/wp-admin/admin-ajax.php?action=add_task_from_form', $('#AddNewTask form').serialize(), function(data) {
			if (data.success === true) {
				alert('Success!');
				location.reload();
			}
		}, 'json');
	});
	/*----end----*/
	
});
