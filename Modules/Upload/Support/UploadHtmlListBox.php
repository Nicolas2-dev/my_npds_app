<?php


namespace Modules\Upload\Support;

/**
 * Undocumented class
 */
class UploadHtmlListBox
{

    /**
     * Undocumented function
     */
    private string $name;
    
    /**
     * Undocumented function
     */
    private array $items = [
        '1' => 'Yes',
        '0' => 'No',
    ];
    
    /**
     * Undocumented function
     */
    private bool $selected; 
    
    /**
     * Undocumented function
     */
    private bool $multiple;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private string $onChange;

    /**
     * Undocumented variable
     *
     * @var string
     */
    private $stub = [
        'select_start'    => '<select class="form-select-sm" name="%s%s"%s%s>',
        'select_end'      => '</select>',
        'option'          => '<option value="%s"%s>%s</option>'
    ];

    /**
     * Undocumented function
     */
    public function __construct($name, $items, $selected = 0, $multiple = 0, $onChange = '')
    {
        $this->name         = $name;
        $this->items        = $items;
        $this->selected     = $selected;
        $this->multiple     = $multiple;
        $this->onChange     = $onChange;
    }

    /**
     * Undocumented function
     *
     * @param [type] $name
     * @param integer $value
     * @param [type] $current
     * @param string $text
     * @return void
     */
    public function display()
    {
        $display  = $this->get_select('start');
        $display .= $this->get_options();
        $display .= $this->get_select('end');

        return $display;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    private function get_name()
    {
        return $this->name;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    private function get_select($type = 'start')
    {
        if ($type == 'start') {
            return sprintf(
                $this->start_select(),
                $this->get_name(),
                $this->get_multipe_array(),
                $this->get_multiple(),
                $this->get_onChange()
            );
        } elseif ($type == 'end') {
            return $this->end_select();
        }
    }

    /**
     * Undocumented function
     *
     * @return bool
     */
    private function get_options()
    {
        $options = '';

        if (is_array($this->get_items())) {
            foreach ($this->get_items() as $key => $value) {
                $options .= sprintf(
                    $this->options(), 
                    $key, 
                    $this->get_selected_item($key), 
                    $this->tranlate_item($value)
                );
            }
        }

        return $options;
    }

    /**
     * Undocumented function
     *
     * @param [type] $value
     * @return string
     */
    private function tranlate_item($value)
    {
        if ($value === 0) {
            return __d('upload', 'non');
        } else {
            return __d('upload', 'oui');
        }
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    private function get_items()
    {
        return $this->items;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    private function get_selected_item($key)
    {
        return strcmp($this->selected, $key) ? '' : ' selected="selected"';
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    private function get_multipe_array()
    {
        return $this->multiple == 1 ? '[]' : '';
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    private function get_multiple()
    {
        return $this->multiple == 1 ? ' multiple' : '';
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    private function get_onChange()
    {
        return empty($this->onChange) ? '' : ' onchange="' . $this->onChange . '"';
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    private function get_stub($type)
    {
        return $this->stub[$type];
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    private function start_select()
    {
        $this->get_stub('select_start');
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    private function end_select()
    {
        return $this->get_stub('select_end');
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    private function options()
    {
        return $this->get_stub('option');
    }

}
