<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Serializer
 */

namespace Zend\Serializer\Adapter;

use Zend\Serializer\Exception;
use Zend\Stdlib\ErrorHandler;

/**
 * @category   Zend
 * @package    Zend_Serializer
 * @subpackage Adapter
 */
class PhpCode extends AbstractAdapter
{
    /**
     * Serialize PHP using var_export
     *
     * @param  mixed $value
     * @return string
     */
    public function serialize($value)
    {
        return var_export($value, true);
    }

    /**
     * Deserialize PHP string
     *
     * Warning: this uses eval(), and should likely be avoided.
     *
     * @param  string $code
     * @return mixed
     * @throws Exception\RuntimeException on eval error
     */
    public function unserialize($code)
    {
        ErrorHandler::start(E_ALL);
        $ret  = null;
        $eval = @eval('$ret=' . $code . ';');
        $err  = ErrorHandler::stop();

        if ($eval === false || $err) {

            $msg = 'eval failed';

            // Error handler doesn't catch syntax errors
            if ($eval === false) {
                $lastErr = error_get_last();
                $msg    .= ': ' . $lastErr['message'];
            }

            throw new Exception\RuntimeException($msg, 0, $err);
        }

        return $ret;
    }
}