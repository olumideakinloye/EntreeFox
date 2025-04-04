<?php
class Message extends User
{
    public function get_chats($id)
    {
        $DB = new Database();
        $query = "select * from messages where receiver = '$id' || sender = '$id' order by id desc";
        $result = $DB->read($query);
        $new_ids = $chats = $ids = [];
        
        // $ids = array_column($result, 'sender');
        if ($result) {
            foreach ($result as $row) {
                if ($row['sender'] !== $id) {
                    $ids[] = $row['sender'];
                } else {
                    $ids[] = $row['receiver'];
                }
            }
            foreach ($ids as $ID) {
                if ($ID !== $id && !in_array($ID, $new_ids)) {
                    $new_ids[] = $ID;
                }
            }
            foreach ($new_ids as $IDS) {
                $sql = "select * from messages where receiver = $IDS && sender = '$id' || receiver = '$id' && sender = $IDS order by id desc limit 1";
                $result = $DB->read($sql);
                if (!in_array($result, $chats)) {
                    $chats[] = $result;
                }
            }
            return $chats;
        } else {
            return "No chats";
        }
    }
    public function find_chats($id, $otherids)
    {
        $DB = new Database();
        // $userIdsList = implode(',', $otherids);
        // $query = "select * from messages where receiver = '$id' && sender in ($userIdsList) || sender = '$id' && receiver in ($userIdsList) order by id desc";
        // $result = $DB->read($query);
        // $ids = array_column($result, 'sender');
        // $new_ids = [];
        $chats = [];
        // return $chats; 
        foreach ($otherids as $other_user) {
            $query = "select * from messages where receiver = '$id' && sender = '$other_user' || sender = '$id' && receiver = '$other_user' order by id desc limit 1";
            $result = $DB->read($query);
            if ($result) {
                $chats[] = $result;
            } else {
                $MSG = new Message();
                $chats[] = $MSG->get_data($other_user);
            }
        }
        return $chats;
    }
    public function unseen($id, $sender)
    {
        $unseen = 0;
        $query = "select * from messages where receiver = '$id' && sender = '$sender' && seen = 0";
        $DB = new Database();
        $result = $DB->read($query);
        if ($result) {
            foreach ($result as $row) {
                $unseen += 1;
            }
        }
        return $unseen;
    }
    public function get_messages($userid, $otherid)
    {
        $query = "select * from messages where receiver = '$userid' && sender = '$otherid' || receiver = '$otherid' && sender = '$userid' order by id asc";
        $DB = new Database();
        $result = $DB->read($query);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }
}
