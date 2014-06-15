// JavaScript Document

$(function(){
	//ページトップへ
    $("#pageTop a").click(function(){
		$('html,body').animate({ scrollTop: $($(this).attr("href")).offset().top }, 'slow','swing');
    	return false;
    })
	//トップページアイコン
	$('#fcIntro a:first').addClass('fchild');
	$('#fcIntro a:last').addClass('lchild');

	//#cont ul
	$('#cont .col2Area  ul li:last-child').addClass('last');

	//2カラムラスト
	$('#cont .col2Area:last').addClass('last');

	//ドロップダウン
    $(".faqDl dd:not(:first)").css("display","none")
    $(".faqDl dt:first").addClass("selected");
    $("dl.faqDl dt").click(function(){
        if($("+dd",this).css("display")=="none"){
            $("dd").slideUp("fast");
            $("+dd",this).slideDown("fast");
            $("dt").removeClass("selected");
            $(this).addClass("selected");
        }
    }).mouseover(function(){
        $(this).addClass("open");
    }).mouseout(function(){
        $(this).removeClass("open");
    })

});

