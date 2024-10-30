var mixFormsSizeIframe = function() {
	// iframe.style.height = iframe.contentWindow.document.body.scrollHeight + "px";
	var iframe = document.getElementById("formbuilderiframe");
	iframe.style.height = "0px";
	var height = document.body.scrollHeight;// - iframe.getBoundingClientRect().top;// - 70;
	iframe.style.height = height + "px";
};

var setFullScreen = function(goFullScreen) {
	var el = document.querySelector(".mixforms-container");

	if (goFullScreen) {
		el.classList.add("mixforms-container--fullscreen");
	} else {
		el.classList.remove("mixforms-container--fullscreen");
	}

	localStorage.setItem("mixForms.editor.fullscreen", goFullScreen ? 1 : 0);
};

var mixFormsLoadBindFullscreen = function() {
	document.querySelector(".fullscreen-toggler--close").addEventListener("click", setFullScreen.bind(window, false));
	document.querySelector(".fullscreen-toggler--open").addEventListener("click", setFullScreen.bind(window, true));

	if (localStorage.getItem("mixForms.editor.fullscreen") == 1) {
		setFullScreen(true);
	}
};

window.addEventListener("load", function() {
	if (document.querySelector(".fullscreen-toggler")) { mixFormsLoadBindFullscreen(); }
	if (document.querySelector("#formbuilderiframe")) { mixFormsSizeIframe(); }
});

window.addEventListener("resize", mixFormsSizeIframe);