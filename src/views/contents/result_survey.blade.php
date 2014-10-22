<?php $statistics = $quiz->statistics();?>
@foreach( $statistics as $one )
<?php
    $_id = md5( json_encode( $one ));
    $_data = array();
    $colors = [ '#F7464A' , '#46BFBD' , '#FDB45C' , '#FFC870' , '#EE646A' ];
    $_i = 0;
    foreach( $one as $key => $val ){
        if( !in_array( $key , array( 'question' , 'text' ) ) ){
            $_data[] = array(
                'value' => $val['count'] ,
                'label' => $key ,
                'color' => $colors[$_i++%5] ,
                'highlight' => $colors[$_i++%5] ,
            );
        }
    }
?>
    <h2>问题 {{ $one['question'] }}</h2>
    <canvas id="{{ $_id }}" width="400" height="400"></canvas>
    <script>
    $(function(){
        var ctx = $("#<?=$_id?>").get(0).getContext("2d");
        var data = <?=json_encode( $_data , JSON_UNESCAPED_UNICODE )?>;
        new Chart(ctx).Pie(data);
    })
    </script>
@endforeach