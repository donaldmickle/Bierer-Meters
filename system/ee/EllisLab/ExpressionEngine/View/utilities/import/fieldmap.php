<?php $this->extend('_templates/default-nav'); ?>

<h1><?=$cp_page_title?></h1>
<?=form_open(ee('CP/URL')->make('utilities/import-converter/import-fieldmap-confirm'), 'class="settings"', $form_hidden)?>
	<?=ee('CP/Alert')->getAllInlines()?>
	<?php if (form_error('unique_check')): ?>
		<div class="alert inline issue">
			<h3><?=lang('file_not_converted')?></h3>
			<p><?=form_error('unique_check')?></p>
		</div>
	<?php endif ?>

	<div class="alert inline warn">
		<p><?=lang('import_password_warning')?></p>
	</div>
	<?php
	$i = 0;
	foreach ($fields[0] as $field): ?>
		<fieldset class="col-group">
			<div class="setting-txt col w-8">
				<h3><?=$field?></h3>
			</div>
			<div class="setting-field col w-8 last">
				<?=form_dropdown('field_'.$i, $select_options, set_value('field_'.$i, ''))?>
			</div>
		</fieldset>
	<?php $i++; endforeach ?>
	<fieldset class="col-group last">
		<div class="setting-txt col w-8">
			<h3><?=lang('plain_text_passwords')?></h3>
			<em><?=lang('plain_text_passwords_desc')?></em>
		</div>
		<div class="setting-field col w-8 last">
			<label class="choice mr chosen yes"><input type="radio" name="encrypt" value="y" <?=set_radio('encrypt', 'y')?> <?php if ( ! isset($_POST['encrypt'])):?> checked="checked"<?php endif ?>> <?=lang('yes')?></label>
			<label class="choice no"><input type="radio" name="encrypt" value="n" <?=set_radio('encrypt', 'n')?>> <?=lang('no')?></label>
		</div>
	</fieldset>
	<fieldset class="form-ctrls">
		<?=cp_form_submit('btn_assign_fields', 'btn_saving')?>
	</fieldset>
</form>