<?php

class AdminScrud {

    private $da;
    private $savant;

    public function __construct($da) {
        $this->da = $da;
        $this->savant = new Savant3();
        $this->savant->setPath('template', dirname(__FILE__));
    }

    public function config() {
        $auth = Auth::singleton();
        $auth->checkConfigPermission();
        global $config_database;
        $dao = new Dao($this->da);

        $this->savant->fields = $this->da->listFields($_GET['table']);
        if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'] . '/' . $_GET['table'] . '.php')) {
            $content = str_replace("<?php exit; ?>\n", "", file_get_contents(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'] . '/' . $_GET['table'] . '.php'));
            if (empty($content)) {
                $content = "{}";
            }
        } else {
            $content = "{}";
        }

        $this->savant->tables = $dao->listTables();

        $this->savant->crudConfigTable = $content;
        $this->savant->fieldConfig = array();
        foreach ($this->savant->fields as $f) {
            if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'] . '/' . $f['Field'] . '.php')) {
                $content = str_replace("<?php exit; ?>\n", "", file_get_contents(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'] . '/' . $f['Field'] . '.php'));
                if (!empty($content)) {
                    $this->savant->fieldConfig[$f['Field']] = $content;
                }
            }
        }


        return $this->savant->getOutput('config.tpl.php');
    }

    public function browse() {
        global $config_database;

        if (!file_exists(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'] . '.php')) {
            exit;
        }

        $content = str_replace("<?php exit; ?>\n", "", file_get_contents(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'] . '.php'));
        $conf = unserialize($content);
        
        $hook = Hook::singleton();

        $hook->addFunction('SCRUD_INIT', 'f_scrud_init');

        $scurd = new Scurd($_GET['table'], $conf, $this->da);
        $this->savant->conf = $conf;
        $this->savant->content = $scurd->process();

        return $this->savant->getOutput('browse.tpl.php');
    }

    public function exportCsv() {
        global $config_database;

        if (!file_exists(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'] . '.php')) {
            exit;
        }

        $content = str_replace("<?php exit; ?>\n", "", file_get_contents(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'] . '.php'));
        $conf = unserialize($content);
        $scurd = new Scurd($_GET['table'], $conf, $this->da);
        echo $scurd->process();
    }

    public function delFile() {
        global $config_database;

        if (!file_exists(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'] . '.php')) {
            exit;
        }
        $content = str_replace("<?php exit; ?>\n", "", file_get_contents(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'] . '.php'));
        $conf = unserialize($content);
        $scurd = new Scurd($_GET['table'], $conf, $this->da);

        $data = $scurd->process();
        die($data);
    }

    public function removeConfig() {
        $auth = Auth::singleton();
        $auth->checkConfigPermission();
        global $config_database;

        if (isset($_GET['table']) &&
                trim($_GET['table']) != '') {
            if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'])) {
                removeDir(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table']);
            }
            if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'] . '.php')) {
                @unlink(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_GET['table'] . '.php');
            }
        }
    }

    public function getFields() {
        $rs = $this->da->listFields($_GET['table']);
        $fields = array();
        foreach ($rs as $v) {
            $fields[] = $v['Field'];
        }
        header('Content-Type: application/json');
        echo json_encode($fields);
    }

    public function getOptions() {
        $var = array();
        if (isset($_POST['config'])) {
            $config = $_POST['config'];
            $crudDao = new GenericDao($config['table'], $this->da);


            if (isset($config['key']) &&
                    trim($config['key']) != '' &&
                    isset($config['value']) &&
                    trim($config['value']) != '') {
                $params = array();
                $params['fields'] = array($config['key'], $config['value']);
                $rs = $crudDao->find($params);
                if (!empty($rs)) {
                    foreach ($rs as $v) {
                        $var[$v[$config['key']]] = $v[$config['value']];
                    }
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($var);
    }

    public function saveConfig() {
        $auth = Auth::singleton();
        $auth->checkConfigPermission();
        global $config_database;

        if (isset($_POST['table'])) {

            if (!is_dir(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"])) {
                $oldumask = umask(0);
                mkdir(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"], 0777);
                umask($oldumask);
            }

            if (!is_dir(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_POST['table'])) {
                $oldumask = umask(0);
                mkdir(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_POST['table'], 0777);
                umask($oldumask);
            }
            $fields = $this->da->listFields($_POST['table']);
            if (isset($_POST['config'])) {
                if (isset($_POST['config'])) {
                    if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_POST['table'] . '/' . $_POST['table'] . '.php')) {
                        @unlink(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_POST['table'] . '/' . $_POST['table'] . '.php');
                    }
                    $oldumask = umask(0);
                    file_put_contents(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_POST['table'] . '/' . $_POST['table'] . '.php', "<?php exit; ?>\n" . json_encode($_POST['config']));
                    umask($oldumask);
                }
            }

            if (isset($_POST['scrud'])) {
                $crud = $_POST['scrud'];
                foreach ($fields as $field) {
                    //$dao->remove(array('crud_key = ?', array(sha1($_POST['table'] . $field['Field']))));
                    if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_POST['table'] . '/' . $field['Field'] . '.php')) {
                        @unlink(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_POST['table'] . '/' . $field['Field'] . '.php');
                    }
                }
                foreach ($crud as $f => $v) {
                    $oldumask = umask(0);
                    file_put_contents(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_POST['table'] . '/' . $f . '.php', "<?php exit; ?>\n" . json_encode($v));
                    umask($oldumask);
                }
            }

            $conf = array();
            $conf['title'] = $_POST['config']['table']['crudTitle'];
            $conf['limit'] = $_POST['config']['table']['crudRowsPerPage'];
            $conf['frm_type'] = $_POST['config']['frm_type'];
            $join = array();

            if (isset($_POST['config']['join']) && count($_POST['config']['join']) > 0) {
                foreach ($_POST['config']['join'] as $v) {
                    $join[$v['table']] = array($v['type'], $v['table'], $v['currentField'] . ' = ' . $v['targetField']);
                }
            }

            $conf['join'] = $join;

            if (isset($_POST['config']['table']['crudOrderField'])) {
                $conf['order_field'] = $_POST['table'] . '.' . $_POST['config']['table']['crudOrderField'];
            }

            if (isset($_POST['config']['table']['crudOrderType'])) {
                $conf['order_type'] = $_POST['config']['table']['crudOrderType'];
            }
            $validate = array();
            $dataList = array();
            if (isset($_POST['config']['table']['noColumn']) &&
                    (int) $_POST['config']['table']['noColumn'] == 1) {
                $dataList['no'] = array('alias' => __LBL_NO__, 'width' => 40, 'align' => 'center', 'format' => '{no}');
            }
            foreach ($_POST['scrud'] as $field => $v) {
                $elements[$_POST['table'] . '.' . $field] = array();
                $element = array();
                $element[] = $v['type'];
                $attributes = array();
                switch ($v['type']) {
                    case 'checkbox':
                    case 'radio':
                        if (!empty($v['options'])) {
                            $options = array();
                            foreach ($v['options'] as $key => $value) {
                                $options[$v['values'][$key]] = $value;
                            }
                            $element[] = $options;
                        }
                        break;
                    case 'select':
                    	if (!empty($v['multiple'])) {
                    		$attributes['multiple'] = $v['multiple'];
                    	}
                    case 'autocomplete':
                        if ($v['list_choose'] == 'default') {
                            if (!empty($v['options'])) {
                                $options = array();
                                foreach ($v['options'] as $key => $value) {
                                    $options[$key + 1] = $value;
                                }
                                $element[] = $options;
                            }
                        } else if ($v['list_choose'] == 'database') {
                            $opt = array();
                            $opt['option_table'] = $v['db_options']['table'];
                            $opt['option_key'] = $v['db_options']['key'];
                            $opt['option_value'] = $v['db_options']['value'];
                            $element[] = $opt;
                        }
                        
                        break;
                    case 'text':
                    case 'password':
                        $style = "";
                        if (!empty($v['type_options']['size'])) {
                            $style .= "width:" . $v['type_options']['size'] . "px;";
                        }
                        if ($style != "") {
                            $attributes['style'] = $style;
                        }
                        break;
                    case 'textarea':
                        $style = "";
                        if (!empty($v['type_options']['width'])) {
                            $style .= "width:" . $v['type_options']['width'] . "px;";
                        }
                        if (!empty($v['type_options']['height'])) {
                            $style .= "height:" . $v['type_options']['height'] . "px;";
                        }
                        if ($style != "") {
                            $attributes['style'] = $style;
                        }
                        break;
                    case 'image':
                        $attributes['thumbnail'] = "mini";
                        $attributes['width'] = "";
                        $attributes['height'] = "";
                        if (!empty($v['type_options']['thumbnail'])) {
                            $attributes['thumbnail'] = $v['type_options']['thumbnail'];
                        }
                        if (!empty($v['type_options']['img_width'])) {
                        	$attributes['width'] = $v['type_options']['img_width'];
                        }
                        if (!empty($v['type_options']['img_height'])) {
                        	$attributes['height'] = $v['type_options']['img_height'];
                        }
                        break;
                }
                if (!empty($attributes)) {
                    $element[] = $attributes;
                }
                $tmpField = $field;
                if (!empty($v['label'])) {
                    $tmpField = $formElements[$_POST['table'] . '.' . $field]['alias'] = $v['label'];
                    $elements[$_POST['table'] . '.' . $field]['alias'] = $v['label'];
                } else {
                    $formElements[$_POST['table'] . '.' . $field]['alias'] = $v['field'];
                    $elements[$_POST['table'] . '.' . $field]['alias'] = $v['field'];
                }

                $elements[$_POST['table'] . '.' . $field]['element'] = $element;
                $formElements[$_POST['table'] . '.' . $field]['element'] = $element;

                if (!empty($_POST['scrud'][$field]['validation'])) {
                    switch ($_POST['scrud'][$field]['validation']) {
                        case 'notEmpty':
                            $validate[$_POST['table'] . '.' . $field] = array('rule' => $_POST['scrud'][$field]['validation'], 'message' => sprintf(E_VAL_REQUIRED_VALUE, $tmpField));
                            break;
                        default :
                            switch ($_POST['scrud'][$field]['validation']) {
                                case 'alpha':
                                    $tmpMessage = sprintf(E_VAL_ALPHA_CHECK_FAILED, $tmpField);
                                    break;
                                case 'alphaSpace':
                                    $tmpMessage = sprintf(E_VAL_ALPHA_S_CHECK_FAILED, $tmpField);
                                    break;
                                case 'numeric':
                                    $tmpMessage = sprintf(E_VAL_NUM_CHECK_FAILED, $tmpField);
                                    break;
                                case 'alphaNumeric':
                                    $tmpMessage = sprintf(E_VAL_ALNUM_CHECK_FAILED, $tmpField);
                                    break;
                                case 'alphaNumericSpace':
                                    $tmpMessage = sprintf(E_VAL_ALNUM_S_CHECK_FAILED, $tmpField);
                                    break;
                                case 'date':
                                    $tmpMessage = sprintf(E_VAL_DATE_CHECK_FAILED, $tmpField);
                                    break;
                                case 'datetime':
                                	$tmpMessage = sprintf(E_VAL_DATE_TIME_CHECK_FAILED, $tmpField);
                                	break;
                                case 'email':
                                	$tmpMessage = sprintf(E_VAL_EMAIL_CHECK_FAILED, $tmpField);
                                	break;
                                case 'ip':
                                    $tmpMessage = sprintf(E_VAL_IP_CHECK_FAILED, $tmpField);
                                    break;
                                case 'url':
                                    $tmpMessage = sprintf(E_VAL_URL_CHECK_FAILED, $tmpField);
                                    break;
                            }
                            $validate[$_POST['table'] . '.' . $field][] = array('rule' => 'notEmpty', 'message' => $tmpMessage);
                            $validate[$_POST['table'] . '.' . $field][] = array('rule' => $_POST['scrud'][$field]['validation'], 'message' => $tmpMessage);
                            break;
                    }
                }
            }
            if (isset($_POST['config']['column']['actived']) && count($_POST['config']['column']['actived']) > 0) {
                foreach ($_POST['config']['column']['actived'] as $field) {
                    if (isset($_POST['config']['column']['atrr'][$field])) {
                        $attr = $_POST['config']['column']['atrr'][$field];
                    } else {
                        $attr = array();
                    }

                    $tmpField = (strpos($field, '.') === false) ? $_POST['table'] . '.' . $field : $field;

                    if (!empty($attr['alias'])) {
                        $dataList[$tmpField]['alias'] = $attr['alias'];
                    } else {
                        $dataList[$tmpField]['alias'] = $field;
                    }

                    if (!empty($attr['width'])) {
                        $dataList[$tmpField]['width'] = $attr['width'];
                    }

                    if (!empty($attr['align'])) {
                        $dataList[$tmpField]['align'] = $attr['align'];
                    }

                    if (!empty($attr['format'])) {
                        $dataList[$tmpField]['format'] = $attr['format'];
                    }
                }
            }
            if (isset($_POST['config']['filter']['actived']) && count($_POST['config']['filter']['actived']) > 0) {
                foreach ($_POST['config']['filter']['actived'] as $field) {
                    $ary = array();
                    if (isset($_POST['config']['filter']['atrr'][$field]) &&
                            isset($_POST['config']['filter']['atrr'][$field]['alias'])) {
                        $ary['alias'] = $_POST['config']['filter']['atrr'][$field]['alias'];
                    }

                    $ary['field'] = $_POST['table'] . '.' . $field;
                    $searchForm[] = $ary;
                }
            }

            if (!empty($searchForm)) {
                $conf['search_form'] = $searchForm;
            }

            if (!empty($validate)) {
                $conf['validate'] = $validate;
            }

            $width = 50;
            if (!empty($formElements)) {
                $format = '<a type="button" onclick="__view(\'{ppri}\'); return false;" class="btn btn-mini">' . __BTN_VIEW__ . '</a>';
                $format .= ' <a type="button" onclick="__edit(\'{ppri}\'); return false;" class="btn btn-mini btn-info">' . __BTN_EDIT__ . '</a>';
                $width += 80;
            }
            $format .= ' <a type="button" onclick="__delete(\'{ppri}\'); return false;" class="btn btn-mini btn-danger">' . __BTN_DELETE__ . '</a>';

            $dataList['action'] = array('alias' => __LBL_ACTIONS__, 'format' => $format, 'width' => $width, 'align' => 'center');

            if (!empty($dataList)) {
                $conf['data_list'] = $dataList;
            }


            if (!empty($formElements)) {
                $conf['form_elements'] = $formElements;
            }

            if (!empty($elements)) {
                $conf['elements'] = $elements;
            }

            if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_POST['table'] . '.php')) {
                @unlink(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_POST['table'] . '.php');
            }
            $oldumask = umask(0);
            file_put_contents(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $_POST['table'] . '.php', "<?php exit; ?>\n" . serialize($conf));
            umask($oldumask);
        }
    }

}

function f_scrud_init ($conf){
    $auth = Auth::singleton();
    $auth->checkBrowsePermission();
    
    return $conf;
}