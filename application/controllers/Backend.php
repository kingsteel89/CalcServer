<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backend extends CI_Controller {

	function __construct() {
        parent::__construct();
        $this->load->model("General");
    }

    function newlogin () {
        $cond_user['openid'] = $this->input->get('openid');

        $user = $this->General->get_row('tb_users', $cond_user);

        $cond_user['nickname'] = $this->input->get('nickname');
        $cond_user['headurl'] = $this->input->get('headurl');
        $cond_user['regdate'] = date('Y-m-d H:i:s');

        if ($user) {
            $result = $this->General->update('tb_users', $cond_user, array('id' => $user->id));
        } else {
            $cond_user['restfunc'] = 3;
            $result = $this->General->insert_new('tb_users', $cond_user);
        }

        $new_user = $this->General->get_row('tb_users', array('openid' => $cond_user['openid']));

        echo json_encode(array('ret' => 10000, 'msg' => 'Success', 'result' => $new_user));
    }

    function relogin () {
        $cond_user['openid'] = $this->input->get('openid');
        $user = $this->General->get_row('tb_users', $cond_user);

        if ($user) {
            echo json_encode(array('ret' => 10000, 'msg' => 'Success', 'result' => $user));
        } else {
            echo json_encode(array('ret' => 10001, 'msg' => 'Failed Login'));
        }
    }

    function update_user () {
        $cond_user['id'] = $this->input->get('id');
        $cond_user['classname'] = $this->input->get('classname');

        $cond_user['classname'] = ucfirst($cond_user['classname']);

        $user = $this->General->get_rows('tb_users', array('classname' => $cond_user['classname']));
        if ($user) {
            echo json_encode(array('ret' => 10001, 'msg' => 'This name is wrong.'));
        } else {
            $result = '<?php
defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');

class ' . $cond_user['classname'] . ' extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("General");
    }

}';
            file_put_contents(APPPATH . 'controllers/' . $cond_user['classname'] . '.php', $result);

            $user = $this->General->update('tb_users', $cond_user, array('id' => $cond_user['id']));
            echo json_encode(array('ret' => 10000, 'msg' => 'Success', 'result' => $cond_user['classname']));
        }
    }

    function getfunctions () {
        $cond_funcs['id_user'] = $this->input->get('id_user');
        $funcs = $this->General->get_rows('tb_functions', $cond_funcs);

        if (count($funcs) > 0) {
            for ($i=0; $i < sizeof($funcs); $i++) {
                $cond_para['id_func'] = $funcs[$i]->id;
                $params = $this->General->get_rows('tb_params', $cond_para);
                $cnt_para[$i] = sizeof($params);
            }
            echo json_encode(array('ret' => 10000, 'msg' => 'Success', 'result' => $funcs, 'param' => $cnt_para));
        } else {
            echo json_encode(array('ret' => 10001, 'msg' => 'Empty Function'));
        }
    }

    function getFunctionDetail() {
        $cond_funcs['id'] = $this->input->get('id_function');
        $res_func = $this->General->get_row('tb_functions', $cond_funcs);
        if ($res_func == null) {
            echo json_encode(array('ret' => 10001, 'msg' => 'Empty Function'));
            return;
        }
        $result['function'] = $res_func;

        $cond_param['id_func'] = $res_func->id;
        $res_params = $this->General->get_rows('tb_params', $cond_param);
        $result['params'] = $res_params;

        echo json_encode(array('ret' => 10000, 'msg' => 'Success', 'result' => $result));
    }

    function getParams () {
        $ids = $this->input->get('id_func');
        $id_funcs = explode(',', $ids);
        $cnt_param = 0;
        for ($i=0; $i < sizeof($id_funcs); $i++) { 
            $cond_params['id_func'] = $id_funcs[$i];
            $params = $this->General->get_rows('tb_params', $cond_params);
            if ($i == 0) {
                $cnt_param = sizeof($params);
            } else {
                $cnt_pair = sizeof($params);
                if ($cnt_pair != $cnt_param) {
                    echo json_encode(array('ret' => 10001, 'msg' => 'Failed Selection.'));
                    return;
                }
            }
        }        

        echo json_encode(array('ret' => 10000, 'msg' => 'Success', 'result' => $params));
    }

    function saveFormula () {
        $cond_user['id'] = $this->input->get('id');
        $params = $this->input->get('params');
        $title = $this->input->get('title');
        $body = $this->input->get('body');
        $description = $this->input->get('description');
        $algorithm = $this->input->get('algorithm');

        $user = $this->General->get_row('tb_users', $cond_user);
        if ($user->restfunc == 0) {
            echo json_encode(array('ret' => 10001, 'msg' => 'The count of your rest functions is zero. Please get some functions.'));
        } else {
            $cond_funcs['title'] = $title;
            $cond_funcs['id_user'] = $user->id;
            $func = $this->General->get_row('tb_functions', $cond_funcs);
            if ($func) {
                echo json_encode(array('ret' => 10002, 'msg' => 'The name of your function is existed. Please change it.'));
                return;
            }

            // Add Function to Class
            $data = file_get_contents(APPPATH . 'controllers/' . $user->classname . '.php');
            $arr = explode("\n", $data);
            array_pop($arr);
            $arr[] = $body;
            $arr[] = '}';
            $result = implode("\n", $arr);
            file_put_contents(APPPATH . 'controllers/' . $user->classname . '.php', $result);

            // Update User Table
            $cond_user['function'] = $user->function + 1;
            $cond_user['restfunc'] = $user->restfunc - 1;
            $cond_user['regdate'] = date('Y-m-d H:i:s');
            $this->General->update('tb_users', $cond_user, array('id' => $user->id));

            // Add New Funtion
            $cond_funcs['name'] = $description;
            $cond_funcs['content'] = $algorithm;
            $cond_funcs['regdate'] = date('Y-m-d H:i:s');
            $func_id = $this->General->insert_new('tb_functions', $cond_funcs);

            // Add New Parameters
            $param_item = explode(":", $params);
            for ($i=0; $i < sizeof($param_item); $i++) { 
                $param_info = explode(",", $param_item[$i]);

                $cond_params['name'] = $param_info[0];
                $cond_params['description'] = $param_info[1];
                $cond_params['id_func'] = $func_id;
                $cond_params['regdate'] = date('Y-m-d H:i:s');
                $this->General->insert_new('tb_params', $cond_params);
            }

            echo json_encode(array('ret' => 10000, 'msg' => 'Success', 'result' => $title));
        }
    }

    function testFormula () {
        $cond_user['id'] = $this->input->get('id');
        $body = $this->input->get('body');

        // Create Test file

        $result = '<?php
defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');

class Test_' . $cond_user['id'] . ' extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("General");
    }

}';
        file_put_contents(APPPATH . 'controllers/Test_' . $cond_user['id'] . '.php', $result);

        // Input Test Algorithm
        $data = file_get_contents(APPPATH . 'controllers/Test_' . $cond_user['id'] . '.php');
        $arr = explode("\n", $data);
        array_pop($arr);
        $arr[] = $body;
        $arr[] = '}';
        $result = implode("\n", $arr);
        file_put_contents(APPPATH . 'controllers/Test_' . $cond_user['id'] . '.php', $result);

        echo json_encode(array('ret' => 10000, 'msg' => 'Success', 'result' => 'Test_' . $cond_user['id']));
    }

    function addcomment () {
        $cond_comment['id_user'] = $this->input->get('id_user');
        $cond_comment['comment'] = $this->input->get('comment');

        $cond_comment['regdate'] = date('Y-m-d H:i:s');

        $this->General->insert_new('tb_comments', $cond_comment);

        echo json_encode(array('ret' => 10000, 'msg' => 'Success', 'result' => "Add comment in server."));
    }

    function gethistory () {
        $cond = $this->input->get('id_user');
        $result_comment = $this->General->get_all('tb_comments', 'no');

        $count = 0;
        for ($i=0; $i < sizeof($result_comment); $i++) { 
            $history = $result_comment[$i];

            if ($history->payed != '' && $history->id_user != $cond) {
                continue;
            }

            $result[$count]['id'] = $history->id;
            $result[$count]['comment'] = $history->comment;
            $result[$count]['payed'] = $history->payed;
            $result[$count]['regdate'] = $history->regdate;
            $result[$count]['other'] = $history->other;

            $cond_user['id'] = $history->id_user;
            $user = $this->General->get_row('tb_users', $cond_user);

            $result[$count]['imgurl'] = $user->headurl;
            $result[$count]['name'] = $user->nickname;

            $count++;
        }

        echo json_encode(array('ret' => 10000, 'msg' => 'Success', 'result' => $result));
    }

    function savepayment () {
        $cond_user['id'] = $this->input->get('id_user');
        $user = $this->General->get_row('tb_users', $cond_user);

        $payed = $this->input->get('payed');
        $cond_user['payed'] = $payed + $user->payed + 0;
        switch ($payed) {
            case '99':
                $cond_user['restfunc'] = $user->restfunc + 1;
                break;
            case '2495':
                $cond_user['restfunc'] = $user->restfunc + 30;
            break;
            case '5990':
                $cond_user['restfunc'] = $user->restfunc + 80;
            break;
            case '8950':
                $cond_user['restfunc'] = $user->restfunc + 150;
            break;            
            case '2000':
                $cond_report['id_user'] = $user->id;
                $cond_report['name'] = $this->input->get('name');
                $cond_report['description'] = $this->input->get('description');
                $cond_report['content'] = $this->input->get('content');
                $cond_report['other'] = '0';
                $cond_report['regdate'] = date('Y-m-d H:i:s');

                $this->General->insert_new('tb_reports', $cond_report);
                break;
            default:
                $cond_project['id_user'] = $this->input->get('id_user');
                $cond_project['name'] = $this->input->get('name');
                $cond_project['regdate'] = date('Y-m-d H:i:s');

                $cond_re['id_project'] = $this->General->insert_new('tb_project', $cond_project);
                $str_functions = $this->input->get('id_function');
                $id_funcs = explode(',', $str_functions);
                for ($i = 0; $i < sizeof($id_funcs); $i++) {
                    $cond_re['id_function'] = $id_funcs[$i];
                    $cond_re['regdate'] = date('Y-m-d H:i:s');
                    $this->General->insert_new('re_project_function', $cond_re);
                }
                break;
        }
        $cond_user['regdate'] = date('Y-m-d H:i:s');

        if ($this->General->update('tb_users', $cond_user, array('id' => $user->id))) {
            $user = $this->General->get_row('tb_users', $cond_user);
        }

        $cond_comment['id_user'] = $user->id;
        $cond_comment['payed'] = $payed;
        $cond_comment['regdate'] = date('Y-m-d H:i:s');

        $this->General->insert_new('tb_comments', $cond_comment);

        echo json_encode(array('ret' => 10000, 'msg' => 'Success', 'result' => $user));
    }

    function getprojects () {
        $cond_project['id_user'] = $this->input->get('id_user');
        $projects = $this->General->get_rows('tb_project', $cond_project);
        if (count($projects) > 0) {
            echo json_encode(array('ret' => 10000, 'msg' => 'Success', 'result' => $projects));
        } else {
            echo json_encode(array('ret' => 10001, 'msg' => 'Empty Function'));
        }
    }

    function getprojectdetail () { 
        $cond_re['id_project'] = $this->input->get('id_project');
        $re_funcs = $this->General->get_rows('re_project_function', $cond_re);
        for ($i = 0; $i < sizeof($re_funcs); $i++) {
            $cond_funcs['id'] = $re_funcs[$i]->id_function;
            $result['function'][$i] = $this->General->get_row('tb_functions', $cond_funcs);
        }
        $cond_params['id_func'] = $re_funcs[0]->id_function;
        $result['param'] = $this->General->get_rows('tb_params', $cond_params);
        echo json_encode(array('ret' => 10000, 'msg' => 'Success', 'result' => $result));
    }

}
