<?php


namespace Modules\Upload\Support;

/**
 * Undocumented class
 */
class UploadHtmlCheckBox
{

    /**
     * Undocumented function
     */
    private string $name;
    
    /**
     * Undocumented function
     */
    private bool $value;
    
    /**
     * Undocumented function
     */
    private string $current; 
    
    /**
     * Undocumented function
     */
    private string $text;

    /**
     * Undocumented variable
     *
     * @var string
     */
    private $stub_input = '<input type="checkbox" name="%s" value="%s"%s />%s';


    /**
     * Undocumented function
     */
    public function __construct($name, $value = 1, $current = '', $text = '')
    {
        $this->name       = $name;
        $this->value      = $value;
        $this->current    = $current;
        $this->text       = $text;
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
        return sprintf(
            $this->get_stub(),
            $this->get_name(),
            $this->get_value(),
            $this->get_current(),
            $this->get_text()
        );
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    private function get_stub()
    {
        return $this->stub_input;
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
     * @return bool
     */
    private function get_value()
    {
        return $this->value ?: 1;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    private function get_current()
    {
        return ($this->current == $this->value ? ' checked="checked"' : '');
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    private function get_text()
    {
        return (empty($this->text)) ? '' :  $this->text;
    }

}
