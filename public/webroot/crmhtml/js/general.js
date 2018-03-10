var j = jQuery.noConflict();
j(document).ready(function(){
	j('.parent').mouseenter(function(){
		j(this).find('.child').stop().slideDown('fast');
	});
	j('.parent').mouseleave(function(){
		j(this).find('.child').stop().slideUp('fast');
	});
	j('.tab-list').click(function(){
		j(this).siblings().removeClass('active');
		j(this).addClass('active');
	});

 	j('.tab-list').each(function(){
 		j(this).click(function(){
			var curTab = j(this).attr('class');
			var curNum = curTab.replace(/[^0-9]/gi, '');
			j('.tab').removeClass('active');
			j(".tab-" + curNum).addClass('active');
		});
 	});

 	j('.fake-radio-yes').click(function(){
 		jQuery(this).find('.radio').addClass('checked');
 		jQuery('.fake-radio-no').find('.radio').removeClass('checked');
 	});
 	j('.fake-radio-no').click(function(){
 		jQuery(this).find('.radio').addClass('checked');
 		jQuery('.fake-radio-yes').find('.radio').removeClass('checked');
 	});
});