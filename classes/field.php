<?php
if (!defined( 'ABSPATH' )) {
	die( '-1' );
}

if (!class_exists( 'AVA_Fields_Field' )) {
	abstract class AVA_Fields_Field
	{
		public $type;
		public $id;
		public $container_id;
		public $section_id;
		public $params;
		public $html;
        public $options;

        public $field_dir;
        public $field_url;

		public function __construct($container_id, $section_id, $id, $params) {

			$this->id = $id;
			$this->container_id = $container_id;
			$this->section_id = $section_id;
			$this->params = $params;

            $this->options = AVA_Fields()->container($this->container_id)->options->options;

			$this->field_dir = AVA_Fields()->dir('fields') . $this->type . '/';
			$this->field_url = AVA_Fields()->url('fields') . $this->type . '/';

		}

		/**
		 * Get value for output
		 *
		 * @param $key
		 * @param null $key2
		 *
		 * @return mixed
		 */
        public function get_value($key, $key2=null) {
		    if (empty($key2)) {
		        return isset($this->options['options'][$key]) ? $this->options['options'][$key]:'';
            } else {
                return isset($this->options['options'][$key]) && isset($this->options['options'][$key][$key2])  ? $this->options['options'][$key][$key2]:'';
            }
        }

        /*
		public function get_name_attr() {
			return esc_attr( $this->section->id . '['.$this->id.']');
		}

		public function get_id_attr() {
			return esc_attr( 'avaf-'.$this->section->id . '-'.$this->id);
		}
        */

		/**
		 * Render group html
		 *
		 * @return string
		 */
		public function render() {

			$this->html = '';
			$this->build();

			$html = '<div class="avaf-group" data-group="'.esc_attr($this->id).'" data-type="'.esc_attr($this->type).'">';

				// label
				$html .= $this->get_label();

				// field
				$html .= '<div class="avaf-field avaf-field-'.esc_attr($this->type).' col col-sm-10">';
					$html .= $this->get_before();
					$html .= $this->html;
					$html .= $this->get_after();
					$html .= $this->get_desc();
				$html .= '</div>';

			$html .= '</div>';

			return $html;
		}

		/**
		 * 	Get field label
		 */
		public function get_label() {
			$html = '<label class="avaf-label col col-sm-2" for="avaf-'. esc_attr($this->id).'">';
			if (!empty( $this->params['texts']['title'] )) $html .= $this->params['texts']['title'];
			if (!empty( $this->params['texts']['subtitle'] )) $html .= '<small style="">'.$this->params['texts']['subtitle'].'</small>';
			$html .= '</label>';

			return $html;
		}

		/*
		public function add_class($class) {
			if (!empty($this->params['attrs']['class']))
				$this->params['attrs']['class'] = $class.' '.$this->params['attrs']['class'];
			else
				$this->params['attrs']['class'] = $class;
		}
		*/


		/**
		 * Get field attributes
		 *
		 */
		public function get_attrs() {
			if (empty($this->params['attrs']) && !is_array($this->params['attrs'])) return '';

			$attrs = array();
			foreach($this->params['attrs'] as $key=>$value) {
				$attrs[] = esc_attr($key).' = "'.esc_attr($value).'"';
			}
			return implode(' ', $attrs);
		}

		/**
		 * Get field description
		 *
		 */
		public function get_desc() {
			if (empty($this->params['texts']['desc'])) return '';

			return '<div class="avaf-desc">' . $this->params['texts']['desc'] . '</div>';
		}

		/**
		 * Get text before
		 *
		 */
		public function get_before() {
			if (empty($this->params['texts']['before'])) return '';

			return '<span class="avaf-before">' . $this->params['texts']['before'] . '</span>';
		}

		/**
		 * Get text after
		 *
		 */
		public function get_after() {
			if (empty($this->params['texts']['after'])) return '';

			return '<span class="avaf-after">' . $this->params['texts']['after'] . '</span>';
		}

		public function add_handler( $dir ) {
			AVA_Fields()->handlers[] = $dir;
		}

		public function get_unique_id( $suffix ) {
			return AVA_Fields()->container($this->container_id)->id . '-' . AVA_Fields()->section($this->container_id, $this->section_id)->id . '-' . preg_replace('/[^a-z0-9_-]/i', '_', $suffix);
		}

		/**
		 * Render field html
		 *
		 * @return mixed
		 */
		abstract public function build();
	}
}

