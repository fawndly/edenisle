<script type="text/javascript" charset="utf-8">
    function showitem_by_layer(layer) {
        $("div .pointer").removeClass("currentlayer");
        $("#l"+layer).addClass("currentlayer");
        $.post('/pcp/get_items_on_layer/'+layer, function(data) {
            $('#items').html(data);
        });
    }

    function get_subs(item_id){
        $("#items img").removeClass("currentitem");
        $("#i"+item_id).addClass("currentitem");

        $.post('/pcp/get_sub_layers/'+item_id, function(data) {
            $('#subs').html(data);
        });
        $.post('/pcp/get_other_items/'+item_id, function(data) {
            $('#other').html(data);
        });
    }
</script>
<style>
#items img, #subs img {
    padding:2px;
    margin:1px;
    border-radius:5px;
    border:solid green 2px;
}

#items img:hover, .pointer:hover {cursor:pointer;}
.float {float:left;}

#select_layer, #items, #subs, #other {
    height:300px;
    overflow-y:scroll;
}
#items {width:180px}
#subs  {width:272px}

#subs > div > span {display: block;}
#subs > div {
    display: inline-block;
    text-align: center;
    padding: 4px;
    margin-bottom: 12px;
}

#other {width: 100%}
.currentlayer {
    font-weight:bold;
    color:green;
}
.currentitem {
    background: #7BCC70;
}
</style>

<div style="overflow: hidden">
    <h3 style="margin: 0">Layering help</h3>
    <p>This page lets you know which layers are used together, and what kind of items are on those layers.</p>
    <div class="float">
        <h5>Main layers <small>Order + Name</small></h5>
        <div id="select_layer" style="max-width: 260px;">
            <?php foreach($main_layers as $layer):?>
                <div class="pointer" id="l<?=$layer['id']?>" onclick="showitem_by_layer(<?=$layer['id']?>)" ><?=$layer['order']?> - <?=$layer['name']?></div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="float">
        <h5>Items</h5>
        <div id="items"></div>
    </div>
    <div class="float">
        <h5>Layers item</h5>
        <div id="subs"></div>
    </div>
    <div class="clear: both;"></div>
    <div id="other"></div>
</div>
