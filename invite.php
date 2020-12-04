<?php

// $ch = curl_init(); // はじめ

class Slack
{
    public function __construct()
    {
        $this->ch = curl_init();
        $this->delimiter = ',';
    }

    public function get_all_member_list()
    {
        $url = "https://slack.com/api/users.list";

        $params = [
            'token' => getenv('SLACK_TOKEN'),
        ];

        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $params);
        $all_member_list = curl_exec($this->ch);

        return json_decode($all_member_list);
    }

    public function invite_to_channel($all_member_list)
    {
        $all_members = array_column($all_member_list->members, 'id');
        $deleted_members = explode($this->delimiter, getenv('DELETED_MEMBER_IDS'));
        $_invited_members = array_diff($all_members, $deleted_members);

        $invited_members = implode($this->delimiter, $_invited_members);

        $url = "https://slack.com/api/conversations.invite";
        $params = [
            'token' => getenv('SLACK_TOKEN'),
            'channel' => getenv('CHANNEL'),
            'users' => $invited_members,
        ];
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $params);
        curl_exec($this->ch);
    }
}

$slack = new Slack();
$all_member_list = $slack->get_all_member_list();
$slack->invite_to_channel($all_member_list);
