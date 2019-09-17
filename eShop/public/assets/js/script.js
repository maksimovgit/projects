$(document).ready(function(){ 
	// Галлерея
	if($("#gallery").length){ 
		var totalImages = $("#gallery > li").length; 
		var imageWidth = $("#gallery > li:first").outerWidth(true); 
		var totalWidth = imageWidth * totalImages;
		var visibleImages = Math.round($("#slider-wrap").width() / imageWidth); 
		var visibleWidth = visibleImages * imageWidth; 
		var stopPosition = (visibleWidth - totalWidth); 

		$("#gallery").width(totalWidth); 
		$("#control-prev").click(function(){
			if($("#gallery").position().left < 0 && !$("#gallery").is(":animated")) {
				$("#gallery").animate({left : '+=' + imageWidth}); 
			}
			return false;
		});

		$('#control-next').click(function(){
			if($("#gallery").position().left > stopPosition && !$("#gallery").is(":animated")) {
				$('#gallery').animate({left : '-=' + imageWidth});
			}
		});
		
	}

	var element = $('.cabinet__links').find('li');
	var len_elem = element.length;
	for(var i=0; i< len_elem; i++) {
		var li = element[i];
		if($(li).hasClass('select')) {
			var a = $(element[i]).children()[0];
			$(a).css({'color': '#fff'});
		}
	}
	
	$(document).on('click','.do-action', function(e){
		e.preventDefault();
		var click = this;
		var href = $(this).attr('href');
		console.log(href);
		var params = href.split('&');
		console.log(params);
		var result = [];
		for(var i=1; i<params.length; i++) {
			var item = params[i].split('=');
			result[item[0]] = item[1];
		}
		console.log(result);
		var id = result['id'];
		var action = result['action'];
		var cost = result['cost'];
		var weight = result['weight'];
		console.log('надо сделать экшн: ' + action + ' с товаром с id = ' + id);

		//сделать аякс запрос
		$.post(params[0],
			{message: 'запрос через аякс', action: action, id: id, cost: cost, weight: weight}, function(data){
			console.log('получили ответ');
			console.log(data);
			data = JSON.parse(data);
			console.log('data после JSON.parse(data)');
			console.log(data);
			$('#TotalCount').text(data['totalCount']);
			$('#TotalCost').text(data['totalCost']);
			console.log('click - this');
			console.log($(click));
			console.log('получить родителя родителя');
			console.log($(click).parent().parent());
			if($(click).parent().parent().hasClass('cart__item-cost_count')) {
				var idSummCost = '#SummCost'+id;
				var idSummCount = '#SummCount'+id;
				$(idSummCost).html('<b>Стомость: </b><br>'+ data['count_in_cart']*cost);
				$(idSummCount).html('<b>Кол-во: </b>' + data['count_in_cart']);
				$('#TotalCost2').text('Общая стоимость: ' + data['totalCost'] + ' руб.');
				$('#TotalWeight').text('Общая вес: ' + (data['totalWeight']/1000) + ' кг.');
				var idInStok = '#InStok'+id;
				if(parseInt(data['count_in_cart']) > parseInt(data['goodsCountInBase'])) {
					$(idInStok).css({'color': 'red'});
				} else {
					$(idInStok).css({'color': 'black'});
				}
			}
			if(action === 'del_element') { 
				var idCartItem = '#CartItem'+id;
				$(idCartItem).css({'display': 'none'});
				$('#TotalCost2').text('Общая стоимость: ' + data['totalCost'] + ' руб.');
				$('#TotalWeight').text('Общая вес: ' + (data['totalWeight']/1000) + ' кг.');
			}
			
		});
	});

});

//ф-ция получает имя загружаемого файла и выводит в нужное место
function getFileName () {
var file_name = $('#Uploaded').val();
//console.log('file_name : '+ file_name);
file_name = file_name.split('\\').pop(); 
//console.log('file_name после сплита : '); console.log(file_name);
$('#FileName').html('Имя файла: ' + file_name);
}