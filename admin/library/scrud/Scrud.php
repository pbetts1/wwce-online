<?php

require_once LIBRARY . '/Validation.php';
require_once LIBRARY . '/FileUpload.php';
require_once LIBRARY . '/Image.php';
require_once LIBRARY . '/scrud/ScrudDao.php';

$hook = Hook::singleton();

$hook->set('SCRUD_INIT');
$hook->set('SCRUD_BEFORE_VALIDATE');
$hook->set('SCRUD_VALIDATE');
$hook->set('SCRUD_ADD_FORM');
$hook->set('SCRUD_EDIT_FORM');
$hook->set('SCRUD_ADD_CONFIRM');
$hook->set('SCRUD_EDIT_CONFIRM');
$hook->set('SCRUD_BEFORE_SAVE');
$hook->set('SCRUD_BEFORE_INSERT');
$hook->set('SCRUD_BEFORE_UPDATE');
$hook->set('SCRUD_COMPLETE_INSERT');
$hook->set('SCRUD_COMPLETE_UPDATE');
$hook->set('SCRUD_COMPLETE_SAVE');

class Scurd {

    private $da;
    private $conf;
    private $title = '';
    private $errors = array();
    private $dao;
    private $primaryKey = array();
    private $fields;
    private $conditions = null;
    private $join = array();
    private $fieldsDisplay = array();
    private $fieldsAlias = array();
    private $orderField = '';
    private $orderType = '';
    private $colsWidth = array();
    private $colsCustom = array();
    private $colsAlign = array();
    private $pageIndex = 1;
    private $limit = 20;
    private $search = 'one_field';
    private $form = array();
    private $elements = array();
    private $validate = array();
    private $data = array();
    private $queryString = array();
    private $table;
    private $fileUpload;
    private $image;
    private $frmType = '1';

    public function __construct($table = null, $conf = array(), $da = null) {
        
        $hook = Hook::singleton();
        if (empty($da)) {
            die('DataAccess object is not null.');
        }
        if ($hook->isExisted('SCRUD_INIT')) {
            $conf = $hook->filter('SCRUD_INIT', $conf);
        }
        
        $conf['theme_path'] = (!empty($conf['theme_path'])) ? $conf['theme_path'] : dirname(__FILE__) . '/templates';
        
        if (file_exists($conf['theme_path'].'/template_functions.php')){
            require_once $conf['theme_path'].'/template_functions.php';
        }else{
            require_once LIBRARY . '/scrud/templates/template_functions.php';
        }
        
        $this->fileUpload = new FileUpload();

        $this->image = new Image(__IMAGE_UPLOAD_REAL_PATH__);

        $this->da = $da;

        if (isset($conf['frm_type'])) {
            $this->frmType = $conf['frm_type'];
        }

        if (empty($conf['order_field'])) {
            $conf['order_field'] = '';
        }
        if (empty($conf['order_type'])) {
            $conf['order_type'] = '';
        }

        if (isset($conf['title'])) {
            $this->setTitle($conf['title']);
        }

        if (isset($conf['form_elements'])) {
            $this->formElements($conf['form_elements']);
        }

        if (isset($conf['elements'])) {
            $this->elements($conf['elements']);
        }

        if (isset($conf['search_form'])) {
            $this->searchForm('fields', $conf['search_form']);
        }

        if (isset($conf['data_list'])) {
            $this->dataList($conf['data_list']);
        }

        if (isset($conf['validate']) && is_array($conf['validate'])) {
            $this->validate = $conf['validate'];
        }
        if ($hook->isExisted('SCRUD_BEFORE_VALIDATE')) {
            $this->validate = $hook->filter('SCRUD_BEFORE_VALIDATE', $this->validate);
        }

        if (isset($conf['join']) && is_array($conf['join'])) {
            $this->join = $conf['join'];
        }

        $conf['limit_opts'] = (isset($conf['limit_opts']) && is_array($conf['limit_opts'])) ? $conf['limit_opts'] : array();
        //$conf['theme_path'] = (!empty($conf['theme_path'])) ? $conf['theme_path'] : dirname(__FILE__) . '/templates';
        $conf['theme'] = (!empty($conf['theme'])) ? $conf['theme'] : '';
        $conf['color'] = (!empty($conf['color'])) ? $conf['color'] : '';
        $this->table = $conf['table'] = $table;

        $this->dao = new ScrudDao($conf['table'], $this->da);
        $this->conf = $conf;

        $fields = $this->da->listFields($this->conf['table']);
        foreach ($fields as $v) {
            $this->fields[] = $this->conf['table'] . '.' . $v['Field'];
            if ($v['Key'] == "PRI") {
                $this->primaryKey[] = $this->conf['table'] . '.' . $v['Field'];
            }
        }

        if (!empty($this->conf['join'])) {
            foreach ($this->conf['join'] as $table => $v) {
                $fields = $this->da->listFields($table);
                foreach ($fields as $v) {
                    $this->fields[] = $table . '.' . $v['Field'];
                }
            }
        }


        $this->dao->p_fields = $this->fields;


        $this->limit = (isset($conf['limit'])) ? $conf['limit'] : 20;
        $this->data = (!empty($_POST['data'])) ? $_POST['data'] : array();
    }

    //public function join($type, $table, $conditions) {
    //    $this->join[] = array($type, $table, $conditions);
    //}

    public function conditions($conditions) {
        $this->conditions = $conditions;
    }

    private function setTitle($title) {
        $this->title = $title;
    }

    private function fields($fields = array()) {
        $this->fields = $fields;
    }

    private function colsWidth($colsWidth = array()) {
        $this->colsWidth = $colsWidth;
    }

    /**
     * @param $dataList
     */
    private function dataList($dataList = array()) {
        foreach ($dataList as $field => $v) {
            if (isset($field)) {
                $this->fieldsDisplay[] = $field;
            } else {
                continue;
            }
            if (isset($v['alias'])) {
                $this->fieldsAlias[$field] = $v['alias'];
            }
            if (isset($v['width'])) {
                $this->colsWidth[$field] = $v['width'];
            }
            if (isset($v['format'])) {
                $this->colsCustom[$field] = $v['format'];
            }
            if (isset($v['align'])) {
                $this->colsAlign[$field] = $v['align'];
            }
        }
    }

    /**
     *
     * @param $type
     * @param $elements
     */
    private function searchForm($type = 'one_field', $elements = array()) {
        switch ($type) {
            case 'one_field':
                $this->search = 'one_field';
                break;
            case 'fields':
                $this->search = $elements;
                break;
        }
    }

    /**
     *
     * @param $form
     */
    private function formElements($form = array()) {
        $this->form = $form;
    }

    private function elements($element = array()) {
        $this->elements = $element;
    }

    /**
     *
     */
    public function getDa() {
        return $this->da;
    }

    /**
     *
     */
    public function process() {
        if (!empty($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $this->queryString);
        }
        if (isset($_GET['apache_mod_rewrite']) && (int) $_GET['apache_mod_rewrite'] == 1){
	        if (isset($this->queryString['wp'])) {
	            unset($this->queryString['wp']);
	        }
        }

        $action = (isset($_GET['xtype'])) ? trim($_GET['xtype']) : '';
        ob_start();
        switch ($action) {
            case 'index':
                $this->index();
                break;
            case 'modalform':
            	$this->modalform();
            	break;
            case 'form':
                $this->form();
                break;
            case 'confirm':
                $this->confirm();
                break;
            case 'update':
                $this->update();
                break;
            case 'del':
                $this->del();
                break;
            case 'delFile':
                $this->delFile();
                break;
            case 'delconfirm':
                $this->delConfirm();
                break;
            case 'exportcsv':
            	$this->exportCsv();
            	break;
            case 'exportcsvall':
            	$this->exportcsvall();
            	break;
            case 'view':
            	$this->view();
            	break;
            default:
                if (isset($_SESSION['auth_token_xtable'])) {
                    unset($_SESSION['auth_token_xtable']);
                }
                if (isset($_SESSION['xtable_search_conditions'])) {
                    unset($_SESSION['xtable_search_conditions']);
                }
                $this->index();
                break;
        }
        $content = ob_get_contents();
        ob_get_clean();

        return $content;
    }

    /**
     *
     */
    private function index() {
        global $config_database;

        if (!empty($this->conf['join'])) {
            foreach ($this->conf['join'] as $tbl => $tmp) {
                if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $tbl . '.php')) {
                    $content = unserialize(str_replace("<?php exit; ?>\n", "", file_get_contents(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $tbl . '.php')));
                    if (!empty($content['form_elements'])) {
                        foreach ($content['form_elements'] as $k => $v) {
                            if (strpos($k, '.') !== false) {
                                $this->form[$k] = $v;
                            }
                        }
                    }
                }
            }
        }

        if (!isset($_POST['src']) && isset($_SESSION['xtable_search_conditions'])) {
            $_POST['src'] = $_SESSION['xtable_search_conditions'];
            unset($_POST['src']['page']);
        }
        if (!isset($_POST['src']['page'])) {
            if (isset($_GET['src']['p'])) {
                $_POST['src']['page'] = $_GET['src']['p'];
            }
        }
        if (isset($_GET['src']['l'])) {
            $_POST['src']['limit'] = $_GET['src']['l'];
        }
        $pageIndex = (!empty($_POST['src']['page'])) ? $_POST['src']['page'] : 1;
        $this->pageIndex = $pageIndex = ((int) $pageIndex > 0) ? (int) $pageIndex : 1;
        $this->limit = (isset($_POST['src']['limit'])) ? $_POST['src']['limit'] : $this->limit;
        $conditions = '';
        $order = '';
        $ps = array();
        $strAnd = '';
        if (is_array($this->search)) {
            foreach ($this->fields as $field) {
                $ary = explode('.', $field);
                if (isset($_POST['src']) &&
                        isset($_POST['src'][$ary[0]][$ary[1]]) &&
                        $_POST['src'][$ary[0]][$ary[1]] != '$$__src_r_all_value__$$'
                ) {
                    if (!is_array($_POST['src'][$ary[0]][$ary[1]]) && trim($_POST['src'][$ary[0]][$ary[1]]) != '') {
                    	if (isset($this->form[$field]['element'][0]) && 
                    	($this->form[$field]['element'][0] == 'autocomplete' || 
                    			$this->form[$field]['element'][0] == 'select')){
                    		$conditions .= $strAnd . $field . ' = ? ';
                    		$ps[] =  $_POST['src'][$ary[0]][$ary[1]];
                    		$strAnd = 'AND ';
                    	}else{
	                        $conditions .= $strAnd . $field . ' like ? ';
	                        $ps[] = '%' . $_POST['src'][$ary[0]][$ary[1]] . '%';
	                        $strAnd = 'AND ';
                    	}
                    } else if (is_array($_POST['src'][$ary[0]][$ary[1]])) {
                        if (count($_POST['src'][$ary[0]][$ary[1]]) > 0) {
                            $strOr  = '';
                            $tempConditons = "";
                            foreach ($_POST['src'][$ary[0]][$ary[1]] as $v) {
                            	if (!empty($v)){
	                                $tempConditons .= $strOr . $field . ' like ? ';
	                                $ps[] = '%,' . $v . ',%';
	                                $strOr = ' OR ';
                            	}
                            }
                            if ($tempConditons != ""){
                            	$conditions .= $strAnd .' ( '.$tempConditons.' ) ';
                            	$strAnd = ' AND ';
                            }
                        }
                    }
                }
            }
        } else if ($this->search == 'one_field') {
            if (isset($_POST['src']) &&
                    isset($_POST['src']['one_field']) &&
                    trim($_POST['src']['one_field']) !== '') {
                $conditions .= "(";
                foreach ($this->fields as $field) {
                    if (!in_array($field, $this->fieldsDisplay))
                        continue;
                    if (trim($_POST['src']['one_field']) !== '') {
                        $conditions .= $strAnd . $field . ' like ? ';
                        $ps[] = '%' . $_POST['src']['one_field'] . '%';
                        $strAnd = 'OR ';
                    }
                }
                $conditions .= ")";
                $strAnd = 'AND ';
            }
        }

        if (isset($_GET['src']['o'])) {
            $_POST['src']['order_field'] = $_GET['src']['o'];
        }
        if (isset($_GET['src']['t'])) {
            $_POST['src']['order_type'] = $_GET['src']['t'];
        }
        if (!empty($_POST['src']['order_field']) && !empty($_POST['src']['order_type'])) {
            $order .= $_POST['src']['order_field'] . ' ' . $_POST['src']['order_type'];
            $this->orderField = trim($_POST['src']['order_field']);
            $this->orderType = trim(strtolower($_POST['src']['order_type']));
        } else if (!empty($this->conf['order_field']) && !empty($this->conf['order_type'])) {
            $order .= $this->conf['order_field'] . ' ' . $this->conf['order_type'];
            $this->orderField = trim($this->conf['order_field']);
            $this->orderType = trim(strtolower($this->conf['order_type']));
        }
        if (!empty($this->conditions)) {
            if (is_array($this->conditions)) {
                $conditions .= ' ' . $strAnd . $this->conditions[0] . ' ';
                foreach ($this->conditions[1] as $v) {
                    $ps[] = $v;
                }
                $strAnd = 'AND ';
            } else {
                $conditions .= ' ' . $strAnd . $this->conditions . ' ';
                $strAnd = 'AND ';
            }
        }

        if (isset($_POST['src'])) {
            $_SESSION['xtable_search_conditions'] = $_POST['src'];
        }

        $params = array();
        $params['fields'] = $this->fields;
        $params['join'] = $this->join;
        $params['found_rows'] = true;
        $params['limit'] = $this->limit;
        $params['page'] = $pageIndex;
        $params['conditions'] = array($conditions, $ps);
        $params['order'] = $order;
        
        $this->results = $this->dao->find($params);
        $this->totalRecord = $this->dao->foundRows();
        $this->totalPage = ceil($this->totalRecord / $this->limit);
        $fields = array();

        if (!empty($this->fieldsDisplay)) {
            $fields = $this->fieldsDisplay;
        } else {
            $fields = $this->fields;
        }

        if (is_file($this->conf['theme_path'] . '/index.php')) {
            require_once $this->conf['theme_path'] . '/index.php';
        } else {
            die($this->conf['theme_path'] . '/index.php is not found.');
        }
    }
    
    public function modalform(){
    	if (!isset($_POST['src']) && isset($_SESSION['xtable_search_conditions'])) {
    		$_POST['src'] = $_SESSION['xtable_search_conditions'];
    	}
    	if (is_file($this->conf['theme_path'] . '/search_form.php')) {
    		require_once $this->conf['theme_path'] . '/search_form.php';
    		exit;
    	} else {
    		die($this->conf['theme_path'] . '/search_form.php is not found.');
    	}
    }

    /**
     * 
     */
    private function exportCsv() {
        if (!isset($_POST['src']) && isset($_SESSION['xtable_search_conditions'])) {
            $_POST['src'] = $_SESSION['xtable_search_conditions'];
            unset($_POST['src']['page']);
        }
        $conditions = '';
        $order = '';
        $ps = array();
        $strAnd = '';
        if (is_array($this->search)) {
            foreach ($this->fields as $field) {
                $ary = explode('.', $field);
                if (isset($_POST['src']) &&
                        isset($_POST['src'][$ary[0]][$ary[1]]) &&
                        !is_array($_POST['src'][$ary[0]][$ary[1]]) && trim($_POST['src'][$ary[0]][$ary[1]]) != '') {
                    $conditions .= $strAnd . $field . ' like ? ';
                    $ps[] = '%' . $_POST['src'][$ary[0]][$ary[1]] . '%';
                    $strAnd = 'AND ';
                }
            }
        } else if ($this->search == 'one_field') {
            if (trim($_POST['src']['one_field']) !== '') {
                $conditions .= "(";
                foreach ($this->fields as $field) {
                    if (!in_array($field, $this->fieldsDisplay))
                        continue;
                    if (trim($_POST['src']['one_field']) !== '') {
                        $conditions .= $strAnd . $field . ' like ? ';
                        $ps[] = '%' . $_POST['src']['one_field'] . '%';
                        $strAnd = 'OR ';
                    }
                }
                $conditions .= ")";
                $strAnd = 'AND ';
            }
        }

        if (isset($_GET['src']['o'])) {
            $_POST['src']['order_field'] = $_GET['src']['o'];
        }
        if (isset($_GET['src']['t'])) {
            $_POST['src']['order_type'] = $_GET['src']['t'];
        }
        if (!empty($_POST['src']['order_field']) && !empty($_POST['src']['order_type'])) {
            $order .= $_POST['src']['order_field'] . ' ' . $_POST['src']['order_type'];
            $this->orderField = trim($_POST['src']['order_field']);
            $this->orderType = trim(strtolower($_POST['src']['order_type']));
        } else if (!empty($this->conf['order_field']) && !empty($this->conf['order_type'])) {
            $order .= $this->conf['order_field'] . ' ' . $this->conf['order_type'];
            $this->orderField = trim($this->conf['order_field']);
            $this->orderType = trim(strtolower($this->conf['order_type']));
        }
        if (!empty($this->conditions)) {
            if (is_array($this->conditions)) {
                $conditions .= ' ' . $strAnd . $this->conditions[0] . ' ';
                foreach ($this->conditions[1] as $v) {
                    $ps[] = $v;
                }
                $strAnd = 'AND ';
            } else {
                $conditions .= ' ' . $strAnd . $this->conditions . ' ';
                $strAnd = 'AND ';
            }
        }

        if (isset($_POST['src'])) {
            $_SESSION['xtable_search_conditions'] = $_POST['src'];
        }

        $params = array();
        $params['fields'] = $this->fields;
        $params['join'] = $this->join;
        $params['conditions'] = array($conditions, $ps);
        $params['order'] = $order;

        $this->results = $this->dao->find($params);
        $fields = array();
        if (!empty($this->fieldsDisplay)) {
            $fields = $this->fieldsDisplay;
        } else {
            $fields = $this->fields;
        }

        if (is_file($this->conf['theme_path'] . '/csv.php')) {
            require_once $this->conf['theme_path'] . '/csv.php';
        } else {
            die($this->conf['theme_path'] . '/csv.php is not found.');
        }
    }

    public function exportcsvall(){
    	$params = array();
    	$params['fields'] = $this->fields;
    	$params['join'] = $this->join;
    	
    	$this->results = $this->dao->find($params);
    	$fields = array();
    	$fields = $this->fields;
    	
    	if (is_file($this->conf['theme_path'] . '/csv.php')) {
    		require_once $this->conf['theme_path'] . '/csv.php';
    	} else {
    		die($this->conf['theme_path'] . '/csv.php is not found.');
    	}
    }
    /**
     *
     */
    private function form() {
        $hook = Hook::singleton();
        if (isset($_GET['key'])) {
            if ($hook->isExisted('SCRUD_EDIT_FORM')) {
                $this->form = $hook->filter('SCRUD_EDIT_FORM', $this->form);
            }
            $params = array();
            $strCon = "";
            $aryVal = array();
            $_tmp = "";
            foreach ($this->primaryKey as $f) {
                $strCon .= $_tmp . " " . $f . ' = ?';
                $_tmp = " AND ";
                $aryVal[] = $_GET['key'][$f];
            }
            $params['fields'] = $this->fields;
            $params['join'] = $this->join;
            $params['conditions'] = array($strCon, $aryVal);
            $rs = $this->dao->findFirst($params);
            $_POST = array_merge($_POST, array('data' => $rs));
        } else {
            if ($hook->isExisted('SCRUD_ADD_FORM')) {
                $this->form = $hook->filter('SCRUD_ADD_FORM', $this->form);
            }
        }
        if (is_file($this->conf['theme_path'] . '/form.php')) {
            require_once $this->conf['theme_path'] . '/form.php';
        } else {
            die($this->conf['theme_path'] . '/form.php is not found.');
        }
    }

    /**
     *
     */
    private function confirm() {
        $hook = Hook::singleton();
        if (isset($_POST['key'])) {
            if ($hook->isExisted('SCRUD_EDIT_CONFIRM')) {
                $this->form = $hook->filter('SCRUD_EDIT_CONFIRM', $this->form);
            }
        } else {
            if ($hook->isExisted('SCRUD_ADD_CONFIRM')) {
                $this->form = $hook->filter('SCRUD_ADD_CONFIRM', $this->form);
            }
        }
        global $imageExtensions;
        global $fileExtensions;
        foreach ($this->form as $field => $v) {
            $elements = (isset($v['element'])) ? $v['element'] : array();
            switch ($elements[0]) {
                case 'image':
                    $tmpfields = explode('.', $field);
                    $this->fileUpload->uploadDir = __IMAGE_UPLOAD_REAL_PATH__;
                    $this->fileUpload->extensions = $imageExtensions;
                    $this->fileUpload->tmpFileName = $_FILES['img_data']['tmp_name'][$tmpfields[0]][$tmpfields[1]];
                    $this->fileUpload->fileName = $_FILES['img_data']['name'][$tmpfields[0]][$tmpfields[1]];
                    $this->fileUpload->httpError = $_FILES['img_data']['error'][$tmpfields[0]][$tmpfields[1]];

                    if ($this->fileUpload->upload()) {
                        $this->data[$field] = $_POST['data'][$tmpfields[0]][$tmpfields[1]] = $this->fileUpload->newFileName;
                        if (isset($elements[1]) && isset($elements[1]['thumbnail'])) {
                            switch ($elements[1]['thumbnail']) {
                                case 'mini':
                                    $this->image->miniThumbnail($this->fileUpload->newFileName);
                                    break;
                                case 'small':
                                    $this->image->smallThumbnail($this->fileUpload->newFileName);
                                    break;
                                case 'medium':
                                    $this->image->mediumThumbnail($this->fileUpload->newFileName);
                                    break;
                                case 'large':
                                    $this->image->largeThumbnail($this->fileUpload->newFileName);
                                    break;
                                default :
                                    $this->image->miniThumbnail($this->fileUpload->newFileName);
                                    break;
                            }
                        } else {
                            $this->image->miniThumbnail($this->fileUpload->newFileName);
                        }
                        $width = (isset($elements[1]['width']))?$elements[1]['width']:'';
                        $height = (isset($elements[1]['height']))?$elements[1]['height']:'';
                        $fix = 'width';
                        if ($width != '' || $height != ''){
                        	$this->image->newWidth = '';
                        	$this->image->newHeight = '';
                        	$this->image->pre = '';
                        	if ($width == ''){
                        		$fix = 'height';
                        	}
	                        $this->image->resize($this->fileUpload->newFileName,$width,$height,$fix);
                        }
                    }
                    $error = $this->fileUpload->getMessage();
                    if (!empty($error)) {
                        $this->errors[$field] = $error;
                        $this->data[$field] = "no error";
                    }
                    break;
                case 'file':
                    $tmpfields = explode('.', $field);
                    $this->fileUpload->uploadDir = __FILE_UPLOAD_REAL_PATH__;
                    $this->fileUpload->extensions = $fileExtensions;
                    $this->fileUpload->tmpFileName = $_FILES['file_data']['tmp_name'][$tmpfields[0]][$tmpfields[1]];
                    $this->fileUpload->fileName = $_FILES['file_data']['name'][$tmpfields[0]][$tmpfields[1]];
                    $this->fileUpload->httpError = $_FILES['file_data']['error'][$tmpfields[0]][$tmpfields[1]];

                    if ($this->fileUpload->upload()) {
                        $this->data[$field] = $_POST['data'][$tmpfields[0]][$tmpfields[1]] = $this->fileUpload->newFileName;
                    }
                    $error = $this->fileUpload->getMessage();
                    if (!empty($error)) {
                        $this->errors[$field] = $error;
                        $this->data[$field] = "no error";
                    }
                    break;
            }
        }
        if (count($_POST) > 0 && $this->validate()) {
            if (is_file($this->conf['theme_path'] . '/confirm.php')) {
                require_once $this->conf['theme_path'] . '/confirm.php';
            } else {
                die($this->conf['theme_path'] . '/confirm.php is not found.');
            }
        } else {
            if (isset($_POST['key'])) {
                if ($hook->isExisted('SCRUD_EDIT_FORM')) {
                    $this->form = $hook->filter('SCRUD_EDIT_FORM', $this->form);
                }
            } else {
                if ($hook->isExisted('SCRUD_ADD_FORM')) {
                    $this->form = $hook->filter('SCRUD_ADD_FORM', $this->form);
                }
            }
            if (is_file($this->conf['theme_path'] . '/form.php')) {
                require_once $this->conf['theme_path'] . '/form.php';
            } else {
                die($this->conf['theme_path'] . '/form.php is not found.');
            }
        }
    }

    /**
     *
     */
    private function update() {
        $hook = Hook::singleton();
        foreach ($this->data[$this->conf['table']] as $k => $v){
        	 if (is_array($v)){
        	 	$this->data[$this->conf['table']][$k] = ','.implode(',', $v).',';
        	 }else{
        	 	$this->data[$this->conf['table']][$k] = $v;
        	 }
        }
        $historyDao = new ScrudDao('crud_histories', $this->da);
        $history = array();
        $history['user_id'] = (isset($_SESSION['CRUD_AUTH']['id']))?$_SESSION['CRUD_AUTH']['id']:0;
        $history['user_name'] = (isset($_SESSION['CRUD_AUTH']['user_name']))?$_SESSION['CRUD_AUTH']['user_name']:'';
        $history['history_table_name'] = $this->conf['table'];
        $history['history_date_time'] = date("Y-m-d H:i:s");
        if (count($_POST) > 0 && $this->validate() && $_POST['auth_token'] == $_SESSION['auth_token_xtable']) {
            if ($hook->isExisted('SCRUD_BEFORE_SAVE')) {
                $this->data = $hook->filter('SCRUD_BEFORE_SAVE', $this->data);
            }
            $editFlag = false;
            foreach ($this->primaryKey as $f) {
                $ary = explode('.', $f);
                if (isset($_POST['key'][$ary[0]][$ary[1]])) {
                    $editFlag = true;
                } else {
                    $editFlag = false;
                    break;
                }
            }
            $q = $this->queryString;
            $q['xtype'] = 'index';
            if (isset($q['key']))
                unset($q['key']);

            if ($editFlag) {
                $params = array();
                $strCon = "";
                $aryVal = array();
                $_tmp = "";
                foreach ($this->primaryKey as $f) {
                    $ary = explode('.', $f);
                    $strCon .= $_tmp . $f . ' = ?';
                    $_tmp = " AND ";
                    $aryVal[] = $_POST['key'][$ary[0]][$ary[1]];
                }
                $params = array($strCon, $aryVal);
                try {
                    if ($hook->isExisted('SCRUD_BEFORE_UPDATE')) {
                        $this->data = $hook->filter('SCRUD_BEFORE_UPDATE', $this->data);
                    }
                    $this->dao->update($this->data[$this->conf['table']], $params);
                    
                    $tmpData = $this->data[$this->conf['table']];
                    foreach ($this->primaryKey as $f) {
                    	$ary = explode('.', $f);
                    	$tmpData[$ary[1]] = $_POST['key'][$ary[0]][$ary[1]];
                    }
                    
                    $history['history_data'] = json_encode($tmpData);
                    $history['history_action'] = 'update';
                    $historyDao->insert($history);

                    if ($hook->isExisted('SCRUD_COMPLETE_SAVE')) {
                        $hook->execute('SCRUD_COMPLETE_SAVE', $this->data);
                    }
                    if ($hook->isExisted('SCRUD_COMPLETE_UPDATE')) {
                        $hook->execute('SCRUD_COMPLETE_UPDATE', $this->data);
                    }

                    header("Location: ?" . http_build_query($q, '', '&'));
                } catch (Exception $e) {
                    $this->errors['__NO_FIELD__'][] = $e->getMessage();
                    if (is_file($this->conf['theme_path'] . '/form.php')) {
                        require_once $this->conf['theme_path'] . '/form.php';
                    } else {
                        die($this->conf['theme_path'] . '/form.php is not found.');
                    }
                }
            } else {
                try {
                    if ($hook->isExisted('SCRUD_BEFORE_INSERT')) {
                        $this->data = $hook->filter('SCRUD_BEFORE_INSERT', $this->data);
                    }
                    $this->dao->insert($this->data[$this->conf['table']]);
                    $history['history_data'] = json_encode($this->data[$this->conf['table']]);
                    $history['history_action'] = 'add';
                    $historyDao->insert($history);

                    if ($hook->isExisted('SCRUD_COMPLETE_SAVE')) {
                        $hook->execute('SCRUD_COMPLETE_SAVE', $this->data);
                    }
                    if ($hook->isExisted('SCRUD_COMPLETE_INSERT')) {
                        $hook->execute('SCRUD_COMPLETE_INSERT', $this->data);
                    }
                    
                    header("Location: ?" . http_build_query($q, '', '&'));
                } catch (Exception $e) {
                    $this->errors['__NO_FIELD__'][] = $e->getMessage();
                    if (is_file($this->conf['theme_path'] . '/form.php')) {
                        require_once $this->conf['theme_path'] . '/form.php';
                    } else {
                        die($this->conf['theme_path'] . '/form.php is not found.');
                    }
                }
                if (isset($_SESSION['xtable_search_conditions'])) {
                    unset($_SESSION['xtable_search_conditions']);
                }
            }
        } else {
            if ($_POST['auth_token'] != $_SESSION['auth_token_xtable']) {
                $this->errors['auth_token'][] = 'Auth token does not exist.';
            }
            if (is_file($this->conf['theme_path'] . '/form.php')) {
                require_once $this->conf['theme_path'] . '/form.php';
            } else {
                die($this->conf['theme_path'] . '/form.php is not found.');
            }
        }
    }

    private function delConfirm() {
        if (isset($_GET['key'])) {
            $params = array();
            $strCon = "";
            $aryVal = array();
            $_tmp = "";
            foreach ($this->primaryKey as $f) {
                $strCon .= $_tmp . " " . $f . ' = ?';
                $_tmp = " AND ";
                $aryVal[] = $_GET['key'][$f];
            }
            $params['fields'] = $this->fields;
            $params['join'] = $this->join;
            $params['conditions'] = array($strCon, $aryVal);
            $rs = $this->dao->findFirst($params);
            $_POST = array_merge($_POST, array('data' => $rs));

            if (is_file($this->conf['theme_path'] . '/delete_confirm.php')) {
                require_once $this->conf['theme_path'] . '/delete_confirm.php';
            }
        } else {
            $q = $this->queryString;
            $q['xtype'] = 'index';
            if (isset($q['key']))
                unset($q['key']);
            if (isset($q['auth_token']))
                unset($q['auth_token']);
            header("Location: ?" . http_build_query($q, '', '&'));
        }
    }

    /**
     *
     */
    private function del() {
    	$historyDao = new ScrudDao('crud_histories', $this->da);
    	$history = array();
    	$history['user_id'] = (isset($_SESSION['CRUD_AUTH']['id']))?$_SESSION['CRUD_AUTH']['id']:0;
    	$history['user_name'] = (isset($_SESSION['CRUD_AUTH']['user_name']))?$_SESSION['CRUD_AUTH']['user_name']:'';
    	$history['history_table_name'] = $this->conf['table'];
    	$history['history_date_time'] = date("Y-m-d H:i:s");
        if (isset($_GET['key']) && $_GET['auth_token'] == $_SESSION['auth_token_xtable']) {
            $params = array();
            $strCon = "";
            $aryVal = array();
            $_tmp = "";
            foreach ($this->primaryKey as $f) {
                $strCon .= $_tmp . " " . $f . ' = ?';
                $_tmp = " AND ";
                $aryVal[] = $_GET['key'][$f];
            }
            $params = array($strCon, $aryVal);
            
            $tmpData = $this->dao->findFirst(array('conditions'=>$params));
            $this->dao->remove($params);
            
            $history['history_data'] = json_encode($tmpData[$this->conf['table']]);
            $history['history_action'] = 'delete';
            $historyDao->insert($history);
            
        }
        $q = $this->queryString;
        $q['xtype'] = 'index';
        if (isset($q['key']))
            unset($q['key']);
        if (isset($q['auth_token']))
            unset($q['auth_token']);
        header("Location: ?" . http_build_query($q, '', '&'));
    }

    private function delFile() {
        if (isset($_GET['fileType']) && $_GET['fileType'] == 'img') {
            $this->fileUpload->uploadDir = __IMAGE_UPLOAD_REAL_PATH__;
        } else {
            $this->fileUpload->uploadDir = __FILE_UPLOAD_REAL_PATH__;
        }

        $_POST['src']['field'] = str_replace('data.', '', $_POST['src']['field']);
        if (isset($_POST['src']['field']) &&
                is_file($this->fileUpload->uploadDir . $_POST['src']['file'])) {
            $params = array();
            $strCon = "";
            $aryVal = array();
            $_tmp = "";
            foreach ($this->primaryKey as $f) {
                $strCon .= $_tmp . " " . $f . ' = ?';
                $_tmp = " AND ";
                $aryVal[] = $_GET['key'][$f];
            }
            $params['fields'] = $this->fields;
            $params['join'] = $this->join;
            $params['conditions'] = array($strCon, $aryVal);
            $rs = $this->dao->findFirst($params);
            $ary = explode('.', $_POST['src']['field']);
            if (!empty($rs)) {
                if (trim($rs[$ary[0]][$ary[1]]) == trim($_POST['src']['file'])) {
                    $data = array();
                    $data[$ary[1]] = '';
                    $this->dao->update($data, $params['conditions']);
                    $this->fileUpload->delFile(trim($_POST['src']['file']));
                    $this->fileUpload->delFile('thumbnail_' . trim($_POST['src']['file']));
                }
            }
        }
    }

    /**
     *
     * Enter description here ...
     */
    private function view() {
        if (isset($_GET['key'])) {
            $params = array();
            $strCon = "";
            $aryVal = array();
            $_tmp = "";
            foreach ($this->primaryKey as $f) {
                $strCon .= $_tmp . " " . $f . ' = ?';
                $_tmp = " AND ";
                $aryVal[] = $_GET['key'][$f];
            }
            $params['fields'] = $this->fields;
            $params['join'] = $this->join;
            $params['conditions'] = array($strCon, $aryVal);
            $rs = $this->dao->findFirst($params);
            $_POST = array_merge($_POST, array('data' => $rs));

            if (is_file($this->conf['theme_path'] . '/view.php')) {
                require_once $this->conf['theme_path'] . '/view.php';
            }
        } else {
            $q = $this->queryString;
            $q['xtype'] = 'index';
            if (isset($q['key']))
                unset($q['key']);
            if (isset($q['auth_token']))
                unset($q['auth_token']);
            header("Location: ?" . http_build_query($q, '', '&'));
        }
    }

    private function validate() {
        $hook = Hook::singleton();
        foreach ($this->validate as $k => $v) {
            if (isset($v['rule'])) {
                $this->_validate($k, $v);
            } else {
                foreach ($v as $k1 => $v1) {
                    $this->_validate($k, $v1);
                }
            }
        }
        if ($hook->isExisted('SCRUD_VALIDATE')) {
            $this->errors = $hook->filter('SCRUD_VALIDATE', $this->errors);
        }

        return (count($this->errors) > 0) ? false : true;
    }

    private function _validate($k, $v) {
        $ary = explode('.', $k);
        $validation = Validation::singleton();
        if ($v['rule'] == 'notEmpty') {
            $v['required'] = true;
        }
        if (isset($v['required']) && $v['required'] === true) {
            if (@!$validation->notEmpty($this->data[$ary[0]][$ary[1]])) {
                $this->errors[$k][] = $v['message'];
            } else {
                if (!is_array($v['rule'])) {
                    if (trim($v['rule']) != '') {
                        if (!$validation->{$v['rule']}($this->data[$ary[0]][$ary[1]])) {
                            $this->errors[$k][] = $v['message'];
                        }
                    }
                } else {
                    if (trim($v['rule'][0]) != '') {
                        $params = array($this->data[$ary[0]][$ary[1]]);
                        foreach ($v['rule'] as $value) {
                            if ($value == $v['rule'][0])
                                continue;
                            $params[] = $value;
                        }
                        if (!call_user_func_array(array($validation, $v['rule'][0]), $params)) {
                            $this->errors[$k][] = $v['message'];
                        }
                    }
                }
            }
        } else if (!empty($this->data[$ary[0]][$ary[1]])) {
            if (!is_array($v['rule'])) {
                if (trim($v['rule']) != '') {
                    if (!$validation->{$v['rule']}($this->data[$ary[0]][$ary[1]])) {
                        $this->errors[$k][] = $v['message'];
                    }
                }
            } else {
                if (trim($v['rule'][0]) != '') {
                    $params = array($this->data[$ary[0]][$ary[1]]);
                    foreach ($v['rule'] as $value) {
                        if ($value == $v['rule'][0])
                            continue;
                        $params[] = $value;
                    }
                    if (!call_user_func_array(array($validation, $v['rule'][0]), $params)) {
                        $this->errors[$k][] = $v['message'];
                    }
                }
            }
        }
    }

    /**
     *
     * Enter description here ...
     */
    private function getToken() {
        if (!isset($_SESSION['auth_token_xtable'])) {
            $string = 'HTTP_USER_AGENT=' . $_SERVER['HTTP_USER_AGENT'];
            $string .= 'time=' . time();
            $auth = md5($string);
            $_SESSION['auth_token_xtable'] = $auth;
        } else {
            $auth = $_SESSION['auth_token_xtable'];
        }

        return $auth;
    }

}