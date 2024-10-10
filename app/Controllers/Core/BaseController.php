<?php


namespace App\Controllers\Core;

use Npds\View\View;
use Npds\Core\Controller;
use Npds\Events\Manager as Events;
use Modules\Blocks\Support\Facades\Block;


/**
 * Simple themed controller showing the typical usage of the Flight Control method.
 */
class BaseController extends Controller
{

    /**
     * [$layout description]
     *
     * @var [type]
     */
    protected $layout = 'themed';

    /**
     * [$events description]
     *
     * @var [type]
     */
    protected $events = null;

    /**
     * [$npds description]
     *
     * @var [type]
     */
    protected $pdst;

    /**
     * [description]
     */
    protected $pdst_default = 1;


    /**
     * Call the parent construct
     */
    public function __construct()
    {
        parent::__construct();

        $this->events = Events::getInstance();

        // Setup the Data Entries.
        $this->data = array(
            'headerMetaData' => array(),
            'headerCSSheets' => array(),
            'headerJScripts' => array(),
            'footerJScripts' => array(),
        );
    }

    /**
     * Before 'Flight' action
     * @return bool
     */
    protected function before()
    {
        $data =& $this->data;

        $params = array(
            'controller' => $this->className,
            'method'     => $this->method,
            'params'     => $this->params,
            'context'    => $this->module ? $this->module : 'App'
        );

        // Broadcast the Event to all its Listeners; if they return a valid array, merge it to Data.
        $this->events->trigger('App.Core.BaseController.before', $params, function ($result) use (&$data) {
            if (! is_array($result)) {
                return;
            }

            foreach ($result as $key => $value) {
                switch ($key) {
                    case 'headerMetaData':
                    case 'headerCSSheets':
                    case 'headerJScripts':
                    case 'footerJScripts':
                        if (! is_array($value)) {
                            continue 2;
                        }

                        break;
                    default:
                        continue 2;
                }

                if (! empty($value)) {
                    $data[$key] = array_merge($data[$key], $value);
                }
            }
        });

        // Leave to parent's method the Flight decisions.
        return parent::before();
    }

    /**
     * After 'flight' action
     *
     * @param mixed $result
     * @return bool
     */
    protected function after($result)
    {
        if (($result === false) || ! $this->autoRender) {
            // Errors in called Method or isn't wanted the auto-Rendering; stop the Flight.
            return false;
        }

        if (!is_null($this->pdst)) {
            $pdst = $this->pdst;
        } else {
            $pdst = $this->pdst_default;
        }

        $pdst = Block::block_pdst($pdst);

        View::share('pdst', $pdst);

        if (($result === true) || is_null($result)) {
            $result = View::make($this->method(), $this->data());
        }

        if ($result instanceof View) {
            if ($this->useLayout) {
                View::layout($this->layout(), $this->data())->withContent($result)->display();
            } else {
                $result->display();
            }

            // Stop the Flight.
            return false;
        }

        // Leave to parent's method the Flight decisions.
        return parent::after($result);
    }
}
