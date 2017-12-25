var Demo = (function() {

	function output(node) {
		var existing = $('#result .croppie-result');
		if (existing.length > 0) {
			existing[0].parentNode.replaceChild(node, existing[0]);
		}
		else {
			$('#result')[0].appendChild(node);
		}
	}

	function popupResult(result) {
		var html;
		if (result.html) {
			html = result.html;
		}
		if (result.src) {
			html = '<img src="' + result.src + '" />';
		}
		swal({
			title: '',
			html: true,
			text: html,
			allowOutsideClick: true
		});
		setTimeout(function(){
			$('.sweet-alert').css('margin', function() {
				var top = -1 * ($(this).height() / 2),
					left = -1 * ($(this).width() / 2);

				return top + 'px 0 0 ' + left + 'px';
			});
		}, 1);
	}

	function demoMain () {
		var mc = $('#cropper-1');
		mc.croppie({
			viewport: {
				width: 150,
				height: 150,
				type: 'square'
			},
			boundary: {
				width: 300,
				height: 300
			},
			// url: 'demo/demo-1.jpg',
			enforceBoundary: false
			// mouseWheelZoom: false
		});
		mc.on('update', function (ev, data) {
			// console.log('jquery update', ev, data);
		});
		$('.js-main-image').on('click', function (ev) {
            mc.croppie('result', {
            	type: 'rawcanvas',
            	// size: { width: 300, height: 300 },
            	format: 'png'
            }).then(function (canvas) {
				popupResult({
					src: canvas.toDataURL()
				});
			});
		});
	}

	function demoBasic() {
		var $w = $('.basic-width'),
			$h = $('.basic-height'),
			basic = $('#demo-basic').croppie({
			viewport: {
				width: 150,
				height: 200
			},
			boundary: {
				width: 300,
				height: 300
			}
		});
		basic.croppie('bind', {
			url: 'demo/cat.jpg',
			points: [77,469,280,739]
		});

		$('.basic-result').on('click', function() {
			var w = parseInt($w.val(), 10),
				h = parseInt($h.val(), 10),s
				size = 'viewport';
			if (w || h) {
				size = { width: w, height: h };
			}
			basic.croppie('result', {
				type: 'canvas',
				size: size
			}).then(function (resp) {
				popupResult({
					src: resp
				});
			});
		});
	}

	function demoVanilla() {
		var vEl = document.getElementById('vanilla-demo'),
			vanilla = new Croppie(vEl, {
			viewport: { width: 100, height: 100 },
			boundary: { width: 300, height: 300 },
			showZoomer: false,
            enableOrientation: true
		});
		vanilla.bind({
            url: 'demo/demo-2.jpg',
            orientation: 4,
            zoom: 0
        });
        vEl.addEventListener('update', function (ev) {
        	console.log('vanilla update', ev);
        });
		document.querySelector('.vanilla-result').addEventListener('click', function (ev) {
			vanilla.result({
				type: 'blob'
			}).then(function (blob) {
				popupResult({
					src: window.URL.createObjectURL(blob)
				});
			});
		});

		$('.vanilla-rotate').on('click', function(ev) {
			vanilla.rotate(parseInt($(this).data('deg')));
		});
	}

    function demoResizer() {
		var vEl = document.getElementById('resizer-demo'),
			resize = new Croppie(vEl, {
			viewport: { width: 100, height: 100 },
			boundary: { width: 300, height: 300 },
			showZoomer: false,
            enableResize: true,
            enableOrientation: true
		});
		resize.bind({
            url: 'demo/demo-2.jpg',
            zoom: 0
        });
        vEl.addEventListener('update', function (ev) {
        	console.log('resize update', ev);
        });
		document.querySelector('.resizer-result').addEventListener('click', function (ev) {
			resize.result({
				type: 'blob'
			}).then(function (blob) {
				popupResult({
					src: window.URL.createObjectURL(blob)
				});
			});
		});
	}

	function callAjax(image) {
		//console.log(message);
		url = 'uploadFile.php';
		//$(form).submit(function(e) {
     // var formObj = image;
      var firstname = $('#firstname').attr('value');
      var message = $('#message').val();
      console.log(message);
      // var formURL = formObj.attr("action");
      var formURL = url;
      //if(window.FormData !== undefined)  // for HTML5 browsers
      //{
   //formData = {"wouter": "koppers"};
          //var formData = message;
          $.ajax({
              url: formURL,
              type: 'POST',
              data: {
   				     '<insert-shit>': "<no plx>",
   				     'image': image,
   				     //'lastname': lastname,
   				     'message': message
				    },
              //data:  formData,
              mimeType:"multipart/form-data",
              //contentType: false,
              //cache: false,
              //processData:false,
              success: function(data, textStatus, jqXHR)
              {
              	console.log("succesfully saved");
                  window.location.href = 'bbp14.php?speed=2000&dialogic=false'; 		
              },
              error: function(jqXHR, textStatus, errorThrown) 
              {
                    // Oops! Some Error
                    console.log("ERROR!!! NOT succesfully saved!!!");
              }           
         });
	}

	//only function i use:
	function demoUpload() {
		var $uploadCrop;

		function readFile(input) {
 			if (input.files && input.files[0]) {
	            var reader = new FileReader();
	            
	            reader.onload = function (e) {
					$('.upload-demo').addClass('ready');
	            	$uploadCrop.croppie('bind', {
	            		url: e.target.result
	            	}).then(function(){
	            		console.log('jQuery bind complete');
	            	});
	            	
	            }
	            
	            reader.readAsDataURL(input.files[0]);
	        }
	        else {
		        swal("Sorry - you're browser doesn't support the FileReader API");
		    }
		}

		$uploadCrop = $('#upload-demo').croppie({
			viewport: {
				width: 200,
				height: 200,
				type: 'circle'
			},
			enableExif: true
		});

		//hide elements
		$('#rotatebutton').hide();
		$('#shortmessageform').hide();
		$('#upload-to-server').hide();
		$('#useimagebutton').hide();
		$('#upload-really').hide();
		$('#uploadwrap').hide();

		//after affirmation of image
		$('#useimagebutton').on('click', function () {
			$('#shortmessageform').show();
			$('#upload-really').show();

		});

		//save image and text
		$('#upload-really').on('click', function (ev) {
			$uploadCrop.croppie('result', {
				type: 'canvas',
				size: 'viewport',
			}).then(function (resp) {
				callAjax(resp);
			});
		});

		$('.vanilla-rotate').on('click', function(ev) {
			$uploadCrop.croppie('rotate', parseInt($(this).data('deg')) );
		});
			
		//after uploading an image
		$('#upload').on('change', function () { 
			$('#uploadwrap').show();
			readFile(this); 
			$('#rotatebutton').show();
			//$('#shortmessageform').hide();
			$('#useimagebutton').show();
			$( "#uploadtext" ).html( "I want to use another image" );
		});

		//preview an image
		$('.upload-result').on('click', function (ev) {
			$uploadCrop.croppie('result', {
				type: 'canvas',
				size: 'viewport'
			}).then(function (resp) {
				popupResult({
					src: resp
				});
			});
		});
	}

	function demoHidden() {
		var $hid = $('#hidden-demo');

		$hid.croppie({
			viewport: {
				width: 175,
				height: 175,
				type: 'circle'
			},
			boundary: {
				width: 200,
				height: 200
			}
		});
		$hid.croppie('bind', 'demo/demo-3.jpg');
		$('.show-hidden').on('click', function () {
			$hid.toggle();
			$hid.croppie('bind');
		});
	}

	function bindNavigation () {
		var $body = $('body');
		$('nav a').on('click', function (ev) {
			var lnk = $(ev.currentTarget),
				href = lnk.attr('href'),
				targetTop = $('a[name=' + href.substring(1) + ']').offset().top;

			$body.animate({ scrollTop: targetTop });
			ev.preventDefault();
		});
	}

	function init() {
		//bindNavigation();
		//demoMain();
		//demoBasic();	
		//demoVanilla();	
		//demoResizer();
		demoUpload();
		//demoHidden();
	}

	return {
		init: init
	};
})();


// Full version of `log` that:
//  * Prevents errors on console methods when no console present.
//  * Exposes a global 'log' function that preserves line numbering and formatting.
(function () {
  var method;
  var noop = function () { };
  var methods = [
      'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
      'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
      'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
      'timeStamp', 'trace', 'warn'
  ];
  var length = methods.length;
  var console = (window.console = window.console || {});
 
  while (length--) {
    method = methods[length];
 
    // Only stub undefined methods.
    if (!console[method]) {
        console[method] = noop;
    }
  }
 
 
  if (Function.prototype.bind) {
    window.log = Function.prototype.bind.call(console.log, console);
  }
  else {
    window.log = function() { 
      Function.prototype.apply.call(console.log, console, arguments);
    };
  }
})();
