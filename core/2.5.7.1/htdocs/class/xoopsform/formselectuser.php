<?php
/**
 * user select with page navigation
 *
 * limit: Only work with javascript enabled
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @subpackage      form
 * @since           2.0.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: formselectuser.php 12537 2014-05-19 14:19:33Z beckmi $
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');

xoops_load('XoopsFormElementTray');
xoops_load('XoopsFormSelect');

/**
 * User Select field
 */
class XoopsFormSelectUser extends XoopsFormElementTray
{
    /**
     * Constructor
     *
     * @param string $caption      form element caption
     * @param string $name         form element name
     * @param bool   $include_anon Include user "anonymous"?
     * @param mixed  $value        Pre-selected value (or array of them).
     *                             For an item with massive members, such as "Registered Users", "$value"
     *                             should be used to store selected temporary users only instead of all
     *                             members of that item
     * @param int    $size         Number or rows. "1" makes a drop-down-list.
     * @param bool   $multiple     Allow multiple selections?
     */
    public function XoopsFormSelectUser($caption, $name, $include_anon = false, $value = null, $size = 1, $multiple = false)
    {
        /**
         * @var mixed array|false - cache any result for this session.
         * Some modules use multiple copies of this element on a single page, so this call will
         * be made multiple times. This is only used when $value is null.
         * @todo this should be replaced with better interface, with autocomplete style search
         * and user specific MRU cache
         */
        static $querycache = false;

        /**
         * @var int - limit to this many rows
         */
        $limit = 200;

        /**
         * @var string - cache time to live - will be interpreted by strtotime()
         */
        $cachettl = '+5 minutes';

        /**
         * @var string - cache key
         */
        $cachekey = 'formselectuser';

        $select_element = new XoopsFormSelect('', $name, $value, $size, $multiple);
        if ($include_anon) {
            $select_element->addOption(0, $GLOBALS['xoopsConfig']['anonymous']);
        }
        $member_handler =& xoops_gethandler('member');
        $value = is_array($value) ? $value : (empty($value) ? array() : array($value));
        if (count($value) > 0) {
            // simple case - we have a set of uids in $values
            $criteria = new Criteria('uid', '(' . implode(',', $value) . ')', 'IN');
            $criteria->setSort('uname');
            $criteria->setOrder('ASC');
            $users = $member_handler->getUserList($criteria);
        } else {
            // open ended case - no selection criteria
            // we will always cache this version to reduce expense
            if (empty($querycache)) {
                XoopsLoad::load('XoopsCache');
                $querycache = XoopsCache::read($cachekey);
                if ($querycache===false) {
                    $criteria = new CriteriaCompo();
                    if ($limit <= $member_handler->getUserCount()) {
                        // if we have more than $limit users, we will select who to show based on last_login
                        $criteria->setLimit($limit);
                        $criteria->setSort('last_login');
                        $criteria->setOrder('DESC');
                    } else {
                        $criteria->setSort('uname');
                        $criteria->setOrder('ASC');
                    }
                    $querycache = $member_handler->getUserList($criteria);
                    asort($querycache);
                    XoopsCache::write($cachekey, $querycache, $cachettl); // won't do anything different if write fails
                }
            }
            $users = $querycache;
        }
        $select_element->addOptionArray($users);
        if ($limit>count($users)) {
            $this->XoopsFormElementTray($caption, "", $name);
            $this->addElement($select_element);

            return;
        }

        xoops_loadLanguage('findusers');
        $js_addusers = "<script type='text/javascript'>
            function addusers(opts)
            {
                var num = opts.substring(0, opts.indexOf(':'));
                opts = opts.substring(opts.indexOf(':')+1, opts.length);
                var sel = xoopsGetElementById('" . $name . "');
                var arr = new Array(num);
                for (var n=0; n < num; n++) {
                    var nm = opts.substring(0, opts.indexOf(':'));
                    opts = opts.substring(opts.indexOf(':')+1, opts.length);
                    var val = opts.substring(0, opts.indexOf(':'));
                    opts = opts.substring(opts.indexOf(':')+1, opts.length);
                    var txt = opts.substring(0, nm - val.length);
                    opts = opts.substring(nm - val.length, opts.length);
                    var added = false;
                    for (var k = 0; k < sel.options.length; k++) {
                        if (sel.options[k].value == val) {
                            added = true;
                            break;
                        }
                    }
                    if (added == false) {
                        sel.options[k] = new Option(txt, val);
                        sel.options[k].selected = true;
                    }
                }

                return true;
            }
            </script>";
        $token = $GLOBALS['xoopsSecurity']->createToken();
        $action_tray = new XoopsFormElementTray("", " | ");
        $action_tray->addElement(new XoopsFormLabel('', '<a href="#" onclick="var sel = xoopsGetElementById(\'' . $name . '\');for (var i = sel.options.length-1; i >= 0; i--) {if (!sel.options[i].selected) {sel.options[i] = null;}}; return false;">' . _MA_USER_REMOVE . "</a>"));
        $action_tray->addElement(new XoopsFormLabel('', '<a href="#" onclick="openWithSelfMain(\'' . XOOPS_URL . '/include/findusers.php?target=' . $name . '&amp;multiple=' . $multiple . '&amp;token=' . $token . '\', \'userselect\', 800, 600, null); return false;" >' . _MA_USER_MORE . "</a>" . $js_addusers));
        $this->XoopsFormElementTray($caption, '<br /><br />', $name);
        $this->addElement($select_element);
        $this->addElement($action_tray);
    }
}
