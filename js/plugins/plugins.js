jQuery.fn.center = function(parent) {
    if (parent) {
        parent = this.parent();
    } else {
        parent = window;
    }
    this.css({
        "position": "absolute",
        "top": ((($(parent).height() - this.outerHeight(true)) / 2) + "px"),
        "left": ((($(parent).width() - this.outerWidth(true)) / 2) + "px")
    });
	
	console.log(((($(parent).height() - this.outerHeight(true)) / 2) + "px"));
return this;
}