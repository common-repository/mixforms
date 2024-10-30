(function() {
	tinymce.create("tinymce.plugins.mixForms", {
		init: function (editor, url) {
			editor.addButton("mixFormsPickform", {
				title : "Insert mixForms form",
				cmd : "mixFormsPickform",
				image : url + "/get-form.png"
			});

			editor.addCommand("mixFormsPickform", function() {
				jQuery.post(ajaxurl, { action: "my_action" }, function(result) {
					var forms = result;
					var comboboxValues = [];
					for (var i = 0; i < forms.length; i++) {
						var form = forms[i];
						comboboxValues.push({text: form.Name, value: form.Key });
					}

					editor.windowManager.open({
						title: "Select form",
						width: 350,
						height: 90,
						body: [
							{ type: "listbox", name: "form", size: 40, autofocus:true, values: comboboxValues }
						],
						onsubmit: function(e) {
							editor.insertContent("[mixForms key=\"" + e.data.form + "\"]");
						}
					});

				}, "JSON");
			});
		},
		createControl : function(n, cm) {
			return null;
		},

		getInfo: function() {
			return {
				longname: "Pick a mixForms form",
				version: 0.1
			};
		}
	});

	tinymce.PluginManager.add("mixForms", tinymce.plugins.mixForms);
})();