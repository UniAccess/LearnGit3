// JavaScript Document

$(document).ready(function(){
    
    if($("#title").val() =="")$("#title").val("Enter title here");
	$("#title").mouseover(function(){
		   if($("#title").val() =="")$("#title").val("Enter title here");
		});
	$("#title").click(function(){
	     if($("#title").val() =="Enter title here")$("#title").val("");
	});
	
	if($("#post").val() =="")$("#post").val("Enter question here");
	$("#post").mouseover(function(){
		   if($("#post").val() =="")$("#post").val("Enter question here");
		});
	$("#post").click(function(){
	     if($("#post").val() =="Enter question here")$("#post").val("");
	});
});