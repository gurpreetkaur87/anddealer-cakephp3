var j = jQuery.noConflict();
j(document).ready(function(){
	var windowWidth = j(window).width();
	console.log(windowWidth);
	if( windowWidth > 1024 ){	
		j('.parent').mouseenter(function(){
			j(this).find('.child').stop().slideDown('fast');
		});
		j('.parent').mouseleave(function(){
			j(this).find('.child').stop().slideUp('fast');
		});
	}
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

 	j('.header-user').mouseenter(function(){
 		j('.header-user a').stop().slideDown('fast');
 	});
 	j('.header-user').mouseleave(function(){
 		j('.header-user a').stop().slideUp('fast');
 	});


 	var liNum = j('.ul-list li').length;
 	//console.log(liNum);
 	if(liNum < 10){
 		j('.ul-list li').removeAttr('class', 'column');		
 		j('.ul-list ul').removeAttr('class', 'columns');		
 	};
 
 	j('.dealer-table .table-cell.last a').each(function(){
 		var empty = '';
 		var test_text =  j(this).text();
 		j(this).text(empty);
 	});

 	// nav mobile

 	j('.burger_btn').click(function(){
 		j('nav#header-nav').show();
 		j('nav#header-nav').animate({right : '0'});
 	});
 	j('.close_btn').click(function(){
 		j('nav#header-nav').animate({right: '-300px'});
 		j('nav#header-nav').fadeOut();
 	})

 	if( windowWidth <= 1024 ){	
 		j('nav#header-nav ul.level0 > li.parent').click(function(){
 			j(this).toggleClass('down');
 			j(this).find('.level2.child').slideToggle();
 		})
 	}

 	
});