/*!
* dropPin - because image maps are icky
* http://duncanheron.github.com/dropPin/
*
*/
(function( $ ){

	$.fn.dropPin = function(method) {

		var defaults = {
		enabled: 'true',
		fixedHeight: 500,
		fixedWidth: 500,
		dropPinPath: '/js/dropPin/',
		pin: 'dropPin/defaultpin@2x.png',
		yellowPin: '',
		redPin: '',
		backgroundImage: 'dropPin/access-map.png',
		backgroundColor: '#9999CC',
		xoffset : 10,
		yoffset : 30, //need to change this to work out icon heigh/width then subtract margin from it
		cursor: 'crosshair',
		pinclass: '',
		userevent: 'click',
		hiddenXid: '#xcoord', //used for saving to db via hidden form field
		hiddenYid: '#ycoord', //used for saving to db via hidden form field
		pinX: false, //set to value if you pass pin co-ords to overirde click binding to position
		pinY: false, //set to value if you pass pin co-ords to overirde click binding to position
		pinDataSet: '' //array of pin coordinates for front end render
	}



	var methods = {
		init: function(options) {

			var options =  $.extend(defaults, options);
			var thisObj = this;

			this.css({'cursor' : options.cursor, 'background-color' : options.backgroundColor , 'background-image' : "url('"+options.backgroundImage+"')",'height' : options.fixedHeight , 'width' : options.fixedWidth});
			var i = 10;
			thisObj.on(options.userevent, function (ev) {

				$('.pin').remove();

				i = i + 10;
				var $img = $(thisObj);
				var offset = $img.offset();
				var x = ev.pageX - offset.left;
				var y = ev.pageY - offset.top;

				var xval = (x - options.xoffset);
				var yval = (y - options.yoffset);
				var imgC = $('<img class="pin">');
				imgC.css('top', yval+'px');
				imgC.css('left', xval+'px');
				imgC.css('z-index', i);
				
				imgC.attr('src',  options.pin);

				imgC.appendTo(thisObj);
				$(options.hiddenXid).val(xval);
				$(options.hiddenYid).val(yval);

				// add hidden fields - can use these to save to database
				var hiddenCtl= $('<input type="hidden" name="hiddenpin" class="pin">');
		        hiddenCtl.css('top', y);
		        hiddenCtl.css('left', x);
		        hiddenCtl.val(x + "#" + y);
		        hiddenCtl.appendTo(thisObj);

			});

		},
		dropMulti: function(options) {

			var options =  $.extend(defaults, options);
			var thisObj = this;

			thisObj.css({'cursor' : options.cursor, 'background-color' : options.backgroundColor , 'background-image' : "url('"+options.backgroundImage+"')",'height' : options.fixedHeight , 'width' : options.fixedWidth});
			var i = 10;
			if(options.pinDataSet != '' && options.pinDataSet.markers.length > 0) {
			for(var j=0; j < (options.pinDataSet).markers.length; j++)
			{
			i = i + 10;
				var dataPin = options.pinDataSet.markers[j];

				var imgC = $('<img class="pin" style="top:'+(dataPin.ycoord - options.yoffset)+'px;left:'+(dataPin.xcoord - options.xoffset)+'px;">');
				imgC.css('z-index', i);
				imgC.attr('src',  options.pin);
				imgC.attr('title',  "Platz "+(j+1));
				imgC.appendTo(this);
				var hiddenCtl= $('<input type="hidden" name="hiddenpin[]" class="pin">');
		        hiddenCtl.css('top', dataPin.ycoord - options.yoffset);
		        hiddenCtl.css('left', dataPin.xcoord - options.xoffset);
		        hiddenCtl.val(dataPin.xcoord  + "#" + dataPin.ycoord);
		        hiddenCtl.appendTo(thisObj);
			}
			}
			
			thisObj.on(options.userevent, function (ev) {

				i = i + 10;
				var $img = $(thisObj);
				var offset = $img.offset();
				var x = ev.pageX - offset.left;
				var y = ev.pageY - offset.top;

				var xval = (x - options.xoffset);
				var yval = (y - options.yoffset);
				var imgC = $('<img class="pin">');
				imgC.css('top', yval+'px');
				imgC.css('left', xval+'px');
				imgC.css('z-index', i);

				imgC.attr('src',  options.pin);

				imgC.appendTo(thisObj);
				// console.log(ev.target);
				$(options.hiddenXid).val(xval);
				$(options.hiddenYid).val(yval);

				// add hidden fields - can use these to save to database
				var hiddenCtl= $('<input type="hidden" name="hiddenpin[]" class="pin">');
		        hiddenCtl.css('top', y);
		        hiddenCtl.css('left', x);
		        hiddenCtl.val(x + "#" + y);
		        hiddenCtl.appendTo(thisObj);

			});

		},
		showPin: function(options) {

			var options =  $.extend(defaults, options);

			this.css({'cursor' : options.cursor, 'background-color' : options.backgroundColor , 'background-image' : "url('"+options.backgroundImage+"')",'height' : options.fixedHeight , 'width' : options.fixedWidth});

			var xval = (options.pinX);
			var yval = (options.pinY);
			var imgC = $('<img class="pin">');
			imgC.css('top', yval+'px');
			imgC.css('left', xval+'px');

			imgC.attr('src',  options.pin);

			imgC.appendTo(this);
			$(options.hiddenXid).val(xval);
			$(options.hiddenYid).val(yval);

		},
		showPins: function(options) {

			var options =  $.extend(defaults, options);

			this.css({'cursor' : options.cursor, 'background-color' : options.backgroundColor , 'background-image' : "url('"+options.backgroundImage+"')",'height' : options.fixedHeight , 'width' : options.fixedWidth});
			var mapdata= $('<input type="hidden" name="mapdata">');
		        mapdata.appendTo(this);
			for(var i=0; i < (options.pinDataSet).markers.length; i++)
			{
				var dataPin = options.pinDataSet.markers[i];

				var imgC = $('<img data-pin="'+i+'" class="pin '+options.pinclass+'" style="top:'+(dataPin.ycoord - options.yoffset)+'px;left:'+(dataPin.xcoord - options.xoffset)+'px;">');
				imgC.attr('src',  options.pin);
				if(dataPin.selected === true) {
				imgC.attr('src',  options.yellowPin);
				}
				if(dataPin.enabled != true) {
				imgC.attr('src',  options.redPin);
				}
				
				imgC.attr('title',  "Platz "+(i+1));

				imgC.appendTo(this);
				
			}
			$(".pin").on("click", function(e) {
			if(options.enabled === "true") {
				var dataPin = options.pinDataSet.markers[$(this).data("pin")];
				if (dataPin.enabled === true) {
				if (dataPin.selected) {
				dataPin.selected= false;
				$(this).attr('src',  options.pin);
				} else {
				dataPin.selected= true;
				$(this).attr('src',  options.yellowPin);
				}
				options.pinDataSet.markers[$(this).data("pin")] = dataPin;
				mapdata.val($.toJSON (options.pinDataSet));
				}
				}
				});
		},
		showPinsBookings: function(options) {

			var options =  $.extend(defaults, options);

			this.css({'cursor' : options.cursor, 'background-color' : options.backgroundColor , 'background-image' : "url('"+options.backgroundImage+"')",'height' : options.fixedHeight , 'width' : options.fixedWidth});
			var mapdata= $('<input type="hidden" name="mapdata">');
		        mapdata.appendTo(this);
			for(var i=0; i < (options.pinDataSet).markers.length; i++)
			{
				var dataPin = options.pinDataSet.markers[i];

				var imgC = $('<img data-pin="'+i+'" class="pin '+options.pinclass+'" style="top:'+(dataPin.ycoord - options.yoffset)+'px;left:'+(dataPin.xcoord - options.xoffset)+'px;">');
				imgC.attr('src',  options.pin);
				if(dataPin.booked == true) {
				imgC.attr('src',  options.redPin);
				}
				
				imgC.attr('title',  "Platz "+(i+1));

				imgC.appendTo(this);
				
			}
			function toggleYellow(pin) {
			var pd = this.$('img[data-pin="'+pin+'"]');
			var dataPin = options.pinDataSet.markers[pd.data("pin")];
			if (dataPin.booked) {
			if (pd.src == options.pin) {
			pd.src = options.yellowPin;
			} else {
			pd.src = options.pin;
			}
			}
			}
		}
	};

	if (methods[method]) {

		return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));

	} else if (typeof method === 'object' || !method) {

		return methods.init.apply(this, arguments);

	} else {

		alert("method does not exist");

	}


}

})( jQuery );
