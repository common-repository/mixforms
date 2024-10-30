<?php
	/*
		Plugin Name: MixForms
		Description: MixForms Formbuilder
		Version: 1.11
	*/

	class MixForms {
		public $key;

		public $env;

		public $baseUrl;

		public $forms;

		public $frontEndLoaded = false;

		public function __construct() {
			$this->env = "live";
			$this->baseUrl = "https://mix-forms.com";
			$this->baseUrlViewer = "https://view.mix-forms.com";

			$this->scriptsUrl = plugins_url("/assets/js/scripts-admin.js", __FILE__); // TODO .min
			$this->scriptsUrlFrontend = plugins_url("/assets/js/scripts-frontend.js", __FILE__); // TODO .min
			$this->stylesUrl = plugins_url("/assets/styles/styles.css", __FILE__);

			add_shortcode("mixForms", function($attributes) {
				if (!$this->frontEndLoaded) {
					$this->frontEndLoaded = true;

					wp_enqueue_script("script", $this->scriptsUrlFrontend);
				}

				$key = $attributes["key"];
				$dataOptions = Array();

				if (isset($attributes["autofocus"])) {
					$dataOptions[] = "autoFocus='". $attributes["autofocus"]. "'";
				}

				// $dataOptions[] = "v=3";

				$editLink = "";

				if (is_user_logged_in()) {
					$aStyle = "style='text-decoration: none; margin-left: 15px;'";

					$editLink .= "<div style='text-align:right; padding: 5px; background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.2);'>";
						$editLink .= "<a $aStyle href='". admin_url("admin.php?page=mixFormsEditor&formKey=$key"). "'>Edit this form</a>";
						$editLink .= "<a $aStyle href='". admin_url("admin.php?page=mixFormsSubmissions&formKey=$key"). "'>View submissions</a>";
					$editLink .= "</div>";
				}

				$dataOptions = count($dataOptions) > 0 ? "?". implode(" ", $dataOptions) : "";

				if (isset($attributes["style"]) && $attributes["style"] === "iframe") {
					$style = "width:100%; border:none; background:transparent;";
					return '<iframe scrolling="no" id="mixForms" style="'. $style. '" src="'. $this->baseUrlViewer. '/'. $key. $dataOptions. '"></iframe>'. $editLink;
				} else {
					return '<script id="formBuilderScript" data-cms="wordpress" src="'. $this->baseUrlViewer. '/'. $key. '/js'. $dataOptions. '"></script>'. $editLink;
				}
			});

			add_action("init", function() {

				add_filter("mce_external_plugins", function($plugins) {
					$plugins["mixForms"] = plugins_url("/assets/js/tinymce/plugin.js", __FILE__);

					return $plugins;
				});

				add_filter("mce_buttons", function($buttons) {
					$buttons[] = "mixFormsPickform";

					return $buttons;
				});
			});

			add_action("wp_ajax_my_action", function() {
				echo json_encode($this->getForms());

				wp_die();
			});


			// register_activation_hook(__FILE__, function() {

			// });

			// register_deactivation_hook(__FILE__, function() {

			// });


			add_action("admin_menu", function() {
				$templateRoot = plugin_dir_path(__FILE__). "templates/";

				// add_menu_page("mixForms", "mixForms", "manage_options", "mixFormsSettings", function() use ($templateRoot) {
				// 	require $templateRoot. "settings.php";
				// }, "dashicons-feedback", 10);

				$openSettings = function() use ($templateRoot) {
					global $mixForms;
					require $templateRoot. "settings.php";
				};
				$openEditor = function() use ($templateRoot) {
					global $mixForms;
					require $templateRoot. "editor.php";
				};
				$openRegister = function() use ($templateRoot) {
					global $mixForms;
					require $templateRoot. "register.php";
				};
				$openSubmissions = function() use ($templateRoot) {
					global $mixForms;
					require $templateRoot. "submissions.php";
				};

				$iconData = 'data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyINCiB3aWR0aD0iMjYwLjAwMDAwMHB0IiBoZWlnaHQ9IjI2MC4wMDAwMDBwdCIgdmlld0JveD0iMCAwIDI2MC4wMDAwMDAgMjYwLjAwMDAwMCINCiBwcmVzZXJ2ZUFzcGVjdFJhdGlvPSJ4TWlkWU1pZCBtZWV0Ij4NCjxtZXRhZGF0YT4NCkNyZWF0ZWQgYnkgcG90cmFjZSAxLjExLCB3cml0dGVuIGJ5IFBldGVyIFNlbGluZ2VyIDIwMDEtMjAxMw0KPC9tZXRhZGF0YT4NCjxnIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAuMDAwMDAwLDI2MC4wMDAwMDApIHNjYWxlKDAuMTAwMDAwLC0wLjEwMDAwMCkiDQpmaWxsPSIjMDAwMDAwIiBzdHJva2U9Im5vbmUiPg0KPHBhdGggZD0iTTEzMCAxMzAwIGwwIC0xMTgwIDExNzUgMCAxMTc1IDAgMCAxMTgwIDAgMTE4MCAtMTE3NSAwIC0xMTc1IDAgMA0KLTExODB6IG0yMTkwIDAgbDAgLTEwMjAgLTEwMTUgMCAtMTAxNSAwIDAgMTAyMCAwIDEwMjAgMTAxNSAwIDEwMTUgMCAwIC0xMDIweiIvPg0KPHBhdGggZD0iTTIwNzggMjE1MCBjLTM2OSAtMjMzIC03NzcgLTY0MiAtMTA0OSAtMTA1MyAtNDMgLTY1IC04MSAtMTE5IC04NA0KLTEyMCAtNCAtMSAtMjEgMzIgLTM5IDczIC00OSAxMTEgLTEwMCAxOTIgLTE0NiAyMzMgLTMzIDMxIC00OCAzNyAtODMgMzcgLTU2DQowIC0xMzMgLTM5IC0yMDAgLTEwMCBsLTUxIC00NyAzMiAtMTcgYzQ1IC0yMyA5OCAtNzIgMTMyIC0xMjEgNTQgLTc4IDE3Nw0KLTM0OCAyMzMgLTUwOCA2IC0xNSAxOCAtOSA4NSA0MCA0MyAzMiAxMDEgNzMgMTI5IDkyIDQwIDI3IDU3IDQ4IDgzIDEwMCAxNDUNCjI5NyA0NzUgNzYxIDc1NiAxMDYzIDc3IDgyIDIxMCAyMDggMjg4IDI3NCBsNTAgNDEgLTIzIDMxIGMtMTIgMTggLTI1IDMyIC0yOQ0KMzEgLTQgMCAtNDIgLTIyIC04NCAtNDl6Ii8+DQo8L2c+DQo8L3N2Zz4=';
				add_menu_page("MixForms", "MixForms", "manage_options", "mixFormsSettings", $openSettings, $iconData, 10);

				add_submenu_page("mixFormsSettings", "Settings", "Settings", "manage_options", "mixFormsSettings", $openSettings);

				if ($this->hasValidKey()) {
					add_submenu_page("mixFormsSettings", "Editor", "Editor", "manage_options", "mixFormsEditor", $openEditor);

					add_submenu_page("mixFormsSettings", "Submissions", "Submissions", "manage_options", "mixFormsSubmissions", $openSubmissions);
				}

				if ($this->env === "local" || !$this->hasValidKey()) {
					add_submenu_page("mixFormsSettings", "Register", "Register", "manage_options", "mixFormsRegister", $openRegister);
				}
				// remove_submenu_page("mixFormsSettings", "mixFormsSettings");

			});

			add_action("admin_post_fb_register", array($this, "register"));
			add_action("admin_post_fb_saveSettings", array($this, "saveSettings"));

			// $this->forms = $this->getForms();
		}

		public function hasValidKey() {
			return !empty($this->key); // Todo: api call
		}

		public function setKey($key) {
			$this->key = $key;

			// $this->forms = $this->getForms();
		}

		protected function api($action, $params) {
			// echo $action; exit;

			if (empty($this->key)) {
				return null;
			}

			$url = $this->baseUrl. "/api/". $action;
			$debugUri = $url. "?". http_build_query($params);

			// echo $debugUri;exit;

			$error = false;

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
			curl_setopt($curl, CURLOPT_POST, true);

			curl_setopt($curl, CURLOPT_URL, $url);

			curl_setopt($curl, CURLOPT_TIMEOUT, 50);

			$rawResult = curl_exec($curl);

			$result = null;

			if (curl_errno($curl) > 0) {
				$error = curl_error($curl);
				// throw new Exception(curl_error($curl). "<br><br>". $rawResult);
			} else {
				$result = json_decode($rawResult);

				if ($result === null) {
					$error = "JSON encoding error\n". $rawResult;
				} elseif ($result->success !== true) {
					$error = $result->message. " - ". $result->error;
				}
			}

			if ($error !== false) {
				echo "<div style='padding:30px; color:red; background:#ffeded; border:1px solid #fdd; margin:20px 0;'>There is an error while connecting to MixForms. The errormessage is: ". $error. "</div>";
				return null;
			} else {
				return $result;
			}
		}

		public function getForms() {
			$result = $this->api("Formbuilder.getForms", Array("userHash" => $this->key));

			return $result === null ? null : $result->data;
		}

		public function saveSettings() {
			update_option("mixForms.userHash", $_POST["userHash"]);
			$url = $_POST["redirectTo"];
			header("Location: $url");
			exit;
		}

		public function register() {
			// echo "ok ok, je wilt registreren. Monumentje...";

			$result = $this->api("Framework.registerUser", $_POST);

			if ($result !== null && $result->success) {
				$user = $result->data;

				if ($user !== null && !empty($user->Hash)) {
					update_option("mixForms.userHash", $user->Hash);
					// $url = menu_page_url("mixFormsEditor", false);
					$url = $_POST["redirectTo"];
					header("Location: $url");
					exit;
				} else {
					$error = "<p>Registratie niet gelukt. ". print_r($user). "</p>";
				}
			} else {
				$error = "<p>Registratie niet gelukt. ". print_r($user). "</p>";
			}
		}
	}

	$mixForms = new MixForms();
	$mixForms->setKey(get_option("mixForms.userHash"));