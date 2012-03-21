
$(document).ready( function() {

	if( $('#clip-index').length ){
	var editor = CodeMirror.fromTextArea(document.getElementById("clip-index"), {
		onChange: function (){
				  var current = editor.getValue();
				  if( (current.indexOf('{') == -1) && (current.indexOf('{') == -1) ) {
					  current = '#ex234{' + current + '}';
					  }
					  $('iframe').contents().find('style').html(current);
				  }
			  }); 
	}

	if( $('#clip-users').length ){
		var editor = CodeMirror.fromTextArea(document.getElementById("clip-users"), {
			onChange: function (){ 
					  var current =  editor.getValue();
					  if( (current.indexOf('{') == -1) && (current.indexOf('{') == -1) ) { 
						  current = '#ex1{' + current + '}';
						  }
						  $('iframe').contents().find('style').html(current);
					  }
				  });

		var editor2 = CodeMirror.fromTextArea(document.getElementById("html-users"), {
			onChange: function (){ 
					  var clip = editor.getValue();
					  var html = editor2.getValue();
					  $.post('?clip/formatClip', { clip: clip, html: html }, function(data) {
						  $('iframe').contents().find('html').html(data);
					  });
				  }
		});


		var rules = {
			'author': {	required: false,maxlength: '35'	},
			'author_site': {required: false,maxlength: '80'	},
			'author_fb': {	required: false,maxlength: '80'	},
			'author_tw': {	required: false,maxlength: '80'	}
		};

		$("#submit-form").validate( {rules: rules});

		}

		if( $('#clip-example-short').length ){
			var exeditor = CodeMirror.fromTextArea(document.getElementById("clip-example-short"), {});
			var exeditor2 = CodeMirror.fromTextArea(document.getElementById("clip-example-long"), {});
		}


		if( $('#clip-show').length ){
			var editor = CodeMirror.fromTextArea(document.getElementById("clip-show"), {
				onChange: function (){ 
						  var clip = editor.getValue();
						  var html = editor2.getValue();
						  var current = clip + html; 
						  $('iframe').contents().find('style').html(clip);
						  $('iframe').contents().find('#html-clip').html(html);
					  }
			});
			var editor2 = CodeMirror.fromTextArea(document.getElementById("html-show"), {
				onChange: function (){ 
						  var clip = editor.getValue();
						  var html = editor2.getValue();
						  $('iframe').contents().find('style').html(clip);
						  $('iframe').contents().find('#html-clip').html(html);
					  }
			});


		}


		if( $('#refine').length ){
			var array = $('.author').map(function(){ return this.innerHTML}).get();
			var list = jQuery.unique(array);
			$('#author_search').attr('autocomplete','off').typeahead( { source: list } );
		}

	});
