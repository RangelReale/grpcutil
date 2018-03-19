<?php

namespace RangelReale\GRPCUtil;

class Exception extends \Exception
{
    /**
     * @var \Google\Rpc\Status  The error status
     */
    public $status;
    
    /**
     * @param \Google\Rpc\Status $status
     */
    public function __construct($status) {
        parent::__construct($status->getMessage(), $status->getCode());
        $this->status = $status;
    }
}
