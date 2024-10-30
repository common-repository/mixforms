<?php
	global $mixForms;
?>

<style>
	.forms-table {
		width:50%;
		max-width:800px;
	}
	.forms-table td,
	.forms-table th {
		text-align:left;
		line-height:20px;
	}

	.forms-table tr:nth-child(even) {
		background:white;
	}
</style>

<div class="wrap">
	<img src="<?php echo $mixForms->baseUrl;?>/app/images/logo-transparent.png" class="logo" />

	<?php
		$userHash = get_option("mixForms.userHash", "");
		$mixForms->setKey($userHash);
		$forms = $mixForms->getForms();
	?>

	<form action="<?php echo esc_url( admin_url('admin-post.php'));?>" method="post">
		<input type="hidden" name="action" value="fb_saveSettings" />
		<input type="hidden" name="redirectTo" value="<?php echo menu_page_url("mixFormsSettings", false); ?>" />
		<table>
			<tr>
				<th>Enter your user Hash</th>
				<td><input style="width:350px;" name="userHash" value="<?php echo $userHash; ?>" /></td>
				<td><button>Save</button></td>
				<?php
					if (!$mixForms->hasValidKey()) {
						$url = menu_page_url("mixFormsRegister", false);
						echo "<td>Or <a href='$url'>register</a> for a new free account!</td>";
					}
				?>
			</td>
		</table>
	</form>

	<?php
		if ($forms !== null && $forms !== false) {

			echo "<h3>Forms</h3>";

			echo "<table class='forms-table' cellspacing=0 cellpadding=3><thead><tr><th>Form</th><th>Edit</th><th>Submissions</th></tr></thead><tbody>";
			foreach($forms as $form) {
				echo "<tr>
					<td>$form->Name</td>
					<td><a href='". menu_page_url("mixFormsEditor", false). "&formKey=". $form->Key. "'>Edit</a></td>
					<td><a href='". menu_page_url("mixFormsSubmissions", false). "&formKey=". $form->Key. "'>View</a></td>
				</tr>";
			}

			echo "</tbody></table>";
		}
	?>
</div>