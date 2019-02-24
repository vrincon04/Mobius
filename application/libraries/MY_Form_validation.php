<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

    protected $months = [
        'es' => [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre',
            'enero',
            'febrero',
            'marzo',
            'abril',
            'mayo',
            'junio',
            'julio',
            'agosto',
            'septiembre',
            'octubre',
            'noviembre',
            'diciembre'
        ]
    ];

    protected $monthsReplace = [
        'January',
        'February',
        'March',
        'April ',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December',
        'January',
        'February',
        'March',
        'April ',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    ];

    /**
     * @param $str
     * @param $field
     * @return bool
     */
    public function exist($str, $field)
    {
        sscanf($field, '%[^.].%[^.]', $table, $field);
        return isset($this->CI->db)
            ? ($this->CI->db->limit(1)->get_where($table, array($field => $str))->num_rows() > 0)
            : FALSE;
    }

    /**
     * @param string $str
     * @param string $field
     * @return bool
     */
    public function is_unique($str, $field)
    {
        $temp = explode('.', $field);

        if ( ! isset($this->CI->db) ) { return FALSE; }

        if ( count($temp) == 3 )
        {
            $table = $temp[0];
            $field = $temp[1];
            $id = $temp[2];
            $query = $this->CI->db->limit(1)->where(array($field => $str))->where("id != {$id}")->get($table);
        }
        else
        {
            $table = $temp[0];
            $field = $temp[1];
            $query = $this->CI->db->limit(1)->get_where($table, array($field => $str));
        }

        return $query->num_rows() === 0;
    }

    public function two_cash_register_opened($str, $field)
    {
        sscanf($field, '%[^.].%[^.]', $table, $field);
        return isset($this->CI->db)
            ? ($this->CI->db->limit(1)->get_where($table, array($field => $str, 'status' => 'open'))->num_rows() == 0)
            : FALSE;
    }

    /**
     * [prep_password_hash description]
     * @param string $password
     * @return bool|mixed|string
     */
    public function prep_password_hash($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /** 
     * [prep_date_formart description]
     * @param string $date
     * @return string
    */
    public function prep_date_formart($string)
    {
        echo $string;
        $date = DateTime::createFromFormat("d F Y", str_replace($this->months[$this->CI->session->userdata('lang')], $this->monthsReplace, $string));
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Prep currency format
     * @param string $number
     * @return string
     */
    public function prep_currency_format($number)
    {
        $number = preg_replace("/[^0-9.-]/", "", $number);
        return number_format($number, 2, '.', '');
    }

    /**
	 * Re-populate the validation_data array with our finalized and processed data
	 *
	 * @return	void
	 */
	protected function _reset_data_array()
	{
		foreach ($this->_field_data as $field => $row)
		{
			if ($row['postdata'] !== NULL)
			{
				if ($row['is_array'] === FALSE)
				{
					isset($this->validation_data[$field]) && $this->validation_data[$field] = $row['postdata'];
				}
				else
				{
					// start with a reference
					$post_ref =& $this->validation_data;

					// before we assign values, make a reference to the right POST key
					if (count($row['keys']) === 1)
					{
						$post_ref =& $post_ref[current($row['keys'])];
					}
					else
					{
						foreach ($row['keys'] as $val)
						{
							$post_ref =& $post_ref[$val];
						}
					}

					$post_ref = $row['postdata'];
				}
			}
		}
	}

    /**
     * @param $data
     * @return $this
     */
    public function set_data_ref(&$data)
	{
		$this->validation_data = &$data;

		return $this;
	}

    /**
     * @param string $group
     * @return bool
     */
    public function run($group = '')
    {
        $result = parent::run($group);

        $result && ! empty($this->validation_data) && $this->_reset_data_array();

        return $result;
    }

}
