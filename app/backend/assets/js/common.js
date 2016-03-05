/*
	Created By Willianson Araújo
*/



// $(document).ready(function()
// {
	
// 	/* Delete 
// 	-----------------------------------------------------------------------------*/
// 	$('body').on('click', '[data-action="delete"]', function(event)
// 	{
// 		event.preventDefault();

// 		// data
// 		var http_uri 	= $(this).attr('href');
// 		var http_type 	= 'DELETE';
// 		var el_self 	= $(this).closest('article'); 

// 		var onSuccess = function(){
// 			el_self.fadeOut(200).delay(300).remove();
// 		};

// 		// action
// 		bridge(http_uri, http_type, null, onSuccess);
// 	});



// 	/* Button 
// 	-----------------------------------------------------------------------------*/
// 	$('button').on('click', function(event)
// 	{
// 		event.preventDefault();

// 		// data
// 		var el_form 	= $(this).closest('form');
// 		var http_uri	= el_form.attr('action');
// 		var http_type 	= el_form.attr('method');
// 		var http_data 	= el_form.serializeArray();

// 		var onFail = function(output){
// 			el_form.html(output);
// 		};

// 		var onSuccess = function(output){
// 			if (output.type)
// 				el_form.html(output.message);
// 		};

// 		// action
// 		bridge(http_uri, http_type, http_data, onSuccess, onFail);
// 	})



// 	/* Product list
// 	-----------------------------------------------------------------------------*/	
// 	function getList()
// 	{
// 		// data
// 		var http_uri 	= '/products/all';
// 		var http_type 	= 'GET';
// 		var http_data 	= { 'order': 'product_id' };

// 		// action
// 		bridge(http_uri, http_type, http_data, fillProducts);
// 	};
// 	if ($('body').attr('id') == 'products-all')
// 		getList();


// 	function fillProducts (output_list)
// 	{
// 		// elements
// 		var el_list = $('#products-all .list');
		
// 		// data
// 		var template 	= el_list.find('.TEMPLATE').clone().removeClass('TEMPLATE')[0].outerHTML;
// 		var output 		= '';
// 		var list 		= output_list.list;

// 		// prepare
// 		for (var i = 0; i < list.length; i++)
// 		{
// 			var temp = template;
// 				temp = temp.replace(/{product_id}/g, list[i].product_id);
// 				temp = temp.replace(/{name}/g, list[i].name);
// 				temp = temp.replace(/{price}/g, list[i].price);
// 				temp = temp.replace(/{detalhes}/g, (list[i].detalhes || 'Nenhum detalhe.' ));
			
// 			output += temp;
// 		}
		
// 		// action
// 		el_list.append(output);
// 	}
	


// 	/* Bridge
// 	-----------------------------------------------------------------------------*/
// 	function bridge(http_uri, http_type, http_data, onSuccess, onFail)
// 	{
// 		// data
// 		var _uri 	= http_uri;
// 		var _type 	= http_type;
// 		var _data 	= (http_data || null);

// 		// action
// 		$.ajax({
// 			url 		: _uri,
// 			type 		: _type,
// 			data 		: _data,
// 			dataType 	: 'json',
// 			beforeSend: function() {
// 				// show load
// 			},
// 			done : function() {
// 				// hide load
// 			},
// 			fail : function()
// 			{
// 				if (onFail)
// 					onFail('Ops! Ocorreu um erro!');
// 			},
// 			success : function(output)
// 			{
// 				if (onSuccess)
// 					onSuccess(output);
// 			}
// 		});
// 	}

// });