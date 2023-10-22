
<!DOCTYPE html>
<html lang="en" id="crysandrea">
<body>
<div id="content">
  <div class="feature_header">
<ul class="feature_navigation">
</ul>
</div>
<style type="text/css">.trade_container{width:400px;float:left;margin-top:5px;}.tc_box{background:#ddd;border-radius:6px;padding:5px;margin-bottom:5px;width: 615px;height: 150px;}.tc_header{height:60px;overflow:hidden}.tc_header img.avatar{float:left;}.tc_header .tc_user_box{margin-left:5px;padding-top:10px;float:left;}.tc_items{background:#fff;padding:5px;border-radius:4px;margin-top:5px;border:1px solid #d0d0d0;overflow:hidden;height:110px}.tc_items a{border:1px solid #fff;float:left;margin:1px;padding:1px;border-radius:3px;}.tc_items a img{float:left;}.xflip{-moz-transform:scaleX(-1);-webkit-transform:scaleX(-1) translate3d(0,0,0);-o-transform:scaleX(-1);transform:scaleX(-1);-ms-filter:fliph;filter:fliph;   }.modify_item{-webkit-transition:all 150ms ease;-moz-transition:all 150ms ease;-ms-transition:all 150ms ease;-o-transition:all 150ms ease;transition:all 150ms ease;}.modify_item:hover{border-color:#ee9d9c;}.modify_item:active{border-color:#aaa;background:#eee;opacity:0.7;}.empty_offer{text-align:center;color:#aaa;padding:15px;font-size:14px;letter-spacing:1px;}#inventory_items>div.tab-pane{overflow:hidden}#inventory_items>div.tab-pane>a{float:left;margin:1px 1px;padding:2px;border-radius:4px;}</style>

<div class="def_content" style="float:left;position: absolute;">
<h3>Search your items</h3>
<div class="push_up">
<form class="form-search" style="margin-top:3px;" id="search_inventory" method="GET" action="/trades/view_trade/705" >
<input type="text" id="search_inventory_query" name="search_items" class="input-medium button_sync" placeholder="Search inventory..." style="width:250px;">
 
</form>
</div>
</div>
<div class="def_structure" style="width: 415px;float:right;">
<div class="def_content">
<h3>Add a type of currency?</h3>
<div class="push_up">
<form class="form-search pull-left" id="add_currency" method="POST" action="/trades/add_currency/705">
<input type="text" name="total_amount" id="total_currency_amount" class="button_sync" style="width:70px;" placeholder="500">
<select name="currency_type" id="trade_currency" style="width:160px;">
<option value="ores">Ores</option>
<option value="berries">Berries</option>
</select>
<select name="modify_method" id="modify_method" style="width:70px;">
<option value="add">Add</option>
<option value="remove">Remove</option>
</select>
<button type="submit" class="main_button" id="modify_currency" disabled>Modify</button>
</form>
</div>
</div>
<br/>
<div class="clearfix" style="display: block;
position: absolute;
top: 135px;
right: 100px;">
<ul class="nav nav-tabs" id="avatar_items" style="position:relative;top:100px;">
<li><a href="#items">Items</a></li>
<li><a href="#appearance">Appearance</a></li>
<li><a href="#bottom">Bottom</a></li>
<li><a href="#tops">Tops</a></li>
<li><a href="#accessories">Accessories</a></li>
<li><a href="#hair">Hair</a></li>
<li><a href="#head">Head</a></li>
<li><a href="#bugs"><i class="icon-leaf"></i> Bugs</a></li>
 
</ul>
<div class="tab-content" id="inventory_items">
</div>
</div>

<br/><br>
<br>
<br>
<br>
<br>
<br>

<div class="clearfix" style="position: absolute;
top: 350px;
left: 275px;">
<div class="trade_container" style="margin-right:6px" id="sender_container">
<div class="tc_box">
<div class="tc_currencies row">
<div class="currency palladium span3">
<span>Ores:</span>
<strong>1</strong>
</div>
<div class="currency berries span3">
<span>Berries:</span>
<strong>0</strong>
</div>
</div>
<div class="tc_items">

<div class="empty_offer" style="display:none"> No items in offer </div>
</div>
</div>
 
<button class="btn btn-success disabled" type="submit" disabled><i class="icon-ok icon-white"></i> Combine</button>
or
<form action="/trades/accept_trade/705/cancel" method="POST" style="display:inline">
<button class="btn btn-danger" type="submit" disabled><i class="icon-remove icon-white"></i>Cancel</button>
</form>
</div>
<div class="trade_container" id="receiver_container">
<div class="tc_header">
</div>
</div>
</div><br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

<hr>
<div class="clearfix">

 
<div id="loading_large_transaction" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="loading_large_transaction" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<h3 id="myModalLabel">This may take a bit...</h3>
</div>
<div class="modal-body" style="text-align:center;">
<p>We thought it would be polite to let you know that the little server is working hard to trade your large batch of items. Depending on the load of the server and the amount of items you want to trade, this could take about 30-60 seconds.</p>
<br/>
<img src="/images/icons/large_loader.gif" alt=""/>
</div>
<div class="modal-footer">
</div>
</div>
<div id="accept_confirmation" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="accept_confirmation" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h3>A word of caution</h3>
</div>
<div class="modal-body" style="text-align:center;">
<p>
Are you sure you want to accept the trade before they've made an offer? If they accept the trade, it will be finalized without you receiving anything. You're safer waiting for them to make an offer and accept the trade first.
</p>
<hr/>
<p><strong>Note:</strong> Any item or change you make to the trade is offered</p>
</div>
<div class="modal-footer">
<form action="/trades/accept_trade/705" method="POST" style="display:inline">
<button class="btn btn-success" type="submit"><i class="icon-ok icon-white"></i> I understand, accept anyway</button>
</form>
<button id="wait_for_offer" class="btn">Wait for their offer</button>
</div>
</div>
  </div>
  </div>
  </div>
  </div>
  </body>
