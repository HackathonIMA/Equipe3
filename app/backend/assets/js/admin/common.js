/*
	Created By Willianson Araújo
	At 2015-05-18
*/




var common = {


	/* properties
	---------------------------------------------------------------------------------------*/
	// var


	/* initialization
	---------------------------------------------------------------------------------------*/
	init: function()
	{
		var me = this;

		// bind ativo change 
		me.bindAtivoChange();

		// bind button submit
		me.bindButtonSubmit();

		// bind cleanbox focus
		me.bindCleanboxFocus();

		// bind clean mask
		me.bindCleanMask();

		// bind color picker
		me.bindColorPicker();

		// bind grids
		me.bindGrids();

		// bind date time
		me.bindHeaderTime();

		// bind inputs date picker
		me.bindInputDatePicker();

		// bind selects
		me.bindSelects();

		// bind tabs
		// me.bindTabs();
		
		// bind export grid
		me.bindExportGrid();
	},


	/* bind ativo change
	----------------------------------------------------------------------------------------*/
	bindAtivoChange: function()
	{
		$('tbody a.checkbox').on('click', function(event)
		{
			event.preventDefault();

			// elements
			var el_checkbox = $(this);
			
			// data
			var target 			= el_checkbox.attr('href');
			var ativo_value 	= el_checkbox.data('ativo');
			var inverse_ativo 	= (ativo_value ? 0 : 1);
			var params 			= 
			{
				'ativo': ativo_value
			};
			
			// action
			$.ajax(
			{
				url 		: target,
				type 		: 'PUT',
				dataType 	: 'json',
				data 		: params,
			})
			.done(function(output)
			{
				if (output.type)
				{
					el_checkbox.toggleClass('ativo');
					el_checkbox.data('ativo', inverse_ativo);
				}
			});
			
		});
	},


	/* bind button submit
	----------------------------------------------------------------------------------------*/
	bindButtonSubmit: function()
	{
		var me = this;

		// elements
		var el_button = $('form button:last');

		// action
		el_button.on('click', function(){
			$(this).addClass('loading');
		});
	},


	/* bind cleanbox focus
	----------------------------------------------------------------------------------------*/
	bindCleanboxFocus: function()
	{
		var me = this;

		// elements
		var el_stage = $('body.cleanbox-body div.content-stage');
		
		// action
		el_stage.find('input[type!="hidden"], select, textarea').first().focus();
	},


	/* bind mask
	----------------------------------------------------------------------------------------*/
	bindCleanMask: function()
	{
		var me = this;

		// elements
		var el_inputs = $('div.input-area input[data-cleanmask]');

		// action
		el_inputs.each(function()
		{
			var temp_mask = $(this).data('cleanmask');
			var temp_this = $(this);

			switch(temp_mask)
			{
				case 'phone':
					var options =  {
						onKeyPress: function(phone)
						{
							var masks 	= ['(00) 00 000 0000', '(00) 0000 00009'];
							var mask 	= (phone.length>14) ? masks[0] : masks[1];
							temp_this.mask(mask, this);
						}
					};
					temp_this.mask('(00) 90 000 0000', options);
					break;
			}
		});
	},


	/* bind color picker
	---------------------------------------------------------------------------------------*/
	bindColorPicker: function()
	{
		var me = this;

		// elements
		var el_color_picker = $('.input-area.color-picker');
		var el_color_list 	= $('.input-area.color-picker .options-base .color-list');

		// action
		el_color_picker.each(function()
		{
			var current_color = $(this).find('input').val();
			
			$(this).find('.color-list td[bgcolor="'+current_color+'"]').addClass('selected');
		});
		
		el_color_picker.on('focus', 'input', function()
		{
			// elements
			var el_options 	= $(this).closest('.input-area').find('.options');

			// action
			el_options.fadeIn(200);

		}).on('blur', 'input', function()
		{
			// elements
			var el_options 	= $(this).closest('.input-area').find('.options');

			// action
			el_options.fadeOut(200);
		});


		el_color_list.on('mouseenter', 'td', function()
		{
			// data
			var color = $(this).css('background-color');

		}).on('click', 'td', function()
		{
			// elements
			var el_input = $(this).closest('.input-area').find('input');
			
			// data
			var color = $(this).attr('bgcolor');

			// action
			$(this).closest('table').find('.selected').removeClass('selected');
			$(this).addClass('selected');
			$(this).closest('.options').fadeOut(200);
			el_input.val(color);
		});
	},


	/* bind grids
	----------------------------------------------------------------------------------------*/
	bindGrids: function()
	{
		var me = this;

		// elements
		var el_grids 		= $('table.grid');

		// action
		el_grids.on('mouseenter', 'tbody tr', function()
		{
			$(this).addClass('hover');
		}).on('mouseleave', 'tbody tr', function()
		{
			$(this).removeClass('hover');
		});
	},


	/* bind header time
	----------------------------------------------------------------------------------------*/
	bindHeaderTime: function()
	{
		var me = this;

		// elements
		var el_time = $('#main-header .datetime .time');
		
		// validate
		if (!el_time)
			return;

		// data
        var objDate = new Date();
        var h = objDate.getHours();
        var m = objDate.getMinutes();
        var s = objDate.getSeconds();
        
        m = (m < 10 ? '0'+m : m);
        s = (s < 10 ? '0'+s : s);

        var full_time = h+':'+m+':'+s;
        
        // action
        el_time.html(full_time);
	    setTimeout('common.bindHeaderTime()', 1000);
	},


	/* bind input data picker
	----------------------------------------------------------------------------------------*/
	bindInputDatePicker: function(html_element)
	{
		var me = this;

		// elements
		var el_forms = $('form');

		// data
		var datapicker_input 	= html_element || $('div.input-area.datepicker input');

		// action
		datapicker_input.datepicker({
			showOtherMonths 	: true,
    		selectOtherMonths 	: true,
    		onSelect 			: function (){ this.focus(); }
		});
	},


	/* bind selects
	----------------------------------------------------------------------------------------*/
	bindSelects: function()
	{
		var me = this;

		// elements
		var el_selects_base = $('form');

		// action
		el_selects_base.on('change', 'div.input-area.select select', function()
		{
			// elements
			var el_input_viewer = $(this).next('input');

			// data
			var selected_text = $(this)[0].options[$(this)[0].selectedIndex].text;

			// action
			el_input_viewer.val(selected_text);
		});

		// _focus
		el_selects_base.on('focus', 'select', function()
		{
			$(this).click();
			$(this).next('input').addClass('focus');
		});

		// _blur
		el_selects_base.on('blur', 'select', function()
		{
			$(this).next('input').removeClass('focus');
		});

		// _hover
		el_selects_base.on('mouseenter', 'select', function()
		{
			$(this).next('input').addClass('hover');
		});

		// _leave
		el_selects_base.on('mouseleave', 'select', function()
		{
			$(this).next('input').removeClass('hover');
		});

		// start
		$('div.input-area.select select').trigger('change');

	},


	/* bind tabs
	----------------------------------------------------------------------------------------*/
	bindTabs: function()
	{
		var me = this;

		// elements
		var el_tabs_base 			= $('div.tabs');
		var el_tabs_items 			= el_tabs_base.find('a.tab');
		var el_tabs_item_active 	= el_tabs_base.find('a.tab.active');
		var el_content_stage 		= el_tabs_base.next('div.content-stage');
		var el_content_stage_items 	= el_content_stage.find('div[id^="stage"]');
		
		// action
		el_tabs_items.on('click', function(event)
		{
			event.preventDefault();

			// validate
			if ($(this).hasClass('disabled') || $(this).hasClass('disabled-action'))
				return false;
			
			// data
			var stage 	= $(this).data('stage');

			// reset
			el_tabs_base.find('.active').removeClass('active');
			el_content_stage_items.addClass('none');

			// action
			$(this).addClass('active');
			el_content_stage.find('#'+stage).removeClass('none');
		});

		// start
		if (el_tabs_item_active.length)
			el_tabs_item_active.trigger('click');
		else
			el_tabs_items.first().trigger('click');
	},


	/* bind export grid
	----------------------------------------------------------------------------------------*/
	bindExportGrid: function()
	{
		var me = this;
		
		$('.export-to-xls').click(function(event){
			event.preventDefault();
			
			var formName	= $(this).data('form');
			var formAction	= $(this).attr('href');
			var formElement	= $('form[name="' + formName + '"]');
			var newForm		= formElement.clone(true).attr({ 'action' : formAction, 'target' : '_blank' }); //Já copia os valores dos campos (Exceto select e textarea)

			//Copia valores dos selects
			var formElementSelects = formElement.find('select');
			newForm.find('select').each(function(index, item) {
				 $(item).val( formElementSelects.eq(index).val() );
			});
			
			newForm.off().submit();
			newForm = null; //Liberar da memória
		});
		
	},

	/* bridge
	----------------------------------------------------------------------------------------*/
	bridge: function(_url, _data, _callback)
	{
		var me = this;

		// validate
		// TODO: validate data if valid json.
		
		// prepare
		var _callback = _callback || null;

		// action
		$.ajax({
			url			: _url,
			type		: 'post',
			dataType	: 'json',
			data 		: _data,
			success		: function(output)
			{
				if (_callback)
					_callback(output);
			},
			error 		: function(output)
			{
				// // data
				// var output_temp 		= { output: 0, message: 'Ops... ocorreu um erro inesperado. Tente novamente.' };
				// var exception_content 	= JSON.stringify($(this));

				// // action
				// common.registerException(exception_content);

				// // debug
				cleanbox.hideContentLoading();
				cleanbox.showOutput({ type: false, message: 'Ops, ocorreu um erro. Atualize a tela e tente novamente.'});
				console.log(output);
				console.log("error");
			},
		});
	},


	/* data format
	----------------------------------------------------------------------------------------*/
	dateFormat: function(date_format_default)
	{
		if (!date_format_default)
			return false;
		
		var dateParts	= date_format_default.split("-");
		var date		= new Date(dateParts[0], dateParts[1] - 1, dateParts[2].substr(0,2));

		// dd/mm/yyyy
		var date_formated = ("0" + date.getDate()).slice(-2) + '/' + ("0" + (date.getMonth() + 1)).slice(-2) + '/' + date.getFullYear();

		return date_formated;
	},


	/* hora format
	----------------------------------------------------------------------------------------*/
	horaFormat: function(date_format_default)
	{
		var date = new Date(date_format_default);

		// 00:00
		var date_formated = ("0" + date.getHours()).slice(-2) + ':' + ("0" + (date.getMinutes())).slice(-2);

		return date_formated;
	},

	/* money format
	----------------------------------------------------------------------------------------*/
	moneyFormat: function( floatMoney )
	{
		floatMoney = floatMoney.replace('.', '');
		
		var stringMoney = floatMoney + '';
			stringMoney = stringMoney.replace(/([0-9]{2})$/g, ",$1"); //centenas
		
		if( stringMoney.length > 6 )
			stringMoney = stringMoney.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2"); //milhares

		if( stringMoney.length > 9)
            stringMoney = stringMoney.replace(/([0-9]{3}).([0-9]{3}),([0-9]{2}$)/g,".$1.$2,$3"); //milhões
		
		return stringMoney;
	},
 

	/* enable tabs
	----------------------------------------------------------------------------------------*/
	enableTabs: function(disabledClass)
	{
		disabledClass = disabledClass || 'disabled';
		
		// elements
		var el_tabs_base 			= $('div.tabs');
		var el_tabs_item_disabled 	= el_tabs_base.find('a.tab.' + disabledClass);
		
		// action
		el_tabs_item_disabled.removeClass(disabledClass);
	},


	/* register exception
	----------------------------------------------------------------------------------------*/
	registerException: function(content)
	{
		console.log('#info')
		console.log(ajax)
		alert('TODO: Call php that save and send email to developer.')
	},


	/* set input value
	----------------------------------------------------------------------------------------*/
	setInputValue: function(input_reference, value)
	{
		var me = this;

		// elements
		var el_input = $(input_reference);

		// validate
		if (!el_input[0])
			return;

		// data
		var el_input_type = el_input[0].nodeName;

		// action
		switch(el_input_type.toLowerCase())
		{
			case 'select':
				if (value != 0)
					el_input.val(value).trigger('change');
				break;

			case 'input':
			case 'textarea':
				el_input.val(value);
				break;

			default:
				alert('"setInputValue()" dont have a case to '+el_input_type.toLowerCase());
		}
	},


	/* sub string
	----------------------------------------------------------------------------------------*/
	subString: function(string, max_length)
	{
		var me = this;

		// data
		var string_size = string.length;

		// action
		if (string_size > max_length)
			output = string.substr(0, max_length) + '...';
		else
			output = string;

		// output
		return output;
	},


	/* time slapsed
	----------------------------------------------------------------------------------------*/
	timeSlapsed: function(timestamp) // miliseconds
	{
        var etime = new Date().getTime() - timestamp;

        if (etime < 1)
        {
            return '0 seconds';
        }

        var time_seconds = [
			{time: (12 * 30 * 24 * 60 * 60), 	str: 'ano'},
			{time: (30 * 24 * 60 * 60), 		str: 'mês'},
			{time: (24 * 60 * 60), 				str: 'dia'},
			{time: (60 * 60), 					str: 'hora'},
			{time: (60), 						str: 'minuto'},
			{time: (1), 						str: 'segundo'}
		];

		for (var i = 0; i < time_seconds.length; i++)
		{
			var rest = (etime / time_seconds[i].time) / 1000;

			if (rest >= 1)
			{
				var r = Math.round(rest);
				var output = r + ' ' + time_seconds[i].str + (r > 1 ? 's' : '') + ' atrás';

				return output.replace('mêss', 'meses');
			}
		}
    }

};


$(document).ready(function()
{
	common.init();
});