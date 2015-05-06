<?php

require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-winner-db.php';

class KS_Winners_List_Table extends WP_List_Table
{
    public function __construct($args = array())
    {
        $this->contest_id = $args['contest_id'];

        parent::__construct(array(
            'plural' => 'winners',
            'singular' => 'winner',
            'screen' => null
        ));
    }

    public function current_action() {
        if (isset($_REQUEST['downloadcsv'])) {
            return 'downloadcsv';
        }

        return parent::current_action();
    }

    public function get_bulk_actions()
    {
        return array(
//            'notify' => 'Notify Winners'
        );
    }

    public function extra_tablenav($which)
    {
        if ($which == 'top') {
            echo '<div class="alignleft actions">';
            echo '<a href="'.admin_url('admin.php?page=ks-giveaways&action=view&id='.$this->contest_id).'&noheader=true&downloadcsv=true" class="button button-primary">Download CSV</a>';
            echo '</div>';
        }
    }

    public function single_row($item)
    {
        static $row_class = '';
    		$row_class = ($row_class == '' ? ' class="alternate"' : '');

        echo '<tr' . $row_class . '>';
        $this->single_row_columns($item);
        echo '</tr>';
    }

    public function prepare_items()
    {
        $paged = max(1, isset($_REQUEST['paged']) ? (int) $_REQUEST['paged'] : 1);
        $per_page = 5;

        $offset = ($paged - 1) * $per_page;

        $total = KS_Winner_DB::get_total($this->contest_id);
        $results = KS_Winner_DB::get_results($this->contest_id, $offset, $per_page);

        $num_winners = KS_Helper::get_winner_count($this->contest_id);
        $max = min($paged * $per_page, $num_winners);
        for ($i = count($results); $i < $max - $offset; $i++) {
            $results[] = array(
                'email_address' => ''
            );
        }

        for ($i = 0; $i < count($results); $i++) {
            $results[$i]['position'] = $offset+$i+1;
        }

        $this->items = $results;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->set_pagination_args(array(
            'total_items' => max($total, $num_winners),
            'per_page' => $per_page
        ));
    }

    public function column_default($item, $column_name)
    {
        switch($column_name) {
            case 'avatar':
                $ret = '';
                if (isset($item['ID']) && $item['ID']) {
                    $url = $item['winner_avatar'];
                    if (!$url) {
                      $url = plugins_url('assets/images/user-avatar.jpg', KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR);
                    }
                    $ret = sprintf('<img alt="Click here to change" title="Click here to change" id="winner_avatar_%d" src="%s" />', $item['ID'], $url);
                }
                return $ret;

            case 'name':
                $ret = '';
                if (isset($item['ID']) && $item['ID']) {
                    $name = $item['winner_name'];
                    if (!$name) {
                        $name = 'Click here to change';
                    }
                    $ret = sprintf('<a href="javascript:void(0)" id="winner_name_%d">%s</a>', $item['ID'], $name);
                }
                return $ret;

            case 'email_address':
                $ret = $item[$column_name];
                return $ret;

            case 'status':
                if (!isset($item[$column_name]) || !$item[$column_name]) {
                    return '';
                }

                return ucwords($item[$column_name]);

            case 'actions':
                $form = '<form method="post" style="padding:0;margin:0;display:inline;">%s</form>';
                $ret = array();
                if (isset($item['ID']) && $item['ID']) {
                    $form = '<form method="post" style="padding:0;margin:0;display:inline;"><input type="hidden" name="winner_id" value="'.$item['ID'].'" />%s</form>';

                    if (in_array($item['status'], array('unconfirmed'))) {
                        $ret[] = '<button name="post_action" value="confirm" class="button button-small" title="Mark status as confirmed">Confirm</button>';
                    }
                    $ret[] = '<button name="post_action" value="remove" class="button button-small" title="Remove winner">Remove</button>';
                    $ret[] = '<button name="post_action" value="redraw" class="button button-small" title="Redraw winner">Redraw</button>';
                    if (in_array($item['status'], array('confirmed','notified'))) {
                        $ret[] = '<button name="post_action" value="notify" class="button button-small" title="Notify winner via email">Notify</button>';
                    }
                } else {
                    $ret[] = '<button name="post_action" value="draw" class="button button-small" title="Draw winner">Draw</button>';
                }

                if (count($ret) > 1) {
                    return sprintf($form, '<div class="button-group">' . implode('', $ret) . '</div>');
                } else {
                    return sprintf($form, implode('&nbsp;', $ret));
                }

            case 'date_drawn':
                if (!isset($item[$column_name]) || !$item[$column_name])
                    return 'N/A';

                return date_i18n(get_option('date_format').' '.get_option('time_format'), strtotime(get_date_from_gmt($item[$column_name])));

            default: return $item[$column_name];
        }
    }

    public function get_columns()
    {
        $columns = array();
        $columns['position'] = '#';
        $columns['avatar'] = 'Avatar';
        $columns['email_address'] = 'Email';
        $columns['name'] = 'Name';
        $columns['date_drawn'] = 'Date Drawn';
        $columns['status'] = 'Status';
        $columns['actions'] = 'Actions';

        return $columns;
    }

    public function no_items()
    {
        _e('No winners found.');
    }

}