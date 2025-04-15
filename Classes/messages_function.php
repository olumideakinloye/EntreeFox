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
    public function set_seen_msg($userid, $otherid){
        $DB = new Database();
        $sql = "update messages set seen = 1 where receiver = '$userid' && sender = '$otherid' || sender = '$userid' && receiver = '$otherid'";
        $DB->save($sql);
    }
    public function find_chats($id, $otherids)
    {
        $DB = new Database();
        $chats = [];
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
    public function send_message($receiver, $msg, $sender){
        $img = new Image;
        $msgid = $img->generate_file_name(60);
        $msg = addslashes(ucfirst($msg));
        $query = "insert into messages (msgid, sender, receiver, message) values ('$msgid', '$sender', '$receiver', '$msg')";
        $DB = new Database();
        $DB->save($query);
    }
}
