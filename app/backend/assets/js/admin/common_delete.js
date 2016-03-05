/* 
 * Create by Willianson Araujo at Ago 05 2014
 * Require: common
 * Require: cleanbox
 */



var common_delete = {


	/* properties
	---------------------------------------------------------------------------------------*/
	


	/* initialization
	---------------------------------------------------------------------------------------*/
	init: function()
	{
		var me = this;

		// bind form delete
		me.bindFormDelete();

	},


	/* bind form delete
	----------------------------------------------------------------------------------------*/
	bindFormDelete: function()
	{
		var me = this;

		// elements
		var el_form_delete = $('#form-delete');

		// validate form
		el_form_delete.on('submit', function(event)
        { 
        	event.preventDefault();

        	// data
			var form_url		= el_form_delete.attr('action');
			var form_data		= el_form_delete.serialize();
			var form_data_json	= JSON.stringify( el_form_delete.serializeArray() );

        	var form_callback 	= function(output)
        	{
				cleanbox.hideContentLoading();
				// cleanbox.showOutput(output);
				// console.log('---- output:');
				// console.log(output);
				// console.log('----');


				// cleanbox
				cleanbox.close();


				// lista
				// TODO: repassar, talvez melhorar.
				var temp_object = {};
				$.each(eval(form_data.split('&')), function(index, val)
				{
					var temp_array = val.split('=');
					if (temp_array[0] == 'callback')
						eval(temp_array[1]);
				});

				
				// cadastro
				// TODO: repassar, talvez melhorar.
				if (parent.cliente_cadastro)
				{
					parent.cliente_cadastro.getAddressList();			
					parent.cliente_cadastro.getContactList();
				}
        	}

        	// action
        	cleanbox.showContentLoading();
        	common.bridge(form_url, form_data, form_callback);
        	return false;
        });
	},
};


$(document).ready(function()
{
	common_delete.init();
});