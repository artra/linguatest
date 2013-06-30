$(document).ready(function() {
	var UTILS = {},
		MYLINK = {},
		SHAREDLINK = {},
		POPULARLINK = {},
		editLinkForm = $('#editLinkForm'),
		newLinkForm = $('#newLinkForm'),
		shareLinkForm = $('#shareLinkForm');
	UTILS = (function(){
		var UTILS={},
			preloader='<div class="u-preloader"></div>',
			messageBlock=$('.b-warning');
		function showMessage (message){
			messageBlock.text(message);
			messageBlock.show("slow");
		}
		function drawPreloader (el){
			$(el).prepend(preloader);
		}
		function removePreloader (el){
			$(el).find('.u-preloader').remove();
		}
		
		UTILS.showMessage=showMessage;
		UTILS.drawPreloader=drawPreloader;
		UTILS.removePreloader=removePreloader;
		return UTILS;
	})();
	
	MYLINK=(function(){
		var MYLINK={},
			newLinkForm = $('#newLinkForm'),
			myLinks=$(".b-my .b-container__content");
			
		function compileTemplate(data) {
			var template=''+
				'<div class="b-container__item" data-id='+data.id+' data-name='+data.name+' data-href='+data.href+'>' +
					'<div class="b-container__options">'+
						'<div class="u-share-icon"></div>'+
						'<div class="u-edit-icon"></div>'+
						'<div class="u-remove-icon"></div>'+
					'</div>'+
					'<a class="b-container__link" href="'+data.href+'">'+data.name+'</a>'+
				'</div>';
			return template;
		}
		function refreshLinks(){
			UTILS.drawPreloader(myLinks);
			$.post('links/show_links.php', null, null, 'json')
			.always(function(data){
				UTILS.removePreloader(myLinks);
			})
			.done(function(data) {
				var content='';
				$.each(data,function(index, value){
					content+=compileTemplate(value);
				});
				myLinks.html(content);
			});
		}
		
		function newLink(fdata, submit){
			var submit = submit || $('');
			submit.attr('disabled', 'disabled');
			$.post('links/new_link.php', fdata, null, 'json')
			.always(function(){
				submit.removeAttr('disabled');
			})
			.done(function(data){
				$("input[type=text]").val("");
				$(".b-my .b-container__content").prepend(compileTemplate(data[0]));
				UTILS.showMessage('Ссылка добавлена');
			});
		}
		function removeLink(item){
			var link_id=item.data("id");
			item.remove();
			$.post('links/delete_link.php', {'link_id':link_id})
			.done(function(data){
				UTILS.showMessage('Ссылка удалена');
			});
		

		}
		function showShareDialog (item){
			var link_id=item.data("id"),
				name=item.data("name"),
				href=item.data("href");
			$('#shareLinkId').val(link_id);
			$('#shareLinkName').val(name);
			$('#shareLinkHref').val(href);
			$("#shareDialog").dialog({ show: "slow", modal: true });
		}
		function shareLink (fdata, submit, errorMessage) {
			submit.attr('disabled', 'disabled');
			$.post('links/share_link.php', fdata)
			.always(function(){
				submit.removeAttr('disabled');
			})
			.fail (function(){
				errorMessage.show();
			})
			.done(function(data){
				errorMessage.hide();
				UTILS.showMessage('Вы поделились ссылкой');
				$( "#shareDialog" ).dialog( "close" );
			});
		}
		
		function showEditDialog (item){
			var link_id=item.data("id"),
				name=item.data("name"),
				href=item.data("href");
			$('#editLinkId').val(link_id);
			$('#editLinkName').val(name);
			$('#editLinkHref').val(href);
			$("#editDialog").dialog({ show: "slow", modal: true });
		}
		function editLink (fdata, submit) {
			submit.attr('disabled', 'disabled');
			$.post('links/edit_link.php', fdata, null, 'json')
			.always(function(){
				submit.removeAttr('disabled');
			})
			.done(function(data){
				myLinks.find('[data-id='+data[0]['id']+'] .b-container__link ').text(data[0]['name']).attr('href',data[0]['href']);
				UTILS.showMessage('Ссылка изменена');
				$( "#editDialog" ).dialog( "close" );
			});
		}
		
		myLinks.on("click", ".u-remove-icon", function(event){
			var item=$(this).closest('.b-container__item');
			MYLINK.removeLink(item);
		});
		myLinks.on("click", ".u-edit-icon", function(event){
			var item=$(this).closest('.b-container__item');
			MYLINK.showEditDialog(item);
		});	
		myLinks.on("click", ".u-share-icon", function(event){
			var item=$(this).closest('.b-container__item');
			MYLINK.showShareDialog(item);
		});	
		
		MYLINK.refreshLinks=refreshLinks;
		MYLINK.newLink=newLink;
		MYLINK.removeLink=removeLink;
		MYLINK.showShareDialog=showShareDialog;
		MYLINK.shareLink=shareLink;
		MYLINK.showEditDialog=showEditDialog;
		MYLINK.editLink=editLink;
		return MYLINK;
	})();
	
	SHAREDLINK=(function(){
		var SHAREDLINK={},
			sharedLinks=$(".b-shared .b-container__content");
			
		function compileTemplate(data) {
			var template=''+
				'<div class="b-container__item" data-id='+data.id+' data-name='+data.name+' data-href='+data.href+'>' +
					'<div class="b-container__options">'+
						'<div class="u-add-icon"></div>'+
						'<div class="u-remove-icon"></div>'+
					'</div>'+
					'<a class="b-container__link" href="'+data.href+'">'+data.name+'</a>'+
				'</div>';
			return template;
		}
		function refreshLinks(){
			UTILS.drawPreloader(sharedLinks);
			$.post('links/show_shared_links.php', null, null, 'json')
			.always(function(data){
				UTILS.removePreloader(sharedLinks);
			})
			.done(function(data) {
				var content='';
				$.each(data,function(index, value){
					content+=compileTemplate(value);
				});
				sharedLinks.html(content);
			});
		}
		
		function removeLink(item){
			var link_id=item.data("id");
			item.remove();
			$(this).closest('.b-container__item').remove();
			$.post('links/delete_shared_link.php', {'link_id':link_id})
			.done(function(data){
				UTILS.showMessage('Ссылка удалена');
			});
		}
		function addLink(item){
			var name=item.data("name"),
				href=item.data("href");
			MYLINK.newLink({'name':name,'href':href});
		}
		
		sharedLinks.on("click", ".u-remove-icon", function(event){
			var item=$(this).closest('.b-container__item');
			SHAREDLINK.removeLink(item);
		});
		sharedLinks.on("click", ".u-add-icon", function(event){
			var item=$(this).closest('.b-container__item');
			SHAREDLINK.addLink(item);
		});	
		
		SHAREDLINK.refreshLinks=refreshLinks;
		SHAREDLINK.removeLink=removeLink;
		SHAREDLINK.addLink=addLink;
		return SHAREDLINK;
	})();
	
	POPULARLINK = (function(){
		var POPULARLINK={},
			popularLinks=$(".b-popular .b-container__content");
		
		function compileTemplate(data) {
			var template=''+
				'<div class="b-container__item" data-id='+data.id+' data-name='+data.name+' data-href='+data.href+'>' +
					'<div class="b-container__options">'+
						'<div class="u-add-icon"></div>'+
					'</div>'+
					'<a class="b-container__link" href="'+data.href+'">'+data.name+'</a>'+
				'</div>';
			return template;
		}
		function refreshLinks(){
			UTILS.drawPreloader(popularLinks);
			$.post('links/show_popular_links.php', null, null, 'json')
			.always(function(data){
				UTILS.removePreloader(popularLinks);
			})
			.done(function(data) {
				var content='';
				$.each(data,function(index, value){
					content+=compileTemplate(value);
				});
				popularLinks.html(content);
			});
		}
		
		function addLink(item){
			var name=item.data("name"),
				href=item.data("href");
			MYLINK.newLink({'name':name,'href':href});
		}
		
		popularLinks.on("click", ".u-add-icon", function(event){
			var item=$(this).closest('.b-container__item');
			POPULARLINK.addLink(item);
		});	 
		
		POPULARLINK.refreshLinks=refreshLinks;
		POPULARLINK.addLink=addLink;
		return POPULARLINK;
	})();	
	
	MYLINK.refreshLinks();
	SHAREDLINK.refreshLinks();
	POPULARLINK.refreshLinks();
	
	newLinkForm.submit(function() {
		var fdata = newLinkForm.serialize(),
			submit=newLinkForm.find('input[type="submit"]'); 
		MYLINK.newLink(fdata,submit);
		return false;
	});
	
	editLinkForm.submit(function() {
		var fdata = editLinkForm.serialize(),
			submit= editLinkForm.find('input[type="submit"]'); 
		MYLINK.editLink(fdata,submit);
		return false;
	});	
	
	shareLinkForm.submit(function() {
		var fdata = shareLinkForm.serialize(),
			submit= shareLinkForm.find('input[type="submit"]')
			errorMessage=$('.b-error'); 
		MYLINK.shareLink(fdata,submit,errorMessage);
		return false;
	});		

});
