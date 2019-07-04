<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class General extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //get rows from tables
    function get_rows($tb_name, $cond) {
        $query = $this->db->get_where($tb_name, $cond);
        return $query->result();
    }

    //get one row from table
    function get_row($tb_name, $cond) {
        $query = $this->db->get_where($tb_name, $cond);
        return $query->row();
    }

    //get all rows
    function get_all($tb_name, $order) {
        if ($order != "no") {
            $this->db->order_by($order, 'desc');
        }
        $query = $this->db->get($tb_name);
        return $query->result();
    }

    function get_all_reverse($tb_name, $order) {
        if ($order != "no") {
            $this->db->order_by($order, 'asc');
        }
        $query = $this->db->get($tb_name);
        return $query->result();
    }

    //insert new datas on table.
    function insert_new($tb_name, $data) {
        $this->db->insert($tb_name, $data);
        return $this->db->insert_id();
    }

    //update row
    function update($tb_name, $data, $cond) {
        $result = $this->db->update($tb_name, $data, $cond);
        return $result;
    }

    function delete($tb_name, $cond) {
        $result = $this->db->delete($tb_name, $cond);
        return $result;
    }

    function setInterval($f, $milliseconds) {
        $seconds = (int) $milliseconds / 1000;
        while (true) {
            $f();
            sleep($seconds);
        }
    }

    function send_ios_notification($deviceToken, $message) {
        $passphrase = '123456';
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', 'E:/Web/www/Chatting/apns.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        // Open a connection to the APNS server
        $fp = stream_socket_client(
                'ssl://gateway.sandbox.push.apple.com:2195', $err, // For development
                // 'ssl://gateway.push.apple.com:2195', $err, // for production
                $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        //echo 'Connected to APNS' . PHP_EOL;
        // Create the payload body
        $body['aps'] = array(
            'alert' => trim($message),
            'sound' => 'default'
        );
        // Encode the payload as JSON
        $payload = json_encode($body);
        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', trim($deviceToken)) . pack('n', strlen($payload)) . $payload;
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));
        if (!$result) {
            //echo 'Message not delivered' . PHP_EOL;
        } else {
            //echo 'Message successfully delivered' . PHP_EOL;
            return $result;
        }
        // Close the connection to the server
        fclose($fp);
    }

    //
    function send_socket($address, $port, $message) {
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname('tcp'));
        socket_connect($socket, $address, $port);
        $status = socket_write($socket, $message, strlen($message));
        if ($status !== FALSE) {
            //echo "success";
        } else {
            echo "Failed";
        }
        socket_close($socket);
    }

    function pagination($datas, $link) {
        $pages = $datas['pages'];
        $groups = $datas['groups'];

        $index = $pages['index'];
        if ($index == 1) {
            $first_class = "page-item active";
        } else {
            $first_class = "page-item";
        }

        $pagination = "";
        $pagination .= '<ul class="pagination pagination_padding_general_components">';


        if ($groups['first'] > 1) {
            $pagination .= '<li class="' . $first_class . '"><a href="' . $link . "1" . '">1</a></li>';
            $pagination .= '<li class="page-item"><a href="#">...</a></li>';
        }
        if ($groups['first'] > 2) {
            $prev = $groups['first'] - 1;
            if ($prev == $index) {
                $prev_class = "page-item active";
            } else {
                $prev_class = "page-item";
            }
            $pagination .= '<li class="' . $prev_class . '"><a href="' . $link . $prev . '">' . $prev . '</a></li>';
        }
        for ($i = $groups['first']; $i <= $groups['last']; $i++) {
            if ($index == $i) {
                $i_class = "page-item active";
            } else {
                $i_class = "page-item";
            }
            $pagination .= '<li class="' . $i_class . '"><a href="' . $link . $i . '">' . $i . '</a></li>';
        }

        if ($groups['last'] < $pages['total'] - 1) {
            $next = $groups['last'] + 1;
            if ($index == $next) {
                $next_class = "page-item active";
            } else {
                $next_class = "page-item";
            }
            $pagination .= '<li class="' . $next_class . '"><a href="' . $link . $next . '">' . $next . '</a></li>';
        }

        if ($groups['last'] < $pages['total']) {
            if ($index == $pages['total']) {
                $last_class = "page-item active";
            } else {
                $last_class = "page-item";
            }
            $pagination .= '<li class="page-item"><a href="#">...</a></li>';
            $pagination .= '<li class="' . $last_class . '"><a href="' . $link . $pages['total'] . '">' . $pages['total'] . '</a></li>';
        }
        $pagination .= '</ul>';
        return $pagination;
    }

    function get_paginationinfo($rows, $page_number) {
        $page_rows = PAGE_ROWS;
        $group_rows = PAGE_GROUP;

        if ($rows % $page_rows == 0) {
            $pages['total'] = (int) ($rows / $page_rows);
        } else {
            $pages['total'] = (int) ($rows / $page_rows) + 1;
        }

        //groups
        if ($pages['total'] % $group_rows == 0) {
            $groups['total'] = (int) ($pages['total'] / $group_rows);
        } else {
            $groups['total'] = (int) ($pages['total'] / $group_rows) + 1;
        }

        if ($page_number % $group_rows == 0) {
            $groups['index'] = (int) ($page_number / $group_rows);
        } else {
            $groups['index'] = (int) ($page_number / $group_rows) + 1;
        }

        if ($groups['total'] == 1) {
            $groups['first'] = 1;
            $groups['last'] = $pages['total'];
        } else {
            if ($groups['index'] == $groups['total']) {
                $groups['first'] = $pages['total'] - $group_rows + 1;
                $groups['last'] = $pages['total'];
            } else {
                $groups['first'] = ($groups['index'] - 1) * $group_rows + 1;
                $groups['last'] = ($groups['index']) * $group_rows;
            }
        }


        $pages['index'] = $page_number;
        if ($page_number == $pages['total']) {
            $pages['number'] = $rows - ($page_number - 1) * $page_rows;
        } else {
            $pages['number'] = $page_rows;
        }
        $pages['first'] = ($page_number - 1) * $page_rows + 1;
        $pages['last'] = ($page_number - 1) * $page_rows + $pages['number'];
        $data['pages'] = $pages;
        $data['groups'] = $groups;
        return $data;
    }

    function get_menus() {
        $menu_str = file_get_contents('json/menu.json');
        $json = json_decode($menu_str, true);
        return $json;
    }

    //upload files
    function upload($name, $path) {
        // ===== Image file upload
        if (!isset($_FILES[$name]) || empty($_FILES[$name]['tmp_name'])) {
            $data['filename'] = "false";
        } else {
            $config = array();
            $config['upload_path'] = $path;
            $config['file_name'] = $name . '_' . time();
            $config['overwrite'] = FALSE;
            $config['allowed_types'] = 'png|gif|jpg|jpeg|bmp';
            $config['max_size'] = PICTURE_FILE_MAX_SIZE;
            $config['encrypt_name'] = FALSE;
            // Check directory
            if (!file_exists($config['upload_path']) || !is_dir($config['upload_path'])) {
                mkdir($config['upload_path']);
                chmod($config['upload_path'], DIR_WRITE_MODE);
            }

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload($name)) {
                return "error";
            }
            $upload_result = $this->upload->data();
            $data['width'] = $upload_result['image_width'];
            $data['height'] = $upload_result['image_height'];
            $data['filename'] = $upload_result['file_name'];
            $data['regdate'] = date(DATETIME_FORMAT);
        }
        return $data['filename'];
    }

}

?>
