<?php

/**
 * Class Es_Html_Helper
 */
class Es_Html_Helper
{
	/**
	 * @param $id
	 * @param $field
	 * @param Es_Entity $entity
	 * @return null|string
	 * @throws Exception
	 */
	public static function render_field( $id, $field, $entity ) {
		$content = null;
		$options = ' ';

		if ( $id == 'ID' && ! $entity->getID() ) return false;
		if ( isset( $field['visible'] ) && ! $field['visible'] ) return;
		if ( empty( $field['type'] ) ) return;

		$field['type'] = ! empty( $field['type'] ) ? $field['type'] : 'text';

		// Generate label if empty.
		if ( empty( $field['label'] ) || ( isset( $field['label'] ) && $field['label']  == false ) ) {
			$field['label'] = static::generate_label( $id );
		} else {
			$field['label'] = ! empty( $field['label'] ) ? $field['label'] : '';
		}

        if ( ! empty( $field['options']['required'] ) && ! empty( $field['label'] ) ) {
            $field['label'] = $field['label'] . '<span class="es-field__required">*</span>';
        }

		if ( empty( $field['template'] ) ) {
			$field['label'] = ! empty( $field['label'] ) ? __( $field['label'], 'es-plugin' ) : '';
			$units_class = ! empty( $field['units'] ) ? 'es-field-area-unit' : null;
			$misc_classes = null;
			if ( $field['type'] == 'wp_editor' ) {
			    $misc_classes = 'es-field__full-width';
            }
			$content = "<div class='es-field es-field-" . $field['type'] . " {$misc_classes} es-field-" . $id . " " . $units_class . "'><div class='es-field__label'>" . $field['label'] . "</div><div class='es-field__content'>";
		}

		if ( ! empty( $field['system'] ) ) {
			if ( ! empty( $field['system_type'] ) && 'taxonomy' == $field['system_type'] ) {
				$terms = wp_get_object_terms( $entity->getID(), $id );
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                    $value = array_map( function( $term ) {
                        return $term->term_id;
                    }, $terms );
                }
			} else {
				$value = $entity->getID() ? $entity->get_entity()->{$id} : null;
			}
		} else {
			$value = $entity->{$id};
		}

		$field_name = $entity->get_base_field_name();

		if ( $id != 'call_for_price' ) {
			if ( ! empty( $value ) || ( isset( $value ) && is_string( $value ) && strlen( $value ) ) ) {
				$field['options']['value'] = $value;
			} else {
				$field['options']['value'] = isset( $field['options']['value'] ) ? $field['options']['value'] : $value;
			}

			if ( empty( $field['options']['value'] ) && ! empty( $field['default_value'] ) ) {
				$field['options']['value'] = $field['default_value'];
			}
		}

		$field['options']['value'] = ! is_array( $field['options']['value'] ) ? esc_attr( $field['options']['value'] ) : $field['options']['value'];

		if ( empty( $field['options']['id'] ) ) {
			$field['options']['id'] = 'es-' . $id . '-input';
		}

        $type_temp = $field['type'];

		if ( isset( $field['options']['required'] ) && empty( $field['options']['required'] ) ) {
			unset( $field['options']['required'] );
		}

		if ( $type_temp == 'file' && ! empty( $field['options']['required'] ) && ! empty( $value ) ) {
            unset( $field['options']['required'] );
        }

		if ( isset( $field['type'] ) && ( 'date' == $field['type'] || 'datetime-local' == $field['type'] ) ) {
			$field['type'] = 'text';
		}

		if ( ! empty( $field['options'] ) ) {
			foreach ( $field['options'] as $key => $option ) {
				if ( $key == 'value' && is_array( $option ) || ( ! $option && ! strlen( $option ) ) ) continue;
				if ( $key == 'multiple' && ! $option ) continue;
				$options .= $key . '="' . esc_attr( $option ) . '" ';
			}
		}

		if ( ! empty( $field['system'] ) ) {
			$name = $field_name . '[system][' . $id . ']';
		} else {
			$name = $field_name . '[' . $id . ']';
		}

		if ( ! empty( $field['options']['multiple'] ) ) {
			$name = $name . '[]';
		}

		if ( ! empty( $field['values_callback'] ) ) {
			$field['values'] = call_user_func($field['values_callback'][0], $field['values_callback'][1]);
		}

		if ( ! empty( $field['formatter'] ) && $field['formatter'] == 'url' ) {
			$field['type'] = $field['formatter'];
		}

		switch ( $field['type'] ) {
			case 'list':

				if ( ( ! empty( $field['values'] ) || ! empty( $field['allow_empty'] ) ) && ! is_wp_error( $field['values'] ) ) {
					$content .= '<select name="' . $name . '" ' . $options .'>';

					if ( ! empty( $field['prompt'] ) ) {
						$content .= '<option value="">' . $field['prompt'] . '</option>';
					} else if ( ! empty( $field['fbuilder'] ) ) {
						$content .= '<option value="">' . __( 'Choose value', 'es-plugin' ) . '</option>';
					}

					foreach ( $field['values'] as $value => $label ) {
						if ( is_array( $field['options']['value'] ) ) {
							$compare_value = stripslashes( $value );
							$selected = selected( true, in_array( $compare_value, $field['options']['value'] ), false );
						} else {
							$compare_value = html_entity_decode( $field['options']['value'], ENT_QUOTES );
							$selected = selected( $compare_value, stripslashes( $value ), false );
						}
						$content .= '<option value="' . esc_attr( $value ) . '" ' . $selected . '>' . stripslashes( esc_attr( $label ) ) . '</option>';
					}
					$content .= '</select>';
				} else {
					return;
				}

				break;

			case 'custom':
				include( $field['template'] );

				break;

			case 'radio':
			case 'checkbox':
				$content .= '<input type="' . $field['type'] . '" name="' . $name . '" id="es-' . $id .'-input"' . $options . ' ' . checked( $value, $field['options']['value'], false ) . '/>';
				break;

			case 'wp_editor':
				ob_start();
				wp_editor( stripslashes( html_entity_decode($field['options']['value'], ENT_QUOTES, 'UTF-8') ), $id, array(
					'textarea_name' => $name,
					'teeny' => true,
				) );
				$content .= ob_get_clean();
				break;

			case 'textarea':
				$content .= '<textarea name="property[' . $id . ']" id="es-' . $id .'-input"' . $options . '>' . ( ! empty( $field['options']['value'] ) ? $field['options']['value'] : null ) . '</textarea>';
				break;

			case 'url':

				$label = ! empty( $value['label'] ) ? $value['label'] : "";
				$url = ! empty( $value['url'] ) ? $value['url'] : "";

				if ( ! empty( $value ) && is_string( $value ) ) {
					$label = $value;
					$url = $value;
				}

				$content .= '<input type="text" placeholder="' . __( 'Link name', 'es-plugin' ) . '" name="property[' . $id . '][label]" value="' . $label . '">';
				$content .= '<input type="text" placeholder="' . __( 'Link URL', 'es-plugin' ) . '" name="property[' . $id . '][url]" value="' . $url . '">';

				break;

			default:

				if ( $type_temp == 'date' && ! empty( $field['range_mode'] ) ) {
					$value = $entity->get_field_value( $id );
					$date_start = ! empty( $value['date_start'] ) ? $value['date_start'] : '';
					$date_end = ! empty( $value['date_end'] ) ? $value['date_end'] : '';
					$content .= '<input type="text" placeholder="' . __( 'Date From', 'es-plugin' ) . '" name="property[' . $id . '][date_start]" value="' . $date_start . '">';
					$content .= '<input type="text" placeholder="' . __( 'Date to', 'es-plugin' ) . '" name="property[' . $id . '][date_end]" value="' . $date_end . '">';
				} else {
					$content .= '<input type="' . $field['type'] . '" name="' . $name . '" id="es-' . $id .'-input"' . $options .'/>';
				}

				if ( 'file' == $field['type'] ) {
					if ( ! empty( $value ) ) {
						$attachment_url = wp_get_attachment_url( $value );
						$finfo = pathinfo($attachment_url);

						if ( $attachment_url ) {
							$content .= "<div class='es-manage-attachments'><a href='{$attachment_url}' target='_blank'>{$finfo['basename']}</a>";
							$content .= "<input type='hidden' name='property[{$id}]' value='{$value}'><a href='#' class='es-delete js-es-remove-attachment'><i class='fa fa-times-circle' aria-hidden='true'></i></input></a></div>";
						}
					}
				}
		}


		if ( ! empty( $field['units'] ) ) {
			if ( ! empty( $entity  ) ) {
				$fields = $entity::get_fields();
				$content .= self::render_field( $field['units'], $fields[ $field['units'] ], $entity );
			}
		}

		if ( empty( $field['template'] ) ) {
			$content .= '</div></div>';
		}

		return apply_filters( 'es_render_field', $content, $id, $field, $field_name, $options );
	}

	/**
	 * Generate label for field using field id.
	 *
	 * @param $name
	 * @return mixed
	 */
	public static function generate_label( $name )
	{
		return str_replace( '_', ' ', ucfirst( $name ) );
	}

	/**
	 * Generate settings input markup.
	 *
	 * @param $label
	 *    Input label text.
	 * @param $field_name
	 * @param $type
	 * @param array $options
	 * @return string
	 */
	public static function render_settings_field( $label, $field_name, $type, $options = array() )
	{
		$_class = $type == 'wp_editor' ? 'es-field__full-width' : null;

		$template = '<div class="es-field ' . $_class . '">
                        <div class="es-field__label">%s</div>
                        <div class="es-field__content">%s</div>
                    </div>';

		if ( ! empty( $options['data-tooltipster-content'] ) ) {
			$label = $label . ' ' . '<i class="fa fa-question-circle" data-tooltipster-content="' . $options['data-tooltipster-content'] . '" aria-hidden="true"></i>';
			unset( $options['data-tooltipster-content'] );
		}

		$field_name = esc_attr( $field_name );

		$template = apply_filters( 'es_html_helper_settings_fields_template', $template, $label, $options );

		$options_string = null;
		$options['name'] = $field_name;
		$options['value'] = isset( $options['value'] ) ? $options['value'] : false;

		foreach ( $options as $key => $value ) {
			if ( is_array( $value ) || ! $value ) continue;
			$options_string .= $key . '="' . esc_attr( $value ) . '" ';
		}

		switch ( $type ) {
			case 'checkbox':
				$field = "<input type='hidden' name='{$field_name}' value='0'/>
                          <input type='checkbox' {$options_string}>";
				break;

			case 'list':
			case 'select':
			case 'selectbox':
				$field = "<select {$options_string}>";

				if ( ! empty( $options['placeholder'] ) ) {
					$field .= '<option value="">' . $options['placeholder'] . '</option>';
				}

				if ( ! empty( $options['values'] ) ) {
					foreach ( $options['values'] as $pvalue => $plabel ) {
                        $options['value'] = is_array( $options['value'] ) ? $options['value'] : array( $options['value'] );
						$field .= '<option value="' . $pvalue . '" '. selected( true, in_array( $pvalue, $options['value'] ) , false ) .'>' .
						          ( is_string( $plabel ) ? $plabel : $plabel['label'] )
						          . '</option>';
					}
				}

				$field .= '</select>';

				break;

			case 'wp_editor':
				ob_start();

				wp_editor( $options['value'], $field_name, $options['options'] );

				$field = ob_get_clean();

				break;

			default:
				$field = "<input type='{$type}' {$options_string}>";
		}

		$template = sprintf( $template, $label, $field );

		return $template;
	}
}
