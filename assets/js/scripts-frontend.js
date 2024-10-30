window.addEventListener("message", function(event) {
	if (event && event.origin && (event.origin === "http://view.formbuilder.local.nl" || event.origin === "https://view.mix-forms.com")) {
		var iframe = document.getElementById("mixForms");
		iframe.style.height = event.data + "px";
	}
});