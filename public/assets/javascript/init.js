$(function($){

	var processFile = "assets/inc/ajax.inc.php";
	//对象字面量
	var fx = {
		"initModal" : function() {
			if ( $(".model-windows").length == 0 ) {
				return $("<div>").hide().addClass("modal-window").appendTo("body");
			} else {
				return $(".model-windows");
			}
		},

		"boxin" : function(data, modal) {
			$("<div>")
				.hide()
				.addClass("modal-overlay")
				.click(function(event) {
					fx.boxout(event);
				})
				.appendTo("body");

			modal
				.hide()
				.append(data)
				.appendTo("body");

			$(".modal-window, .modal-overlay").fadeIn("slow");				
		},

		"boxout" : function(event) {
			if (event != undefined) {
				event.preventDefault();
			}

			$("a").removeClass("active");

			$(".modal-window, .modal-overlay").fadeOut("slow", function() {
				$(this).remove();
			});
		}
	};

	$("li").on("click", "a", function(event) {
		//阻止默认行为
		event.preventDefault();

		//添加class属性
		
		var data = $(this).attr("href").replace(/.+?\?(.*)$/, "$1");

		var modal = fx.initModal();
		
		$("<a>")
			.attr("href", "#")   //改变属性
			.addClass("modal-close-btn")
			.html("&times;")  //&tinmes是乘号的转义字符
			.click(function(event) {
				fx.boxout(event);
			})
			.appendTo(modal);

		$.ajax({
			type: "POST",
			url: processFile,
			data: "action=event_view&" + data,
			success: function (data) {
				fx.boxin(data, modal);
			},
			error: function (msg) {
				modal.append(msg);
			}
		});
	});
});
