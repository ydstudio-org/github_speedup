<?php

require_once('../database_config.php');

class Node
{
    var $nodeID,$appID,$Akey,$msg,$statusCode,$authKey,$nodeIP;
    var $data = [];
    var $JsonMsg = [
        'msg'=>&$msg,
        'status'=>&$statusCode,
        'data'=>&$data
        ];
    var $db_CON = new mysqli(HOST,USER,PASSWORD,TABLE);
    function __construct($nodeID,$authKey,$nodeIP)
    {
        $this->nodeID = $nodeID;
        $this->nodeIP = $nodeIP;
        $this->authKey = $authKey;
        if (strlen($this->nodeID) != 36)
        {
            throw new Exception("at " . __FILE__ . " Line:" . __LINE__ . " Not define NodeID!!");
        }
    }
    function RegisterNode()
    {
        if (strlen($this->nodeID) != 36||$this->authKey != NODE_REG_KEY)
        {
            $this->msg = "NodeID Or authKey Error";
            $this->statusCode = 403;
            return json_encode($this->JsonMsg);
        }
        else {
            $sql = "SELECT * FROM `nodeData` WHERE `NodeID` LIKE '$this->NodeID'";
            if ($this->db_CON->query($sql))
            {
                $sql = "INSERT INTO `nodeData` (`id`, `NodeID`, `IsBindIP`, `NodeIPAddr`, `status`) VALUES (NULL, '$this->NodeID', '1', '$this->NodeIP', '1')";
                if ($this->db_CON->query($sql))
                {
                    $this->msg = "success";
                    $this->statusCode = 200;
                    return json_encode($this->JsonMsg);
                }
                else
                {
                    $this->msg = "failed";
                    $this->statusCode = 409;
                    return json_encode($this->JsonMsg);
                }
            }
        }
    }
    
    
}