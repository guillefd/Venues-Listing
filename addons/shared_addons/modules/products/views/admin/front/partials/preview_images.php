<section class="title">
    <h4>  </h4>            
</section>
<section class="item">
    <table class="blue">
        <tbody>
            <?php foreach($imgsizes as $key=>$sz): ?>            
                <tr>
                    <td>
                        <h2>Tamaño: <?php echo $key ?></h2>
                        Uso: <?php echo $sz[2] ?> 
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php if( check_imagesize_before_crop($imgdata, $sz, $key) ): ?>                        
                            <img src="<?php echo $basethumbimguri.$imgdata['id'].'/'.$sz[0].'/'.$sz[1].'/fit'; ?>" width="<?php echo $sz[0] ?>px" height="<?php echo $sz[1] ?>px" />
                        <?php else: ?>
                            <?php echo lang('front:imagesize_notbigenough'); ?>
                        <?php endif; ?>
                    </td>                                 
                </tr>        
            <?php endforeach; ?>                             
        </tbody>
    </table>            
</section>
<style>
table {
    font-family:Arial, Helvetica, sans-serif;
    color:#666;
    font-size:12px;
    text-shadow: 1px 1px 0px #fff;
    background:#eaebec;
    margin:20px;
    border:#ccc 1px solid;
    width: 95%;
    -moz-border-radius:3px;
    -webkit-border-radius:3px;
    border-radius:3px;

    -moz-box-shadow: 0 1px 2px #d1d1d1;
    -webkit-box-shadow: 0 1px 2px #d1d1d1;
    box-shadow: 0 1px 2px #d1d1d1;
}
table th {
    padding:21px 25px 22px 25px;
    border-top:1px solid #fafafa;
    border-bottom:1px solid #e0e0e0;

    background: #ededed;
    background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));
    background: -moz-linear-gradient(top,  #ededed,  #ebebeb);
}
table th:first-child {
    text-align: left;
    padding-left:20px;
}
table tr:first-child th:first-child {
    -moz-border-radius-topleft:3px;
    -webkit-border-top-left-radius:3px;
    border-top-left-radius:3px;
}
table tr:first-child th:last-child {
    -moz-border-radius-topright:3px;
    -webkit-border-top-right-radius:3px;
    border-top-right-radius:3px;
}
table tr {
    text-align: center;
    padding-left:20px;
}
table td:first-child {
    text-align: left;
    padding-left:20px;
    border-left: 0;
}
table td {
    padding:18px;
    border-top: 1px solid #ffffff;
    border-bottom:1px solid #e0e0e0;
    border-left: 1px solid #e0e0e0;

    background: #fafafa;
    background: -webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#fafafa));
    background: -moz-linear-gradient(top,  #fbfbfb,  #fafafa);
}
table tr.even td {
    background: #f6f6f6;
    background: -webkit-gradient(linear, left top, left bottom, from(#f8f8f8), to(#f6f6f6));
    background: -moz-linear-gradient(top,  #f8f8f8,  #f6f6f6);
}
table tr:last-child td {
    border-bottom:0;
}
table tr:last-child td:first-child {
    -moz-border-radius-bottomleft:3px;
    -webkit-border-bottom-left-radius:3px;
    border-bottom-left-radius:3px;
}
table tr:last-child td:last-child {
    -moz-border-radius-bottomright:3px;
    -webkit-border-bottom-right-radius:3px;
    border-bottom-right-radius:3px;
}
table tr:hover td {
    background: #f2f2f2;
    background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#f0f0f0));
    background: -moz-linear-gradient(top,  #f2f2f2,  #f0f0f0);  
}
</style>