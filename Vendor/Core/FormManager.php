<?php
namespace Megaforms\Vendor\Core;

use Megaforms\Vendor\Libs\Model;
use Megaforms\Vendor\Libs\Model\FormObjectStorage;
use SplSubject;

/**
 * Created by PhpStorm.
 * User: max
 * Date: 12.04.16
 * Time: 17:07
 */

defined("MEGAFORMS_BOOTSTRAPPED") or die("I'm only the wp plugin");
final class FormManager extends FormObjectStorage
{

    /**
     * Receive update from subject
     * @link http://php.net/manual/en/splobserver.update.php
     * @param SplSubject $subject <p>
     * The <b>SplSubject</b> notifying the observer of an update.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function update(SplSubject $subject)
    {
        // TODO: Implement update() method.
    }
}