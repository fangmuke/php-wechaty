<?php
/**
 * Created by PhpStorm.
 * User: peterzhang
 * Date: 2020/7/21
 * Time: 9:40 PM
 */
namespace IO\Github\Wechaty\User;

use IO\Github\Wechaty\Puppet\Schemas\UrlLinkPayload;

class UrlLink {
    public $payload;

    public function __construct() {
        $this->payload = new UrlLinkPayload();
    }
}