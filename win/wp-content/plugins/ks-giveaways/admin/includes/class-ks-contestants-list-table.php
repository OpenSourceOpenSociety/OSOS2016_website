<?php

require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-contestant-db.php';

class KS_Contestants_List_Table extends WP_List_Table
{
    public function __construct($args = array())
    {
        $this->contest_id = $args['contest_id'];

        parent::__construct(array(
            'plural' => 'contestants',
            'singular' => 'contestant',
            'screen' => null
        ));
    }

    public function current_action() {
        if (isset($_REQUEST['downloadcsv'])) {
            return 'downloadcsv';
        }

        return parent::current_action();
    }

    public function extra_tablenav($which)
    {
        if ($which == 'top') {
            echo '<div class="alignleft actions">';
            echo '<a href="'.admin_url('admin.php?page=ks-giveaways&action=contestants&id='.$this->contest_id).'&noheader=true&downloadcsv=true" class="button button-primary">Download CSV</a>';
            echo '</div>';
        }
    }

    public function prepare_items()
    {
        $id = (int) $_REQUEST['id'];
        $paged = max(1, isset($_REQUEST['paged']) ? (int) $_REQUEST['paged'] : 1);
        $per_page = 10;
        $orderby = isset($_REQUEST['orderby']) ? $_REQUEST['orderby'] : 'date_added';
        $order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 'desc';

        if (!in_array($orderby, array('date_added', 'num_entries', 'email_address'))) {
            $orderby = 'date_added';
        }

        if (!in_array($order, array('asc','desc'))) {
            $order = 'desc';
        }

        $offset = ($paged - 1) * $per_page;

        $total = KS_Contestant_DB::get_total($id);
        $results = KS_Contestant_DB::get_results($id, $offset, $per_page, $orderby, $order);

        $this->items = $results;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->set_pagination_args(array(
            'total_items' => $total,
            'per_page' => $per_page
        ));
    }

    public function column_default($item, $column_name)
    {
        switch($column_name) {
            case 'date_added': return date_i18n(get_option('date_format').' '.get_option('time_format'), strtotime(get_date_from_gmt($item[$column_name])));
            case 'status': return ucwords($item['status']);
            case 'actions':
                $form = '<form method="post" style="padding:0;margin:0;display:inline;"><input type="hidden" name="contestant_id" value="'.$item['ID'].'" />%s</form>';
                $ret = array();
                $ret[] = '<button name="post_action" value="remove" class="button button-small" title="Remove contestant from giveaway" onclick="return confirm(\'Are you sure you wan\\\'t to remove this contestant?\');">Remove</button>';
                $ret[] = '<button name="post_action" value="resend" class="button button-small" title="Resend confirmation email">Resend</button>';
                if (count($ret) > 1) {
                    return sprintf($form, '<div class="button-group">' . implode('', $ret) . '</div>');
                } else {
                    return sprintf($form, implode('&nbsp;', $ret));
                }

            default: return $item[$column_name];
        }
    }

    public function get_columns()
    {
        $columns = array();
        $columns['email_address'] = 'Email';
        $columns['num_entries'] = 'Entries';
        $columns['date_added'] = 'Date Entered';
        $columns['status'] = 'Status';
        $columns['actions'] = 'Actions';

        return $columns;
    }

    public function get_sortable_columns()
    {
        return array(
            'email_address' => array('email_address', false),
            'num_entries' => array('num_entries', false),
            'date_added' => array('date_added', true)
        );
    }

    public function no_items()
    {
        _e('No contestants found.');
    }
}