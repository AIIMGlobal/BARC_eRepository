<?php
    
    function conver_to_bn_number($number)
    {
        $numbers = array('০','১','২','৩','৪','৫','৬','৭','৮','৯');
        return strtr($number,$numbers);
    }
    
    function conver_to_bn_date($data)
    {
        $numbers = array('০','১','২','৩','৪','৫','৬','৭','৮','৯');
        $return_data = '';

        if ($data) {
            foreach (str_split($data) as $key => $value) {
                if (is_numeric($value)) {
                    $return_data = $return_data.''.strtr($value,$numbers);
                } else {
                    $return_data = $return_data.''.$value;
                }
            }

            return $return_data;
        }
        
        return '-';
    }
    
    function result_types()
    {
        $result_types = array(
            1 => '1st Division',
            2 => '2nd Division',
            3 => '3rd Division',
            7 => '1st Class',
            8 => '2nd Class',
            9 => '3rd Class',
            4 => 'Passed',
            5 => 'GPA(out of 4)',
            6 => 'GPA(out of 5)',
        );

        return $result_types;
    }
    
    function tender_status()
    {
        $tender_status = array(
            0 => 'Upcoming',
            1 => 'EOI Submitted',
            2 => 'EOI Not Submitted',
            3 => 'Shortlisted',
            4 => 'Not Shortlisted',
            7 => 'RFP Submitted',
            8 => 'RFP Not Submitted',
            5 => 'Project Owned',
            6 => 'Declined',
        );
        
        return $tender_status;
    }

    function taskTypes()
    {
        $taskTypes = array(
            1 => 'Outdoor Task',
            2 => 'Indoor Task',
        );
        
        return $taskTypes;
    }

    function timeAreas()
    {
        $timeAreas = array(
            1 => 'First Half',
            2 => 'Second Half',
            0 => 'Whole Day',
        );
        
        return $timeAreas;
    }

    function hours()
    {
        $hours = array(
            1 => '1 Hour',
            2 => '2 Hour',
            3 => '3 Hour',
            4 => '4 Hour',
            5 => '5 Hour',
            6 => '6 Hour',
            7 => '7 Hour',
            8 => '8 Hour',
            9 => '9 Hour',
            10 => '10 Hour',
            11 => '11 Hour',
            12 => '12 Hour',
        );
        
        return $hours;
    }

    function prioritys()
    {
        $prioritys = array(
            1 => 'Low',
            2 => 'Medium',
            3 => 'High',
        );
        
        return $prioritys;
    }

    function ticketStatuses()
    {
        $ticketStatuses = array(
            0 => 'Pending',
            1 => 'Accepted',
            2 => 'Solved',
            3 => 'Declined',
        );
        
        return $ticketStatuses;
    }