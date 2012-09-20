			</div><!-- close #results div -->
			<div class="clear"></div><!-- clear floats -->
			
		</div><!-- close #content div -->
	</div><!-- close #contentwrapper div -->
	

<script type="text/javascript">
	$(document).ready(function (){
		//reference div where results will be saved
		var results = $("#results");
		
		//reference query entered by user
		var query = $.URLEncode($('#results').attr('class'));
		
		//reference zip entered by user (if no geolocation)
		var zip = $.URLEncode($('#location').attr('class'));  	
		//execute if zip value exists
		if(zip){
			
			//retrieve menu item data from openmenu API
			$.getJSON("http://openmenu.com/api/v1/menu?key=732ecc70-7a96-11e1-97be-00163eeae34c&item="+ query +"&output=json&compact&postal_code="+ zip , function(json) {
			
			//execute if no menu item data returned
			if ($.isEmptyObject(json.response.result) || json.response.api.status === 204)
			{
				//build message displayed to user if no content was returned
				results.append(
							'<div id="nocontent">'+
								'<h3>No results</h3>'+
								'<p>Try different search terms</p>'+	
							'</div>'+
							'</div><div class="clear">'+
						'</div>'+
					'</div>'
				);
			
			//execute if menu item data is returned		
			}else{
				//parse response
				$.each(json.response.result, function(i, dish){
					
					//contact server to get number of users who saved dish
					$.post("<?php echo site_url('member/favecount') ?>", { itemid: dish.menu_item_uid }, function(data){
						
						//reference dish save data from server
						var dishsaves = data;
						
						//reference restaurant address
						var restaurantAdd = dish.address_1 + " " + dish.city_town + " " + dish.state_province;
						restaurantAdd = $.URLEncode(restaurantAdd);
						
						//contact server to see if dish has been bookmarked by current user
						$.post("<?php echo site_url('member/isbookmarked') ?>", { itemid: dish.menu_item_uid }, function(bm){
							//references boolean response of whether user has bookmarked item
							var isbookmarked = bm;
							
							//executes if user has not bookmarked item
							if (!isbookmarked) {
								
								//create result module
								results.append(			
									'<div class="module">' +
										'<div class="result">' +
											'<img src="<?=base_url()?>/img/DishWhere_200.png" alt="Burger 01" width="200" height="200" />' +
											'<h2>' + dish.menu_item_name + '</h2>' + 
											'<h3>' + dish.restaurant_name + '</h3>' +
											'<p>saved by <b>'+ dishsaves +'</b> users</p>' +
										'</div>' +
										
										'<div class="moduleselected">' +
											'<div class="moreinfo">' +
												'<h2>' + dish.menu_item_name + '</h2>' +
												'<p>' + dish.menu_item_description + '</p>' +
												'<div class="options">' +
													'<div class="btn"><div class="btntext"><a href="#" id="' + dish.menu_item_uid + '">Save Dish</a></div></div>' +
													'<div class="btn"><a href="http://maps.google.com/?saddr='+ zip +'&daddr='+ restaurantAdd +'&h1=en" target="_blank">Get Directions</a></div>' +
												'</div>'+
											'</div>' +
										'</div>' +
									'</div>')
									
								//create mouseover effect for result module	
								.find(".module").mouseenter(function(){
									results.find(".moduleselected").hide();
									$(this).find(".moduleselected").show();
								})
								
								//create mouseout effect for result module
								.find(".moduleselected").mouseleave(function(){
									$(this).hide();
								})
								
								//add click event to save dish button
								.find(".btn:first").unbind('click').click(function(e){
										
										//toggle button between save and remove
										e.preventDefault();
										var btn = $(this).find(".btntext").find("a");
										var itemID = btn.attr("id");							
										btn.toggleClass("saved");
										
										if (btn.attr("class") == "saved"){
											btn.text("Remove Dish");
										}else{
											btn.text("Save Dish");
										}
										
										//send dish data to backend
										$.post("<?php echo site_url('member/bookmark') ?>", { itemid: itemID } );
											
								});
							};	
						});	
					});	
				});				
			}
			
			
			
				
			})
		
		//execute if location cookie is found	
		}else if ($.cookie("pLat")){
			
			//reference location cookie latitude
			var lat = $.cookie("pLat");
			
			//reference location cookie longitude
			var lon = $.cookie("pLon");
			
			//reference location cookie zip
			var pzip = $.cookie("pZip");
			
			//reference location cookie address
			var paddress = $.URLEncode($.cookie("pAddress"));
			
			//retrieve menu item data from openmenu API
			$.getJSON("http://openmenu.com/api/v1/menu?key=732ecc70-7a96-11e1-97be-00163eeae34c&item="+ query +"&output=json&compact&postal_code="+ pzip , function(json) {
				
				//execute if no menu item data returned
				if ($.isEmptyObject(json.response.result) || json.response.api.status === 204)
				{
					
					//build message displayed to user if no content was returned
					results.append(
								'<div id="nocontent">'+
									'<h3>No results</h3>'+
									'<p>Try different search terms</p>'+	
								'</div>'+
								'</div><div class="clear">'+
							'</div>'+
						'</div>'
					);
					
				//execute if menu item data is returned	
				}else{
					
					//parse response
					$.each(json.response.result, function(i, dish){
						
						//contact server to get number of users who saved dish
						$.post("<?php echo site_url('member/favecount') ?>", { itemid: dish.menu_item_uid }, function(data){
	
							//reference dish save data from server
							var dishsaves = data;					
							//reference restaurant address
							var restaurantAdd = dish.address_1 + " " + dish.city_town + " " + dish.state_province;
							restaurantAdd = $.URLEncode(restaurantAdd);
							
							//contact server to see if dish has been bookmarked by current user
							$.post("<?php echo site_url('member/isbookmarked') ?>", { itemid: dish.menu_item_uid }, function(bm){
							
								//references boolean response of whether user has bookmarked item
								var isbookmarked = bm;								
								//executes if user has not bookmarked item
								if (!isbookmarked) {
									//create result module
									results.append(			
										'<div class="module">' +
											'<div class="result">' +
												'<img src="<?=base_url()?>/img/DishWhere_200.png" alt="Burger 01" width="200" height="200" />' +
												'<h2>' + dish.menu_item_name + '</h2>' + 
												'<h3>' + dish.restaurant_name + '</h3>' +
												'<p>saved by <b>'+ dishsaves +'</b> users</p>' +
												'<div class="menuid">' + dish.menu_item_uid + '</div>' +
											'</div>' +
											
											'<div class="moduleselected">' +
												'<div class="moreinfo">' +
													'<h2>' + dish.menu_item_name + '</h2>' +
													'<p>' + dish.menu_item_description + '</p>' +
													'<div class="options">' +
														'<div class="btn"><div class="btntext"><a href="#" id=' + dish.menu_item_uid + '>Save Dish</a></div></div>' +
														'<div class="btn"><a href="http://maps.google.com/?saddr='+ paddress +'&daddr='+ restaurantAdd +'&h1=en" target="_blank">Get Directions</a></div>' +
													'</div>' +
												'</div>' +
											'</div>' +
										'</div>')
									
									//create mouseover effect for result module 		
									.find(".module").mouseenter(function(){
										results.find(".moduleselected").hide();
										$(this).find(".moduleselected").show();
									})
									
									//create mouseout effect for result module
									.find(".moduleselected").mouseleave(function(){
										$(this).hide();
									})
									
									//add click event to save dish button
									.find(".btn:first").unbind('click').click(function(e){
										//toggle button between save and remove
										e.preventDefault();
										var btn = $(this).find(".btntext").find("a");
										var itemID = btn.attr("id");							
										btn.toggleClass("saved");
										
										if (btn.attr("class") == "saved"){
											btn.text("Remove Dish");
										}else{
											btn.text("Save Dish");
										}
										
										//send dish data to backend
										$.post("<?php echo site_url('member/bookmark') ?>", { itemid: itemID, restadd:restaurantAdd } );
									});
								};
							});
						});	
					});	
				}
			})
		
		//executes if no geolocation data or zip found	
		}else{
			
			//build message displayed to user if no location data was passed
			results.append('<div id="nocontent">'+
								'<h3>DishWhere was unable to detect your location</h3>'+
								'<p>Please enable Geolocation as prompted by your browser.</p>'+	
							'</div>'+
							'</div><div class="clear">'+
						'</div>'+
					'</div>'
			);
		};	
	});	 	 
</script>