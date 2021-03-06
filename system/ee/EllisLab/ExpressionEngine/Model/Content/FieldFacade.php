<?php

namespace EllisLab\ExpressionEngine\Model\Content;

class FieldFacade {

	private $id;
	private $data; // field_id_*
	private $format;  // field_ft_*
	private $timezone; // field_dt_*
	private $metadata;
	private $required;
	private $field_name;
	private $content_id;
	private $content_type;
	private $value;

	/**
	 * @var Flag to ensure defaults are only loaded once
	 */
	private $populated = FALSE;

	public function __construct($field_id, array $metadata)
	{
		$this->id = $field_id;
		$this->metadata = $metadata;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setName($name)
	{
		$this->field_name = $name;
	}

	public function getName()
	{
		return $this->field_name;
	}

	public function getShortName()
	{
		return $this->getItem('field_name') ?: $this->getName();
	}

	public function setContentId($id)
	{
		$this->content_id = $id;
	}

	public function getContentId()
	{
		return $this->content_id;
	}

	public function setContentType($type)
	{
		$this->content_type = $type;
	}

	public function getContentType($type)
	{
		return $this->content_type;
	}

	public function setTimezone($tz)
	{
		$this->timezone = $timezone;
	}

	public function getTimezone()
	{
		return $this->timezone;
	}

	protected function ensurePopulatedDefaults()
	{
		if ($this->populated)
		{
			return;
		}

		$this->populated = TRUE;

		if ($callback = $this->getItem('populateCallback'))
		{
			call_user_func($callback, $this);
		}
		elseif ($data = $this->getItem('field_data'))
		{
			$this->setData($data);
		}
	}

	public function setData($data)
	{
		$this->ensurePopulatedDefaults();
		$this->data = $data;
	}

	public function getData()
	{
		$this->ensurePopulatedDefaults();
		return $this->data;
	}

	public function setFormat($format)
	{
		$this->format = $format;
	}

	public function getFormat()
	{
		return $this->format;
	}

	public function isRequired()
	{
		$required = $this->getItem('field_required');
		return ($required === TRUE || $required === 'y');
	}

	public function getItem($field)
	{
		if (array_key_exists($field, $this->metadata))
		{
			return $this->metadata[$field];
		}

		return NULL;
	}

	public function setItem($field, $value)
	{
		$this->metadata[$field] = $value;
	}

	public function getType()
	{
		return $this->getItem('field_type');
	}

	public function getTypeName()
	{
		ee()->legacy_api->instantiate('channel_fields');
		$fts = ee()->api_channel_fields->fetch_all_fieldtypes();
		$type = $this->getType();
		return $fts[$type]['name'];
	}

	public function validate($value)
	{
		$this->initField();

		$result = ee()->api_channel_fields->apply('validate', array($value));

		if (is_array($result))
		{
			if (isset($result['value']))
			{
				$this->setData($result['value']);

				$result = (isset($result['error'])) ? $result['error'] : TRUE;
			}

			if (isset($result['error']))
			{
				$result = $result['error'];
			}
		}

		if (is_string($result) && strlen($result) > 0)
		{
			return $result;
		}

		return TRUE;
	}

	public function save($model = NULL)
	{
		$this->ensurePopulatedDefaults();

		$value = $this->data;
		$this->initField();
		return $this->data = ee()->api_channel_fields->apply('save', array($value, $model));
	}

	public function postSave()
	{
		$value = $this->data;
		$this->initField();
		return $this->data = ee()->api_channel_fields->apply('post_save', array($value));
	}

	public function getForm()
	{
		$data = $this->initField();

		$field_value = $data['field_data'];

		return ee()->api_channel_fields->apply('display_publish_field', array($field_value));
	}

	public function getSettingsForm()
	{
		ee()->load->library('table');
		$data = $this->initField();
		$out = ee()->api_channel_fields->apply('display_settings', array($data));

		if ($out == '')
		{
			return ee()->table->rows;
		}

		return $out;
	}

	public function validateSettingsForm($settings)
	{
		$this->initField();
		return ee()->api_channel_fields->apply('validate_settings', array($settings));
	}

	public function saveSettingsForm($data)
	{
		$this->initField();
		return ee()->api_channel_fields->apply('save_settings', array($data));
	}

	/**
	 * Fires post_save_settings on the fieldtype
	 */
	public function postSaveSettings($data)
	{
		$this->initField();
		return ee()->api_channel_fields->apply('post_save_settings', array($data));
	}

	public function delete()
	{
		$this->initField();
		return ee()->api_channel_fields->apply('delete', array(array($this->getContentId())));
	}

	public function getStatus()
	{
		$data = $this->initField();

		$field_value = set_value(
			$this->getName(),
			$data['field_data']
		);

		return ee()->api_channel_fields->apply('get_field_status', array($field_value));
	}


	// TODO THIS WILL MOST DEFINITELY GO AWAY! BAD DEVELOPER!
	public function getNativeField()
	{
		$data = $this->initField();
		return ee()->api_channel_fields->setup_handler($this->getType(), TRUE);
	}


	public function initField()
	{
		$this->ensurePopulatedDefaults();

        // not all custom field tables will specify all of these things
         $defaults = array(
             'field_instructions' => '',
             'field_text_direction' => 'rtl',
             'field_settings' => array()
         );

         $info = $this->metadata;
         $info = array_merge($defaults, $info);

         if (is_null($this->format) && isset($info['field_fmt']))
         {
             $this->setFormat($info['field_fmt']);
         }

         if (is_null($this->timezone) && isset($info['field_dt']))
         {
             $this->setTimezone($info['field_dt']);
         }

		$data = $this->setupField();

		ee()->api_channel_fields->setup_handler($data['field_id']);
		ee()->api_channel_fields->apply('_init', array(array(
			'content_id' => $this->content_id,
			'content_type' => $this->content_type
		)));

		return $data;
	}

	protected function setupField()
	{
		$field_dt = $this->timezone;
		$field_fmt = $this->format;
		$field_data = $this->data;
		$field_name = $this->getName();

		// not all custom field tables will specify all of these things
		$defaults = array(
			'field_instructions' => '',
			'field_text_direction' => 'rtl',
			'field_settings' => array()
		);

		$info = $this->metadata;
		$info = array_merge($defaults, $info);

		$settings = array(
			'field_instructions'	=> trim($info['field_instructions']),
			'field_text_direction'	=> ($info['field_text_direction'] == 'rtl') ? 'rtl' : 'ltr',
			'field_fmt'				=> $field_fmt,
			'field_dt'				=> $field_dt,
			'field_data'			=> $field_data,
			'field_name'			=> $field_name
		);

		$field_settings = empty($info['field_settings']) ? array() : $info['field_settings'];

		$settings = array_merge($info, $settings, $field_settings);

		ee()->legacy_api->instantiate('channel_fields');
		ee()->api_channel_fields->set_settings($info['field_id'], $settings);

		return $settings;
	}
}

// EOF
