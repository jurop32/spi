<?php

/*
 * script for managing articles
 */

class orders extends maincontroller
{
    public function __construct($registry)
    {
        parent::__construct($registry);
    }

    public function getItems()
    {
        if ($_SESSION[PAGE_SESSIONID]['privileges']['orders'][0] == '0')
            die($this->text('dont_have_permission'));

        $where = '';
        $order = '';
        $limit = '';
        if (isset($this->data['owner']) && $this->data['owner'] != '-' && $this->data['owner'] != '')
        {
            $where .= " WHERE vlastnik='" . $this->data['owner'] . "'";
        }
        if (isset($this->data['state']) && $this->data['state'] != '-' && $this->data['state'] != '')
        {
            if (strlen($where) == 0)
            {
                $where .= " WHERE " . $this->data['state'];
            } else {
                $where .= " AND " . $this->data['state'];
            }
        }

        if (isset($this->data['order']) && $this->data['order'] != '')
        {
            $order .= " ORDER BY " . $this->data['order'];
        }

        $temp = dibi::query('SELECT COUNT(id) FROM ' . DB_TABLEPREFIX . 'ORDERS' . $where);
        $allresultcount = $temp->fetchSingle();
        $resultpagescount = ceil($allresultcount / $this->data['resultcount']);
        if ($resultpagescount == 0)
            $resultpagescount = 1;

        if (isset($this->data['resultpage']) && $this->data['resultpage'] != '')
        {
            $resultpage = (Int)$this->data['resultpage'];
        } else {
            $resultpage = 1;
        }
        if (isset($this->data['resultcount']) && $this->data['resultcount'] != '')
        {
            $limit_od = $resultpage * (Int)$this->data['resultcount'];
            if ($allresultcount < $limit_od)
            {
                $limit_od = ($resultpagescount - 1) * (Int)$this->data['resultcount'];
            }
            $limit .= " LIMIT " . $limit_od . "," . $this->data['resultcount'];
        }

        $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'ORDERS' . $where . $order . $limit);
        $orders = $temp->fetchAll();
        if (count($orders) > 0)
        {
            $markup =
                '<table>' .
                '<tr>' .
                '<th class="control ui-corner-all ui-state-hover">' . $this->text('id') . '</th>' .
                '<th class="text ui-corner-all ui-state-hover">' . $this->languager->getText('common', 'name') . '</th>' .
                '<th class="control ui-corner-all ui-state-hover">' . $this->text('t4') . '</th>' .
                '<th class="control ui-corner-all ui-state-hover">' . $this->text('t5') . '</th>' .
                '<th class="control ui-corner-all ui-state-hover">' . $this->text('t2') . '</th>' .
                '<th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-search" title="' . $this->text('t7') . '"></span></th>' .
                '<th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-refresh" title="' . $this->text('t9') . '"></span></th>';
            if ($_SESSION[PAGE_SESSIONID]['privileges']['orders'][2] == '1'
                || $_SESSION[PAGE_SESSIONID]['privileges']['orders'][3] == '1')
            {
                $markup .=
                    '<th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-pencil" title="' . $this->text('edit') . '"></span></th>' .
                    '<th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-trash" title="' . $this->text('delete') . '"></span></th>';
            }
            $markup .= '</tr>';
            foreach ($orders as $order)
            {
                $markup .= '<tr>';
                $markup .= '<td>' . $order['id'] . '</td>';
                $markup .= '<td class="text">' . $order['meno'] . ' ' . $order['priezvisko'] . ' - ' . $order['miesto'] . '</td>';
                $markup .= '<td>' . date('d.m.Y', strtotime($order['datum_platnosti'])) . '</td>';
                $markup .= '<td>' . (($order['datum_ukoncenia'] != '0000-00-00') ? date('d.m.Y', strtotime($order['datum_ukoncenia'])) : '-') . '</td>';
                $markup .= '<td>';
                if ($order['ukoncene'] == '1')
                {
                    $markup .= $this->text('s_3');
                } else {
                    if (strtotime($order['datum_platnosti']) > time())
                    {
                        $markup .= $this->text('s_2');
                    } else {
                        $markup .= $this->text('s_1');
                    }
                }
                $markup .= '</td>';
                $markup .= '<td><button class="ui-icon-search notext button" onclick="showItem(' . $order['id'] . ');">' . $this->text('t7') . '</button></td>';
                $markup .= '<td><button class="ui-icon-refresh notext button" onclick="acquireItem(' . $order['id'] . ');"' .
                    (($order['ukoncene'] == '1'
                        || strtotime('now') < strtotime($order['datum_platnosti']))
                        ? ' disabled="disabled"'
                        : ''
                    ) . '>' .
                    $this->text('t9') . '</button></td>';
                if ($_SESSION[PAGE_SESSIONID]['privileges']['orders'][2] == '1'
                    || $_SESSION[PAGE_SESSIONID]['privileges']['orders'][3] == '1')
                {
                    $markup .=
                        '<td><button class="ui-icon-pencil notext button" onclick="editItem(' . $order['id'] . ');"' .
                        (($_SESSION[PAGE_SESSIONID]['privileges']['orders'][3] == '0'
                            && $order['vlastnik'] != $_SESSION[PAGE_SESSIONID]['id'])
                            ? ' disabled="disabled"'
                            : ''
                        ) . '>' . $this->text('edit') . '</button></td>' .
                        '<td><button class="ui-icon-trash notext button" onclick="confirmDeleteItem(' . $order['id'] . ');"' .
                        (($order['ukoncene'] == '1'
                            || ($_SESSION[PAGE_SESSIONID]['privileges']['orders'][3] == '0'
                            && $order['vlastnik'] != $_SESSION[PAGE_SESSIONID]['id']))
                            ? ' disabled="disabled"'
                            : ''
                        ) . '>' . $this->text('delete') . '</button></td>';
                }
                $markup .= '</tr>';
            }
            $markup .= '</table>';
            return array(
                'state' => 'ok',
                'data' => array(
                    'html' => $markup,
                    'resultpagescount' => $resultpagescount
                )
            );
        } else {
            return array(
                'state' => 'ok',
                'data' => '<p>' . $this->text('noorders') . '</p>'
            );
        }
    }

    public function getNewItemForm()
    {
        if ($_SESSION[PAGE_SESSIONID]['privileges']['orders'][1] == '0')
        {
            die($this->text('dont_have_permission'));
        }

        return array(
            'state' => 'ok',
            'data' => (
                '<div><h4 class="left">' . $this->text('t6') . '</h4>' .
                $this->getForm('add') .
                '</div>'
            )
        );
    }

    public function getEditItemForm()
    {
        if ($_SESSION[PAGE_SESSIONID]['privileges']['orders'][2] == '0'
            && $_SESSION[PAGE_SESSIONID]['privileges']['orders'][3] == '0')
        {
            die($this->text('dont_have_permission'));
        }

        $this->datavalidator->addValidation('id', 'req', $this->text('e1'));
        $this->datavalidator->addValidation('id', 'numeric', $this->text('e2'));

        $result = $this->datavalidator->ValidateData($this->data);
        if (! $result)
        {
            $errors = $this->datavalidator->GetErrors();
            return
                array(
                    'state' => 'error',
                    'data' => reset($errors)
            );
        }

        $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'ORDERS WHERE id=' . $this->data['id']);
        $order = $temp->fetchAssoc('id');
        if (count($temp) == 1)
        {
            $order = $order[$this->data['id']];

            $_SESSION[PAGE_SESSIONID]['order_dirname'] = $this->data['id'];
            $markup = '<div><h4 class="left">' . $this->text('t21') . ' ' . $this->data['id'] . '</h4>';
            $markup .= $this->getForm('update', $order);
            $markup .= '</div>';
            return
                array(
                    'state' => 'ok',
                    'data' => $markup
                );
        } else {
            return
                array(
                    'state' => 'error',
                    'data' => $this->text('e3')
                );
        }
    }

    public function checkItem()
    {
        if ($_SESSION[PAGE_SESSIONID]['privileges']['orders'][1] == '0')
        {
            die ($this->text('dont_have_permission'));
        }

        //var_dump('$this->data', $this->data);
        //$this->datavalidator->addValidation('meno','req',$this->text('e4'));
        //$this->datavalidator->addValidation('meno','maxlen=50',$this->text('e5'));
        //$this->datavalidator->addValidation('priezvisko','req',$this->text('e6'));
        //$this->datavalidator->addValidation('priezvisko','maxlen=50',$this->text('e7'));
        //$this->datavalidator->addValidation('miesto','req',$this->text('e13'));
        //$this->datavalidator->addValidation('miesto','maxlen=100',$this->text('e14'));
        //$result = $this->datavalidator->ValidateData($this->data);
        //if (!$result)
        //{
        //	$errors = $this->datavalidator->GetErrors();
        //	return array('state'=>'error','data'=> reset($errors),'field' => key($errors));
        //}
        //if (!$result) return $result;

        $src = new DibiDataSource(DB_TABLEPREFIX . 'orders', dibi::getConnection());

        if ($this->data['meno'] || $this->data['priezvisko'] || $this->data['miesto'])
        {
            $src->select(
                array('id', 'meno', 'priezvisko', 'miesto', 'datum_platnosti', 'datum_ukoncenia')
            );

            if ($this->data['meno'])
            {
                $src->where('meno LIKE "%' . $this->data['meno'] . '%"');
            }
            if ($this->data['priezvisko'])
            {
                $src->where('priezvisko LIKE "%' . $this->data['priezvisko'] . '%"');
            }
            if ($this->data['miesto'])
            {
                $src->where('miesto LIKE "%' . $this->data['miesto'] . '%"');
            }

            // if (isset($this->data['order']) && $this->data['order'])
            // {
                // $src->orderBy($this->data['order']);
            // }

            $res = $src->getResult();

            $rowCount = $res->getRowCount();
            $pageCount = ceil($rowCount / $this->data['resultcount']);
            $pageCount = $pageCount ? $pageCount : 1;

            $page =
                (isset($this->data['resultpage']) && $this->data['resultpage'])
                    ? intval($this->data['resultpage'])
                    : 1;

            if (isset($this->data['resultcount']) && $this->data['resultcount'])
            {
                $limit = intval($this->data['resultcount']);
                $offset = $page * $limit;
                if ($rowCount < $offset)
                {
                    $offset = ($pageCount - 1) * $limit;
                }
                $src->applyLimit($limit, $offset);
            }

            $orders = $res->fetchAll();
            if (count($orders))
            {
                $markup =
                    '<table>' .
                    '<tr>' .
                    '<th class="control ui-corner-all ui-state-hover">' . $this->text('id') . '</th>' .
                    '<th class="text ui-corner-all ui-state-hover">' . $this->languager->getText('common', 'name') . '</th>' .
                    '<th class="control ui-corner-all ui-state-hover">' . $this->text('t4') . '</th>' .
                    '<th class="control ui-corner-all ui-state-hover">' . $this->text('t5') . '</th>' .
                    '<th class="control ui-corner-all ui-state-hover">' . $this->text('t2') . '</th>' .
                    '<th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-search" title="' . $this->text('t7') . '"></span></th>' .
                    '<th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-refresh" title="' . $this->text('t9') . '"></span></th>' .
                    (($_SESSION[PAGE_SESSIONID]['privileges']['orders'][2] == '1'
                        || $_SESSION[PAGE_SESSIONID]['privileges']['orders'][3] == '1')
                        ? (
                            '<th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-pencil" title="' . $this->text('edit') . '"></span></th>' .
                            '<th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-trash" title="' . $this->text('delete') . '"></span></th>'
                        )
                        : ''
                    ) .
                    '</tr>';
                foreach ($orders as $order)
                {
                    $markup .=
                        '<tr>' .
                        '<td>' . $order['id'] . '</td>' .
                        '<td class="text">' .
                        $order['meno'] . ' ' . $order['priezvisko'] . ' - ' . $order['miesto'] .
                        '</td>' .
                        '<td>' . date('d.m.Y', strtotime($order['datum_platnosti'])) . '</td>' .
                        '<td>' .
                            (($order['datum_ukoncenia'] != '0000-00-00')
                                ? date('d.m.Y', strtotime($order['datum_ukoncenia']))
                                : '-'
                            ) .
                        '</td>' .
                        '<td>';

                    if ($order['ukoncene'] == '1')
                    {
                        $markup .= $this->text('s_3');
                    } else {
                        $markup .=
                            (strtotime($order['datum_platnosti']) > time())
                                ? $this->text('s_2')
                                : $this->text('s_1');
                    }

                    $markup .=
                        '</td>' .
                        '<td>' .
                        '<button class="ui-icon-search notext button" onclick="showItem(' . $order['id'] . ');">' .
                        $this->text('t7') .
                        '</button>' .
                        '</td>' .
                        '<td>' .
                        '<button class="ui-icon-refresh notext button" onclick="acquireItem(' . $order['id'] . ');"' .
                            (($order['ukoncene'] == '1'
                                || strtotime('now') < strtotime($order['datum_platnosti']))
                                ? ' disabled="disabled"'
                                : ''
                            ) .
                        '>' . $this->text('t9') .
                        '</button>' .
                        '</td>';

                    if ($_SESSION[PAGE_SESSIONID]['privileges']['orders'][2] == '1'
                        || $_SESSION[PAGE_SESSIONID]['privileges']['orders'][3] == '1')
                    {
                        $markup .=
                            '<td>' .
                            '<button class="ui-icon-pencil notext button" onclick="editItem(' . $order['id'] . ');"' .
                                (($_SESSION[PAGE_SESSIONID]['privileges']['orders'][3] == '0'
                                    && $order['vlastnik'] != $_SESSION[PAGE_SESSIONID]['id'])
                                        ? ' disabled="disabled"'
                                        : ''
                                ) .
                            '>' . $this->text('edit') .
                            '</button>' .
                            '</td>' .
                            '<td>' .
                            '<button class="ui-icon-trash notext button" onclick="confirmDeleteItem(' . $order['id'] . ');"' .
                                (($order['ukoncene'] == '1'
                                    || ($_SESSION[PAGE_SESSIONID]['privileges']['orders'][3] == '0'
                                        && $order['vlastnik'] != $_SESSION[PAGE_SESSIONID]['id']))
                                        ? ' disabled="disabled"'
                                        : ''
                                ) .
                            '>' . $this->text('delete') .
                            '</button>' .
                            '</td>';
                    }
                    $markup .= '</tr>';
                }
                $markup .= '</table>';

                return
                    array(
                        'state' => 'ok',
                        'data' => array(
                            'html' => $markup,
                            'resultpagescount' => $pageCount
                        )
                    );
            } else {
                return
                    array(
                        'state' => 'ok',
                        'data' => '<script type="text/javascript">newItem()</script>'
                    );
            }
        } else {
            return
                array(
                    'state' => 'ok',
                    'data' => (
                        '<div><h4 class="left">' . $this->text('t6') . '</h4>' .
                        '<button class="ui-icon-close button right" onclick="cancelAction();">' . $this->text('close') . '</button>' .
                        '<div class="clear"></div>' .
                        '<fieldset>' .
                        '<legend>' . $this->text('t40') . ':</legend>' .
                        '<fieldset>' .
                        '<legend>' . $this->text('t10') . ':</legend>' .
                        '<input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="meno" id="meno" value="' . ((isset($data['meno'])) ? $data['meno'] : '') . '" placeholder="' . $this->text('t11') . '" />' .
                        '<input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="priezvisko" id="priezvisko" value="' . ((isset($data['priezvisko'])) ? $data['priezvisko'] : '') . '" placeholder="' . $this->text('t12') . '" />' .
                        '</fieldset>' .
                        '<fieldset>' .
                        '<legend>' . $this->text('t13') . ':</legend>' .
                        '<input class="ui-autocomplete-input ui-widget-content ui-corner-all" type="text" name="miesto" id="miesto" value="' . ((isset($data['miesto'])) ? $data['miesto'] : '') . '" placeholder="' . $this->text('t19') . '" />' .
                        '</fieldset>' .
                        '<button class="ui-icon-cancel button right" onclick="cancelAction();">' . $this->text('cancel') . '</button>' .
                        '<button class="ui-icon-disk button right" onclick="checkItem();">' . $this->text('ok') . '</button>' .
                        '<div class="clear"></div>' .
                        '</fieldset>'
                    )
                );
        }
    }

    // Create
    public function addItem()
    {
        if ($_SESSION[PAGE_SESSIONID]['privileges']['orders'][1] == '0')
            die($this->text('dont_have_permission'));

        $result = $this->checkData();
        if ($result !== true)
        {
            return $result;
        }

        if ($_SESSION[PAGE_SESSIONID]['privileges']['orders'][4] == '0')
        {
            $this->data['vlastnik'] = $_SESSION[PAGE_SESSIONID]['id'];
        } else {
            $this->datavalidator->addValidation('vlastnik', 'req', $this->text('e28'));
            $this->datavalidator->addValidation('vlastnik', 'numeric', $this->text('e29'));

            $result = $this->datavalidator->ValidateData($this->data);
            if (! $result)
            {
                $errors = $this->datavalidator->GetErrors();
                return
                    array(
                        'state' => 'error',
                        'data' => reset($errors)
                );
            }
        }

        $this->data['typ_zateplenia'] = str_replace('z_', '', $this->data['typ_zateplenia']);
        $this->data['datum_pridania'] = date("Y-m-d H:i:s");
        $this->data['datum_zmeny'] = date("Y-m-d");
        $this->data['datum_platnosti'] = date('Y-m-d', strtotime($this->data['datum_platnosti']));
        $this->data['datum_ukoncenia'] = '0000-00-00';
        $this->data['ukoncene'] = '0';

        $temp = dibi::query('INSERT INTO ' . DB_TABLEPREFIX . 'ORDERS ', $this->data);
        $returnvalue = array();
        if ($temp == 0 || $temp == 1)
        {
            $returnvalue = array(
                'state' => 'highlight',
                'data' => $this->text('h5')
            );
            $id = dibi::insertId();

            // create photos directory
            $temp = mkdir(FRONTEND_ABSDIRPATH . ORDERFILESPATH . $id, 0777);
            if ($temp === false)
            {
                $returnvalue['data'] .= '<br />' . $this->text('e27');
            }

            // send mails for all about new order
            $this->data['id'] = $id;
            $this->sendAddEmail($this->data);
        } else {
            $returnvalue = array(
                'state' => 'error',
                'data' => $this->text('e26')
            );
        }

        return $returnvalue;
    }

    // Read
    public function getItem()
    {
        $this->datavalidator->addValidation('id', 'req', $this->text('e1'));
        $this->datavalidator->addValidation('id', 'numeric', $this->text('e2'));

        $result = $this->datavalidator->ValidateData($this->data);
        if (! $result)
        {
            $errors = $this->datavalidator->GetErrors();
            return
                array(
                    'state' => 'error',
                    'data' => reset($errors)
                );
        }

        $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'ORDERS WHERE id=' . $this->data['id']);
        $order = $temp->fetchAssoc('id');

        if (count($order) == 1)
        {
            $order = $order[$this->data['id']];

            $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'USERS WHERE id=' . $order['vlastnik']);
            $user = $temp->fetchAssoc('id');
            $user = $user[$order['vlastnik']];

            $markup = '<div><h4 class="left">' . $this->text('t30') . ': ' . $order['id'] . '</h4>
            <button class="ui-icon-close button right" onclick="cancelAction();">' . $this->text('close') . '</button>
            <div class="clear"></div>
            <table class="infotable">
            <tr>
            <th class="ui-corner-all ui-state-hover">' . $this->text('t10') . '</th>
            <th class="ui-corner-all ui-state-hover">' . $this->text('t1') . '</th>
            </tr>
            <tr>
            <td>
                ' . $order['meno'] . ' ' . $order['priezvisko'] . '<br />
                ' . (($order['firma'] == '') ? '' : $order['firma'] . '<br />') . '
                ' . (($order['telefon'] == '') ? '' : $order['telefon'] . '<br />') . '
                ' . (($order['email'] == '') ? '' : '<a href="mailto:' . $order['email'] . '">' . $order['email'] . '</a>') . '
            </td>
            <td>
                ' . (($user['fronttitles'] == '') ? '' : $user['fronttitles']) . ' ' . $user['firstname'] . ' ' . $user['surename'] . ' ' . (($user['endtitles'] == '') ? '' : $user['endtitles']) . '<br />
                ' . (($user['company'] == '') ? '' : $user['company'] . '<br />') . '
                ' . (($user['phone'] == '') ? '' : $user['phone'] . '<br />') . '
                ' . '<a href="mailto:' . $user['email'] . '">' . $user['email'] . '</a>' . '
            </td>
            </tr>
            </table>
            <h4>' . $this->text('t14') . '</h4>
            <table class="infotable">
            <tr>
            <th class="ui-corner-all ui-state-hover">' . $this->text('t31') . '</th>
            <th class="ui-corner-all ui-state-hover">' . $this->text('t32') . '</th>
            </tr>
            <tr>
            <td>' . $this->text('t13') . '</td>
            <td>' . $order['miesto'] . '</td>
            </tr>
            <tr>
            <td>' . $this->text('t15') . '</td>
            <td>' . $this->text('zateplenie.z_' . $order['typ_zateplenia']) . '</td>
            </tr>
            <tr>
            <td>' . $this->text('t16') . '</td>
            <td>' . $order['plocha'] . '</td>
            </tr>
            <tr>
            <td>' . $this->text('t17') . '</td>
            <td>' . $order['hrubka'] . '</td>
            </tr>';
            if ($order['ukoncene'] == '1')
            {
                $markup .= '<tr>
            <td>' . $this->text('t34') . '</td>
            <td>' . $order['sarza_setov'] . '</td>
            </tr>
            <tr>
            <td>' . $this->text('t35') . '</td>
            <td>' . $order['mnozstvo_mat'] . '</td>
            </tr>';
            }
            $markup .= '<tr>
            <td>' . $this->text('t25') . '</td>
            <td>' . date('d.m.Y', strtotime($order['datum_platnosti'])) . '</td>
            </tr>
            <tr>
            <td>' . $this->text('t3') . '</td>
            <td>' . date('d.m.Y', strtotime($order['datum_pridania'])) . '</td>
            </tr>
            <tr>
            <td>' . $this->text('t2') . '</td>
            <td>';
            if ($order['ukoncene'] == '1')
            {
                $markup .= $this->text('s_3') . '</td></tr>
                    <tr>
                    <td>' . $this->text('t5') . '</td>
                    <td>' . date('d.m.Y', strtotime($order['datum_ukoncenia']));
            } else {
                if (strtotime($order['datum_platnosti']) > time())
                {
                    $markup .= $this->text('s_2');
                } else {
                    $markup .= $this->text('s_1');
                }
            }
            $markup .= '</td>
            </tr>
            </table>';

            $files = scandir(FRONTEND_ABSDIRPATH . ORDERFILESPATH . $this->data['id']);
            if (count($files) > 2)
            {
                $markup .= '<h4>' . $this->text('t22') . '</h4>';
                $markup .= '<div class="ordergallery">';
                foreach ($files as $file)
                {
                    if ($file != '..' && $file != '.' && substr($file, 0, 3) != 'tn_')
                    {
                        $markup .=
                            '<a target="_blank" href="' . PAGE_URL . ORDERFILESPATH . $order['id'] . '/' . $file . '">' .
                            '<img src="' . PAGE_URL . ORDERFILESPATH . $order['id'] . '/tn_' . $file . '" />' .
                            '</a>';
                    }
                }
                $markup .= '</div><div class="clear"></div>';
            }

            $markup .=
                '<button class="ui-icon-close button right" onclick="cancelAction();">' . $this->text('close') . '</button>' .
                '<div class="clear"></div>';
            return
                array(
                    'state' => 'ok',
                    'data' => $markup
                );
        } else {
            return
                array(
                    'state' => 'error',
                    'data' => $this->text('e3')
                );
        }
    }

    // Update
    public function updateItem()
    {
        if ($_SESSION[PAGE_SESSIONID]['privileges']['orders'][2] == '0'
            && $_SESSION[PAGE_SESSIONID]['privileges']['orders'][3] == '0'
        ) {
            die($this->text('dont_have_permission'));
        }

        $result = $this->checkData();
        if ($result !== true)
        {
            return $result;
        }
        $this->datavalidator->addValidation('id', 'req', $this->text('e1'));
        $this->datavalidator->addValidation('id', 'numeric', $this->text('e2'));

        //if user can change article ownership set values
        if ($_SESSION[PAGE_SESSIONID]['privileges']['orders'][4] == '0')
        {
            unset($this->data['vlastnik']);
        } else {
            $this->datavalidator->addValidation('vlastnik', 'req', $this->text('e28'));
            $this->datavalidator->addValidation('vlastnik', 'numeric', $this->text('e29'));
        }

        $result = $this->datavalidator->ValidateData($this->data);
        if (!$result)
        {
            $errors = $this->datavalidator->GetErrors();
            return
                array(
                    'state' => 'error',
                    'data' => reset($errors)
                );
        }

        $id = $this->data['id'];
        unset($this->data['id']);

        $this->data['typ_zateplenia'] = str_replace('z_', '', $this->data['typ_zateplenia']);
        $this->data['datum_zmeny'] = date("Y-m-d");
        $this->data['datum_platnosti'] = date('Y-m-d', strtotime($this->data['datum_platnosti']));

        $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "ORDERS SET ", $this->data, "WHERE id='" . $id . "'");

        return (
            ($temp == 0 || $temp == 1)
                ? array(
                    'state' => 'highlight',
                    'data' => $this->text('h5')
                )
                : array(
                    'state' => 'error',
                    'data' => $this->text('e26')
                )
        );
    }

    // Delete
    public function deleteItem()
    {
        if ($_SESSION[PAGE_SESSIONID]['privileges']['orders'][2] == '0'
            && $_SESSION[PAGE_SESSIONID]['privileges']['orders'][3] == '0'
        ) {
            die($this->text('dont_have_permission'));
        }

        $this->datavalidator->addValidation('id', 'req', $this->text('e1'));
        $this->datavalidator->addValidation('id', 'numeric', $this->text('e2'));

        $result = $this->datavalidator->ValidateData($this->data);
        if (!$result)
        {
            $errors = $this->datavalidator->GetErrors();
            return
                array(
                    'state' => 'error',
                    'data' => reset($errors)
                );
        }

        $temp = dibi::query('DELETE FROM ' . DB_TABLEPREFIX . 'ORDERS WHERE id=' . $this->data['id']);
        $returnvalue = array();
        if ($temp)
        {
            $returnvalue = array(
                'state' => 'highlight',
                'data' => $this->text('h7')
            );
            if (file_exists(FRONTEND_ABSDIRPATH . ORDERFILESPATH . $this->data['id']))
            {
                if (!$this->destroyDir(FRONTEND_ABSDIRPATH . ORDERFILESPATH . $this->data['id']))
                {
                    $returnvalue['data'] .= '<br />' . $this->text('e31');
                }
            }
        } else {
            $returnvalue = array(
                'state' => 'error',
                'data' => $this->text('e32')
            );
        }

        return $returnvalue;
    }

    // Set vlastnik
    public function acquireItem()
    {
        if ($_SESSION[PAGE_SESSIONID]['privileges']['orders'][0] == '0')
            die($this->text('dont_have_permission'));

        $this->datavalidator->addValidation('id', 'req', $this->text('e1'));
        $this->datavalidator->addValidation('id', 'numeric', $this->text('e2'));

        $result = $this->datavalidator->ValidateData($this->data);
        if (!$result)
        {
            $errors = $this->datavalidator->GetErrors();
            return array('state' => 'error', 'data' => reset($errors));
        }

        $id = $this->data['id'];
        unset($this->data['id']);

        $this->data['datum_platnosti'] = date('Y-m-d', strtotime('+1 month'));
        $this->data['datum_zmeny'] = date("Y-m-d");
        $this->data['vlastnik'] = $_SESSION[PAGE_SESSIONID]['id'];

        $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "ORDERS SET ", $this->data, "WHERE id='" . $id . "'");

        return (
            ($temp == 0 || $temp == 1)
                ? array(
                    'state' => 'highlight',
                    'data' => $this->text('h6')
                )
                : array(
                    'state' => 'error',
                    'data' => $this->text('e30')
                )
        );
    }

    // Set ukoncene
    public function finishItem()
    {
        if ($_SESSION[PAGE_SESSIONID]['privileges']['orders'][0] == '0')
        {
            die($this->text('dont_have_permission'));
        }

        $this->datavalidator->addValidation('id', 'req', $this->text('e1'));
        $this->datavalidator->addValidation('id', 'numeric', $this->text('e2'));
        $this->datavalidator->addValidation('sarza_setov', 'req', $this->text('e35'));
        $this->datavalidator->addValidation('mnozstvo_mat', 'req', $this->text('e36'));

        $result = $this->datavalidator->ValidateData($this->data);
        if (! $result)
        {
            $errors = $this->datavalidator->GetErrors();
            return
                array(
                    'state' => 'error',
                    'data' => reset($errors),
                    'field' => key($errors)
                );
        }

        $id = $this->data['id'];
        unset($this->data['id']);

        $this->data['datum_ukoncenia'] = date("Y-m-d");
        $this->data['ukoncene'] = '1';

        $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "ORDERS SET ", $this->data, "WHERE id='" . $id . "'");

        return (
            ($temp == 0 || $temp == 1)
                ? array(
                    'state' => 'highlight',
                    'data' => $this->text('h8')
                )
                : array(
                    'state' => 'error',
                    'data' => $this->text('e33')
                )
            );
    }

    // Validate
    private function checkData()
    {
        $this->datavalidator->addValidation('meno', 'req', $this->text('e4'));
        $this->datavalidator->addValidation('meno', 'maxlen=50', $this->text('e5'));
        $this->datavalidator->addValidation('priezvisko', 'req', $this->text('e6'));
        $this->datavalidator->addValidation('priezvisko', 'maxlen=50', $this->text('e7'));
        $this->datavalidator->addValidation('firma', 'maxlen=50', $this->text('e8'));
        $this->datavalidator->addValidation('email', 'email', $this->text('e9'));
        $this->datavalidator->addValidation('email', 'maxlen=100', $this->text('e10'));
        $this->datavalidator->addValidation('telefon', 'regexp=/^\+?\d+$/', $this->text('e11'));
        $this->datavalidator->addValidation('telefon', 'maxlen=20', $this->text('e12'));
        $this->datavalidator->addValidation('miesto', 'req', $this->text('e13'));
        $this->datavalidator->addValidation('miesto', 'maxlen=100', $this->text('e14'));
        $this->datavalidator->addValidation('typ_zateplenia', 'req', $this->text('e15'));
        $this->datavalidator->addValidation('typ_zateplenia', 'regexp=/^z_\d+$/', $this->text('e16'));
        $this->datavalidator->addValidation('plocha', 'req', $this->text('e17'));
        $this->datavalidator->addValidation('plocha', 'gt=0', $this->text('e18'));
        $this->datavalidator->addValidation('plocha', 'regexp=/^\d{1,8}$/', $this->text('e19'));
        $this->datavalidator->addValidation('hrubka', 'req', $this->text('e20'));
        $this->datavalidator->addValidation('hrubka', 'gt=0', $this->text('e21'));
        $this->datavalidator->addValidation('hrubka', 'regexp=/^\d{1,8}$/', $this->text('e22'));
        $this->datavalidator->addValidation('datum_platnosti', 'req', $this->text('e23'));
        $this->datavalidator->addValidation('datum_platnosti', 'regexp=/^\d{2}\.\d{2}\.\d{4}$/', $this->text('e24'));
        $this->datavalidator->addValidation('sarza_setov', 'regexp=/^#\d+(, #\d+)*$/', $this->text('e25'));
        $this->datavalidator->addValidation('sarza_setov', 'maxlen=100', $this->text('e37'));
        $this->datavalidator->addValidation('mnozstvo_mat', 'regexp=/^\d{1,5}$/', $this->text('e34'));

        $result = $this->datavalidator->ValidateData($this->data);
        if (!$result)
        {
            $errors = $this->datavalidator->GetErrors();
            return
                array(
                    'state' => 'error',
                    'data' => reset($errors),
                    'field' => key($errors)
                );
        } else {
            return true;
        }
    }

    /*
     * returning form
     */
    private function getForm($formaction = 'add', $data = array())
    {
        if (isset($data['ukoncene']) && $data['ukoncene'] == '1')
        {
            $markup = '
                <button class="ui-icon-close button right" onclick="cancelAction();">' . $this->text('close') . '</button>
                <div class="clear"></div>
                <fieldset>
                <legend>' . $this->text('t23') . ':</legend>
                <div class="help"><p>' . $this->text('h1') . '</p></div>
                <iframe src="modules/orders/filemanager.php" frameborder="0" style="width:50%;height:440px;display: block;margin:0 auto;"/>
                </fieldset>
                <button class="ui-icon-close button right" onclick="cancelAction();">' . $this->text('close') . '</button>
                <div class="clear"></div>';
        } else {
            $markup = '
                <button class="ui-icon-cancel button right" onclick="cancelAction();">' . $this->text('cancel') . '</button>
                <button class="ui-icon-disk button right" onclick="' . $formaction . 'Item(' . ((isset($data['id'])) ? $data['id'] : '') . ');">' . $this->text('ok') . '</button>
                <div class="clear"></div>
                <fieldset>
                <legend>' . $this->text('t10') . ':</legend>
                <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="meno" id="meno" value="' . ((isset($data['meno'])) ? $data['meno'] : '') . '" placeholder="' . $this->text('t11') . '" />
                <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="priezvisko" id="priezvisko" value="' . ((isset($data['priezvisko'])) ? $data['priezvisko'] : '') . '" placeholder="' . $this->text('t12') . '" />
                <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="firma" id="firma" value="' . ((isset($data['firma'])) ? $data['firma'] : '') . '" placeholder="' . $this->text('t27') . '" />
                </fieldset>
                <fieldset>
                <legend>' . $this->text('t28') . ':</legend>
                <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedleft" type="text" name="email" id="email" value="' . ((isset($data['email'])) ? $data['email'] : '') . '" placeholder="' . $this->text('email') . ' (' . $this->text('t18') . ')" />
                <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="telefon" id="telefon" value="' . ((isset($data['telefon'])) ? $data['telefon'] : '') . '" placeholder="' . $this->text('t29') . ' (' . $this->text('t18') . ')" />
                </fieldset>
                <fieldset>
                <legend>' . $this->text('t13') . ':</legend>
                <input class="ui-autocomplete-input ui-widget-content ui-corner-all" type="text" name="miesto" id="miesto" value="' . ((isset($data['miesto'])) ? $data['miesto'] : '') . '" placeholder="' . $this->text('t19') . '" />
                </fieldset>
                <fieldset>
                <legend>' . $this->text('t15') . ':</legend>' .
                $this->getTypCombobox((isset($data['typ_zateplenia'])) ? $data['typ_zateplenia'] : '') . '
                </fieldset>
                <fieldset>
                <legend>' . $this->text('t14') . ':</legend>
                <label for="plocha">' . $this->text('t16') . ': </label><input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="plocha" id="plocha" value="' . ((isset($data['plocha'])) ? $data['plocha'] : '') . '" placeholder="' . $this->text('t16') . '" />
                <label for="hrubka">' . $this->text('t17') . ': </label><input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="hrubka" id="hrubka" value="' . ((isset($data['hrubka'])) ? $data['hrubka'] : '') . '" placeholder="' . $this->text('t17') . '" />
                </fieldset>
                <fieldset>
                <legend>' . $this->text('t4') . ':</legend>
                <div class="help"><p>' . $this->text('h3') . '</p></div>' .
                $this->text('t25') . ': <input class="date ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="datum_platnosti" id="datum_platnosti" value="' .
                    (isset($data['datum_platnosti'])
                        ? date('d.m.Y', strtotime($data['datum_platnosti']))
                        : date('d.m.Y', strtotime('+1 month'))
                    ) . '" />
                </fieldset>';
            if ($_SESSION[PAGE_SESSIONID]['privileges']['orders'][4] == '1'
                || ($formaction == 'update'
                && $_SESSION[PAGE_SESSIONID]['id'] == $data['vlastnik']))
            {
                $markup .=
                    '<fieldset>
                    <legend>' . $this->text('t26') . ':</legend>
                    <div class="help"><p>' . $this->text('h4') . '</p></div>' .
                    $this->getUsersCombobox(
                        isset($data['vlastnik'])
                            ? $data['vlastnik']
                            : $_SESSION[PAGE_SESSIONID]['id']
                        ) .
                    '</fieldset>';
            }
            if ($formaction == 'update')
            {
                $markup .=
                    '<fieldset>' .
                    '<legend>' .
                        $this->text('t23') . ':</legend>' .
                    '<div class="help"><p>' .
                        $this->text('h9') . '</p></div>' .
                    '<iframe src="modules/orders/filemanager.php" frameborder="0" style="width:700px;height:440px" />' .
                    '</fieldset>' .
                    '<fieldset>' .
                    '<legend>' .
                        $this->text('t24') . ':</legend>' .
                    '<div class="help"><p>' .
                        $this->text('h2') . '</p></div>' .
                    '<input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="sarza_setov" id="sarza_setov" value="' .
                        ((isset($data['sarza_setov'])) ? $data['sarza_setov'] : '') . '" />' .
                    '<label for="sarza_setov"> - ' .
                        $this->text('t34') . '</label><br />' .
                    '<input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="mnozstvo_mat" id="mnozstvo_mat" value="' .
                        ((isset($data['mnozstvo_mat'])) ? $data['mnozstvo_mat'] : '') . '" />' .
                    '<label for="mnozstvo_mat"> - ' .
                        $this->text('t35') . '</label><br /><br />' .
                    '<button class="ui-icon-check button" onclick="finishItem(' . $data['id'] . ');">' .
                        $this->text('t20') . '</button>' .
                    '</fieldset>';
            }
            $markup .=
                '<button class="ui-icon-cancel button right" onclick="cancelAction();">' .
                    $this->text('cancel') . '</button>' .
                '<button class="ui-icon-disk button right" onclick="' . $formaction . 'Item(' .
                    (isset($data['id'])
                        ? $data['id']
                        : ''
                    ) . ');">' .
                    $this->text('ok') . '</button>' .
                '<div class="clear"></div>';
        }
        return $markup;
    }

    /*
     * Returns the menucategories combobox
     */
    private function getTypCombobox($active = 'x')
    {
        $markup = '<select name="typ_zateplenia" id="typ_zateplenia" class="ui-autocomplete-input ui-widget-content ui-corner-all">';
        foreach ($this->text('zateplenie') as $id => $name)
        {
            $markup .= '<option value="' . $id . '"' . (($id == 'z_' . $active) ? ' selected="selected"' : '') . '>' . $name . '</option>';
        }
        $markup .= '</select>';
        return $markup;
    }

    //generates the userselector combobox
    private function getUsersCombobox($activeuser = 'x', $groups = array())
    {
        return
            '<select name="vlastnik" id="vlastnik" class="ui-autocomplete-input ui-widget-content ui-corner-all">' .
            $this->getUsersComboboxElements($activeuser) .
            '</select>';
    }

    /*
     * generates the layout combobox
     */
    private function sendAddEmail($order)
    {
        //getting recipients
        $groups = $this->configuration->getSetting('ID_ORDERGROUP');
        if (strlen($groups) > 0)
        {
            $groups = explode(',', $groups);
        } else {
            $groups = array();
        }
        $recipients = $this->registry['users']->getUsersListAllprop($groups);

        //getting owner of the new order
        $owner = $this->registry['users']->getUser($order['vlastnik']);

        foreach ($recipients as $recipient)
        {
            if ($recipient['newsletter'] == '1')
            {
                $subject = sprintf($this->text('m0'), $order['id']);
                $body = sprintf($this->text('m1'), ((($recipient['fronttitles'] == '') ? '' : $recipient['fronttitles']) . ' ' . $recipient['firstname'] . ' ' . $recipient['surename'] . ' ' . (($recipient['endtitles'] == '') ? '' : $recipient['endtitles'])), $order['id']);
                $body .= '<table border="1" cellpadding="5">
                <tr>
                <th>' . $this->text('t10') . '</th>
                <th>' . $this->text('t1') . '</th>
                </tr>
                <tr>
                <td>
                    ' . $order['meno'] . ' ' . $order['priezvisko'] . '<br />
                    ' . (($order['firma'] == '') ? '' : $order['firma'] . '<br />') . '
                    ' . (($order['telefon'] == '') ? '' : $order['telefon'] . '<br />') . '
                    ' . (($order['email'] == '') ? '' : '<a href="mailto:' . $order['email'] . '">' . $order['email'] . '</a>') . '
                </td>
                <td>
                    ' . (($owner['fronttitles'] == '') ? '' : $owner['fronttitles']) . ' ' . $owner['firstname'] . ' ' . $owner['surename'] . ' ' . (($owner['endtitles'] == '') ? '' : $owner['endtitles']) . '<br />
                    ' . (($owner['company'] == '') ? '' : $owner['company'] . '<br />') . '
                    ' . (($owner['phone'] == '') ? '' : $owner['phone'] . '<br />') . '
                    ' . '<a href="mailto:' . $owner['email'] . '">' . $owner['email'] . '</a>' . '
                </td>
                </tr>
                </table>
                <h4>' . $this->text('t14') . '</h4>
                <table border="1" cellpadding="5">
                <tr>
                <th>' . $this->text('t31') . '</th>
                <th>' . $this->text('t32') . '</th>
                </tr>
                <tr>
                <td>' . $this->text('t13') . '</td>
                <td>' . $order['miesto'] . '</td>
                </tr>
                <tr>
                <td>' . $this->text('t15') . '</td>
                <td>' . $this->text('zateplenie.' . $order['typ_zateplenia']) . '</td>
                </tr>
                <tr>
                <td>' . $this->text('t16') . '</td>
                <td>' . $order['plocha'] . '</td>
                </tr>
                <tr>
                <td>' . $this->text('t17') . '</td>
                <td>' . $order['hrubka'] . '</td>
                </tr>
                <tr>
                <td>' . $this->text('t25') . '</td>
                <td>' . date('d.m.Y', strtotime($order['datum_platnosti'])) . '</td>
                </tr>
                </table><br /><br />
                <hr /><small>' . $this->text('m2') . '<a href="' . PAGE_URL . ADMINDIRNAME . '">' . PAGE_URL . ADMINDIRNAME . '</a></small>';

                ECMSMailer::sendMail($owner['email'], $recipient['email'], $subject, $body);
            }
        }
    }

    /*
     * returns the users combobox list
     */
    public function getUsersComboboxElements($activeuser = 'x')
    {
        $markup = '';

        $groups = $this->configuration->getSetting('ID_ORDERGROUP');
        if (strlen($groups) > 0)
        {
            $groups = explode(',', $groups);
        } else {
            $groups = array();
        }

        $users = $this->registry['users']->getUsersList($groups);

        foreach ($users as $id => $name)
        {
            $markup .= '<option value="' . $id . '"' . (($id == $activeuser) ? ' selected="selected"' : '') . '>' . $name . '</option>';
        }

        return $markup;
    }

    //assign orders from to
    public function preassignOrders($from = array(), $to = 0)
    {
        $query = "UPDATE " . DB_TABLEPREFIX . "ORDERS SET vlastnik=" . $to . " WHERE vlastnik";
        if (is_array($from))
        {
            $query .= ' IN(' . implode(',', $from) . ')';
        } else {
            $query .= '=' . $from;
        }
        $temp = dibi::query($query);
        if ($temp == 0 || $temp == 1)
        {
            return true;
        } else {
            return false;
        }
    }
}
