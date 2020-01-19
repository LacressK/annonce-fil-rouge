$(document).ready(function () {
	$("#rubrique").change(function () {
		$.ajax({
            type: "get",
			url: "index.php",
			data: { "Etat": "listerAnnoncesAjax", "rubrique": $("#rubrique").val() },
			dataType: 'json',	//The type of data that you're expecting back from the server
			mimeType: 'application/json',
			success: function (data) {
				//console.log(data);
				if (data == null){
					var output =`Pas d'annonce pour cette rubrique`;
				}
				else{
					var output = `<table>
						<thead>
							<tr><th>id</th><th>entête</th><th>corps</th></tr>
						</thead>
						<tbody>
					`;
					$.each(data, function (k, v) {
						output += '<tr id="' + v.id_annonce + '">';
						output += '<td>' + v.id_annonce + '</td>';
						output += '<td>' + v.entete + '</td>';
						output += '<td>' + v.corps + '</td>';
						output += '</tr>';
					});
					output += `</tbody>
						</table>
					`;
				}
				$('#retourServeur').html(output);
			},
			error: function (xhr, err) {
				alert('Ajax readyState: ' + xhr.readyState + '\nstatus: ' + xhr.status + ' ' + err);
			}
		});
	});
});