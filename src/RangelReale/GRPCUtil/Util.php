<?php

namespace RangelReale\GRPCUtil;

class Util 
{
    /**
     * Returns an \Google\Rpc\Status object from an gRPC function call status value,
     * with details decoded to Any if available.
     * 
     * @param object $status Return from gRPC function call.
     * @return \Google\Rpc\Status
     */
    public static function parseStatus($status) {
        $rpcStatus = new \Google\Rpc\Status();
        if (isset($status->metadata['grpc-status-details-bin'])) {
            if (is_array($status->metadata['grpc-status-details-bin']) && count($status->metadata['grpc-status-details-bin']) > 0) {
                $rpcStatus->mergeFromString($status->metadata['grpc-status-details-bin'][0]);
                return $rpcStatus;
            }
        }
        $rpcStatus->setCode($status->code);
        $rpcStatus->setMessage($status->details);
        return $rpcStatus;
    }

    /**
     * Unpacks the list of details messages from an RPC status.
     * 
     * @param \Google\Rpc\Status $rpcStatus
     * @return array Array of unpacked detail messages
     */
    public static function unpackStatusDetails($rpcStatus) {
        $ret = [];
        foreach ($rpcStatus->getDetails as $det) {
            $ret[] = $det->unpack();
        }
        return $ret;
    }
    
    /**
     * Throws an exception on gRPC error.
     * 
     * @param object $status
     * @throws Exception
     */
    public static function checkStatus($status) {
        $rpcStatus = self::parseStatus($status);
        if ($rpcStatus->getCode() != \Grpc\STATUS_OK) {
            throw new Exception($rpcStatus);
        }
    }
}
