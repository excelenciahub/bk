<?php
    require_once("../../include/config.php");
    $instance = new order_master();
    $q = "SELECT COUNT(*) AS orders, DATE_FORMAT(om.created_time,'%d-%m-%Y') AS order_date,om.payment_type
            FROM ".ORDER_MASTER." AS om
            WHERE om.is_delete=0
            GROUP BY DATE_FORMAT(om.created_time,'%m-%d-%Y'),om.payment_type";
    $instance->db_query($q);
    $records = $instance->db_fetch_all();
    $category = array();
    $data = array();
    foreach($records as $key=>$val){
        $category[$val['order_date']] = $val['order_date'];
        $data[$val['payment_type']]['name'] = $val['payment_type']==1?'Instant':'Future';
        $data[$val['payment_type']]['data'][] = intval($val['orders']);
        if($val['payment_type']==1){
            $data[$val['payment_type']]['color'] = 'green';
        }
        else{
            $data[$val['payment_type']]['color'] = 'orange';
        }
    }
    $cat = json_encode(array_values($category));
    $data = json_encode(array_values($data));
?>
<div id="no_of_orders_chart"></div>
<script type="text/javascript">
    Highcharts.chart('no_of_orders_chart', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Number of orders'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            min: 0,
            categories: <?php echo $cat; ?>,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Orders'
            }
        },
        allowDecimals: false,
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 1,
                dataLabels: {
                    enabled: true
                }
            }
        },
        series: <?php echo $data; ?>
    });
</script>